<?php

namespace api\login\controller;

use cmf\controller\RestBaseController;
//use think\Exception;
use think\facade\Db;
use think\Request;

class UserloginController extends RestBaseController
{
    private $phone = '';
    //uni-app's appid
    private $appid = 'wxb588f7ba494a769d';
    //uni-app's secret
    private $secret = '262fa48117f60c2bd78c1b4bb4e06efc';
    //the grant_type
    private $grant_type = 'authorization_code';
    //the url of uni-app to get the openid
    private $url = "https://api.weixin.qq.com/sns/jscode2session";
    
    public function wxLogin(Request $request)
    {
        //得到前端传入的数据
        $code = $request->param("code");
        $rawData = $request->param("rawData");
        $signature = $request->param("signature");
        $encryptedData = $request->param("encryptedData");
        $iv = $request->param('iv');

        //封装数据
        $params = [
            'appid' => $this->appid,
            'secret' => $this->secret,
            'js_code' => $code,
            'grant_type' => $this->grant_type
        ];
        $res = $this->makeRequest($this->url, $params);

        if ($res['code'] !== 200 || !isset($res['result']) || !isset($res['result'])) {
            return json($this->ret_message('TF'));//requestTokenFailed
        }
        $reqData = json_decode($res['result'], true);
        if (!isset($reqData['session_key'])) {
            return json($this->ret_message('T1'));
        }
        //echo $reqData['session_key'];

        $sessionKey = $reqData['session_key'];
        $openid = $reqData['openid'];


//        $signature2 = sha1($rawData.$sessionKey);
//        if ($signature2 !== $signature) return json($this->ret_message("SN"));//signNotMatch

        /*解密手机号*/
        $pc = new WXBizDataCrypt1($params['appid'], $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );


        if ($errCode == 0) {
            $data = json_decode($data,true);
            echo $data['phoneNumber'];
            $this->phone = $data['phoneNumber'];//返回的string类型的手机号
        } else {
            print($errCode . "\n");
        }

        // 如果openid存在，更新openid_time,返回登录成功信息及手机号
        if ($this->userin($openid)) {
            // openid存在，先判断openid_time,与现在的时间戳相比，如果相差大于4个小时，则则返回登录失败信息，使客户端跳转登录页，如果相差在四个小时之内，则更新openid_time，然后返回登录成功信息及手机号；
            // 根据openid查询到所在条数据
            $data = Db::table('yfc_a_token')->where('openid', $openid)->find();
            $this->token = md5('cus_no'.$data['cus_no'].'openid=>'.$openid);
            // 计算openid_time与现在时间的差值
            $time = time() - $data['openid_time'];
            $time = $time / 3600;
            // 如果四个小时没更新过，则登陆态消失，返回失败，重新登录
            if ($time > 4) {
                $update = Db::table('yfc_a_token')->where('openid', $openid)->update(['openid_time' => time()]);
                return json(['sendsure' => '0', 'message' => '登录失效，已重新登录',]);

            //小于则自动登录
            } else {
                $update = Db::table('yfc_a_token')->where('openid', $openid)->update(['openid_time' => time(),'token'=>$this->token,'token_time'=>time()]);
                if ($update) {
                    $this->success('1',$this->token, ['token'=>$this->token]);
                    return json(['sendsure' => '1', 'message' => '登录成功',]);
                } else {
                    return json(['sendsure' => '-1', 'message' => '数据传入错误']);
                }
            }
        }
        else{
    $is_in_token = Db::table('yfc_a_token')->where('user_phone', $this->phone)->find();
    if ($is_in_token){
        $this->token = md5('cus_no'.$is_in_token['cus_no'].'openid=>'.$openid);
        $update = Db::table('yfc_a_token')
            ->where('user_phone', $this->phone)
            ->update([
                'openid'        =>  $openid,
                'openid_time'   =>  time(),
                'token'         =>  $this->token,
                'token_time'    =>  time()
            ]);
        if ($update) {
            $this->success('1',$this->token, ['token'=>$this->token]);
            return json(['sendsure' => '1', 'message' => '登录成功',]);
        }else{
            return json(['sendsure' => '-1', 'message' => '数据传入失败',]);
        }
    }else {
        $is_in_cus = Db::table('yfc_a_customer')->where('phone', $this->phone)->find();
        if ($is_in_cus) {
            $this->token = md5('cus_no' . $is_in_cus['cus_no'] . 'openid=>' . $openid, true);
            $update = Db::table('yfc_a_token')
                ->insert([
                    'cus_no' => $is_in_cus['cus_no'],
                    'openid' => $openid,
                    'openid_time' => time(),
                    'user_phone' => $this->phone,
                    'token' => $this->token,
                    'token_time' => time()
                ]);
        }
    }
    }
    }

