<?php


namespace api\login\controller;


use cmf\controller\RestBaseController;
use cmf\controller\RestUserBaseController;
use think\Exception;
use think\facade\Db;
use think\Request;

include_once "wxBizDataCrypt1.php";

class LoginController extends RestBaseController
{
    public function phone(Request $request){
        $url = "https://api.weixin.qq.com/sns/jscode2session";

        $params['appid']= 'wx622d152e88961da4';
        $params['secret']= '65187f031e483c70d55ceae830077ee6';
        $params['js_code']= $request -> param('code');
        $params['grant_type']= 'authorization_code';
        $encryptedData = $request -> param('encryptedData');
        $iv = $request  ->  param('iv');
        // 判断是否成功
        if(isset($arr['errcode']) && !empty($arr['errcode'])){
            return json(['code'=>'2','message'=>$arr['errmsg'],"result"=>null]);
        }
        $openid = $arr['openid'];
        $session_key = $arr['session_key'];
        $pc = new WXBizDataCrypt1($params['appid'], $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );

        if ($errCode == 0) {
            $data = json_decode($data,true);
            return $data['phoneNumber'];//返回的string类型的手机号
        } else {
            print($errCode . "\n");
        }

    }

    public function index(Request $request){
        $url = "https://api.weixin.qq.com/sns/jscode2session";

        $params['appid']= 'wx622d152e88961da4';
        $params['secret']= '65187f031e483c70d55ceae830077ee6';
        $params['js_code']= $request -> param('code');
        $params['grant_type']= 'authorization_code';
        $encryptedData = $request -> param('encryptedData');
        $iv = $request  ->  param('iv');
        // 微信API返回的session_key 和 openid
        $arr = $this->httpCurl($url, $params, 'GET');
        $arr = json_decode($arr,true);

        // 判断是否成功
        if(isset($arr['errcode']) && !empty($arr['errcode'])){
            return json(['code'=>'2','message'=>$arr['errmsg'],"result"=>null]);
        }
        $openid = $arr['openid'];
        $session_key = $arr['session_key'];

        // 从数据库中查找是否有该openid
        $is_openid = Db::table('yfc_a_token')->where('openid',$openid)->find();
        // 如果openid存在，更新openid_time,返回登录成功信息及手机号
        if($is_openid) {
            // openid存在，先判断openid_time,与现在的时间戳相比，如果相差大于4个小时，则则返回登录失败信息，使客户端跳转登录页，如果相差在四个小时之内，则更新openid_time，然后返回登录成功信息及手机号；
            // 根据openid查询到所在条数据
            $data = Db::table('yfc_a_token')->where('openid', $openid)->find();
            // 计算openid_time与现在时间的差值
            $time = time() - $data['openid_time'];
            $time = $time / 3600;
            // 如果四个小时没更新过，则登陆态消失，返回失败，重新登录
            if ($time > 4) {
                $update = Db::table('yfc_a_token')->where('openid', $openid)->update(['openid_time' => time()]);
                return json(['sendsure' => '0', 'message' => '登录失效，已重新登录',]);

            } else {
                // 根据手机号更新openid时间
                $update = Db::table('yfc_a_token')->where('openid', $openid)->update(['openid_time' => time()]);
                if($update){
                    
                    return json(['sendsure'=>'1','message'=>'登录成功','user_phone' => $data['user_phone']]);
                }else{
                    return json(['sendsure'=>'0','message'=>'登录失败']);
                }
            }
            // openid不存在时
        }else {
            // dump($user_phone);
            //********************************************获取手机号start********************************************
            // 微信API返回的session_key 和 openid
            $arr = $this->httpCurl($url, $params, 'GET');
            $arr = json_decode($arr,true);
            $pc = new WXBizDataCrypt1($params['appid'], $session_key);
            $errCode = $pc->decryptData($encryptedData, $iv, $data );
            $data = json_decode($data,true);
            if ($errCode == 0) {
                $user_phone = $data['phoneNumber'];//返回的string类型的手机号
            //********************************************获取手机号end********************************************
                $is_phone = Db::table('yfc_a_token')->where('user_phone',$user_phone)->find();
                print($is_phone);
                if ($is_phone) {
                    // 如果不为空，则说明是登录过的，就从数据库中找到手机号，然后绑定openid，+时间
                    // 登录后,手机号不为空，则根据手机号更新openid和openid_time
                    $update = Db::table('yfc_a_token')
                        ->where('user_phone', $user_phone)
                        ->update([
                            'openid' => $openid,
                            'openid_time' => time()
                        ]);
                    if ($update) {
                        $this->success();
                        return json(['sendsure' => '1', 'message' => '登录成功',]);
                    }
                } else {
                    // 如果也为空，则返回登录失败信息，使客户端跳转登录页
                    return json(['sendsure' => '0', 'message' => '读取失败',]);
                }
            } else {
                print($errCode . "\n");
            }
            // 如果openid不存在, 判断手机号是否为空

        }
    }

    // 后台登录方法
    public function login(Request $request){
        // 获取到前台传输的手机号
        $user_phone = $request -> param('user_phone');
        // 判断数据库中该手机号是否存在
        $is_user_phone = Db::table('yfc_a_token')->where('user_phone',$user_phone)->find();
        if(isset($is_user_phone) && !empty($is_user_phone)){
            // 登录时，数据库中存在该手机号，则更新openid_time
            $update = Db::table('yfc_a_token')
                ->where('user_phone', $user_phone)
                ->update([
                    'openid_time' => time(),
                ]);
            if($update){
                return json(['sendsure'=>'1','message'=>'登录成功',]);
            }
        }else{
            $data = [
                "user_phone" => $user_phone,
                "pass" => '12345'
            ];
            // 如果数据库中不存在该手机号，则进行添加
            Db::table('yfc_a_token')->insert($data);
        }
        return json(['sendsure'=>'1','message'=>'登录成功',]);
    }

    public function httpCurl($url, $params, $method = 'GET', $header = array(), $multi = false){
        date_default_timezone_set('PRC');
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_COOKIESESSION  => true,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_COOKIE
            =>session_name().'='.session_id(),
        );

        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                // $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                // 链接后拼接参数  &  非？
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new Exception('不支持的请求方式！');
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error) throw new Exception('请求发生错误：' . $error);
        return  $data;

    }


}