    public function register()
    {

    }

//else{
//    $is_in_token = Db::table('yfc_a_token')->where('user_phone', $this->phone)->find();
//    if ($is_in_token){
//        $this->token = md5('cus_no'.$is_in_token['cus_no'].'openid=>'.$openid);
//        $update = Db::table('yfc_a_token')
//            ->where('user_phone', $this->phone)
//            ->update([
//                'openid'        =>  $openid,
//                'openid_time'   =>  time(),
//                'token'         =>  $this->token,
//                'token_time'    =>  time()
//            ]);
//        if ($update) {
//            $this->success('1',$this->token, ['token'=>$this->token]);
//            return json(['sendsure' => '1', 'message' => '登录成功',]);
//        }else{
//            return json(['sendsure' => '-1', 'message' => '数据传入失败',]);
//        }
//    }else{
//        $is_in_cus = Db::table('yfc_a_customer')->where('phone', $this->phone)->find();
//        if ($is_in_cus) {
//            $this->token = md5('cus_no' . $is_in_cus['cus_no'] . 'openid=>' . $openid, true);
//            $update = Db::table('yfc_a_token')
//                ->insert([
//                    'cus_no' => $is_in_cus['cus_no'],
//                    'openid' => $openid,
//                    'openid_time' => time(),
//                    'user_phone' => $this->phone,
//                    'token' => $this->token,
//                    'token_time' => time()
//                ]);




    /**——————————————————————————————————————————————————————————————————————————————————————————
     * 判断用户存在
    ————————————————————————————————————————————————————————————————————————————————————————————*/
    public function userin($openid){
        return Db::table('yfc_a_token')->where('openid',$openid)->find();
    }

    /**——————————————————————————————————————————————————————————————————————————————————————————
     * 生成token
    ————————————————————————————————————————————————————————————————————————————————————————————*/
    public function makeToken(){
        $str = md5(uniqid(md5(microtime(true)), true)); //生成一个不会重复的字符串
        $str = sha1($str); //加密
        return $str;
    }



    /**——————————————————————————————————————————————————————————————————————————————————————————
     * 以下是工具函数
     ————————————————————————————————————————————————————————————————————————————————————————————*/


    /**
     * 返回信息
     * @param $message
     * @return array
     */
    function ret_message($message = "") {
        if ($message == "") return ['result'=>0, 'message'=>''];
        $ret = lang($message);
//        dump($ret);
//        if (count($ret) != 2) {
//            return ['result'=>-1,'message'=>'未知错误'];
//        }
        return array(
            'result' => -1,
            'message' => $ret
        );
    }

    function makeRequest($url, $params = array(), $expire = 0, $extend = array(), $hostIp = '')
    {
        if (empty($url)) {
            return array('code' => '100');
        }

        $_curl = curl_init();
        $_header = array(
            'Accept-Language: zh-CN',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache'
        );
// 方便直接访问要设置host的地址
        if (!empty($hostIp)) {
            $urlInfo = parse_url($url);
            if (empty($urlInfo['host'])) {
                $urlInfo['host'] = substr(DOMAIN, 7, -1);
                $url = "http://{$hostIp}{$url}";
            } else {
                $url = str_replace($urlInfo['host'], $hostIp, $url);
            }
            $_header[] = "Host: {$urlInfo['host']}";
        }

// 只要第二个参数传了值之后，就是POST的
        if (!empty($params)) {
            curl_setopt($_curl, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($_curl, CURLOPT_POST, true);
        }

        if (substr($url, 0, 8) == 'https://') {
            curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($_curl, CURLOPT_URL, $url);
        curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($_curl, CURLOPT_USERAGENT, 'API PHP CURL');
        curl_setopt($_curl, CURLOPT_HTTPHEADER, $_header);

        if ($expire > 0) {
            curl_setopt($_curl, CURLOPT_TIMEOUT, $expire); // 处理超时时间
            curl_setopt($_curl, CURLOPT_CONNECTTIMEOUT, $expire); // 建立连接超时时间
        }

// 额外的配置
        if (!empty($extend)) {
            curl_setopt_array($_curl, $extend);
        }

        $result['result'] = curl_exec($_curl);
        $result['code'] = curl_getinfo($_curl, CURLINFO_HTTP_CODE);
        $result['info'] = curl_getinfo($_curl);
        if ($result['result'] === false) {
            $result['result'] = curl_error($_curl);
            $result['code'] = -curl_errno($_curl);
        }

        curl_close($_curl);
        return $result;
    }


}