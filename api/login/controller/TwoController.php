<?php


namespace api\login\controller;


use cmf\controller\RestBaseController;

class TwoController extends RestBaseController
{
    protected $config=[
        'APPID'=>'wx622d152e88961da4',
        'secret'=> '65187f031e483c70d55ceae830077ee6'
    ];

    public function car_login()
    {
        //开发者使用登陆凭证 code 获取 session_key 和 openid
        $APPID = $this->config->item('APPID');//自己配置
        $AppSecret = $this->config->item('AppSecret');//自己配置
        if(empty($this->input->post('code')) ||
            empty($this->input->post('signature')) || 
             empty($this->input->post('rawData')) ||
              empty($this->input->post('encryptedData')) || 
               empty($this->input->post('iv'))){
            $this->render(0,'参数缺失');
        }
        $code = $this->input->post('code');
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $APPID . "&secret=" . $AppSecret . "&js_code=" . $code . "&grant_type=authorization_code";
        $arr = $this->vget($url);  // 一个使用curl实现的get方法请求
        $arr = json_decode($arr, true);
        if(empty($arr)||empty($arr['openid'])||empty($arr['session_key'])){
            $this->render(0,'请求微信接口失败,appid或私钥不匹配！');
        }
        $openid = $arr['openid'];
        $session_key = $arr['session_key'];
        // 数据签名校验
        $signature = $this->input->post('signature');
        $rawData = $this->input->post('rawData');
        $signature2 = sha1($rawData . $session_key);
        if ($signature != $signature2) {
            $this->render(0,'数据签名验证失败！');
        }
        require_once dirname(dirname(__DIR__)) . '/libraries/wx_aes/wxBizDataCrypt.php';
        $encryptedData = $this->input->post('encryptedData');
        $iv = $this->input->post('iv');
        $pc = new \WXBizDataCrypt($APPID, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);  //其中$data包含用户的所有数据
        $data = json_decode($data,true);
        if ($errCode == 0) {
            $this->car_owner_model->api_save($data);
            $time = 2*60*60;
            $data['sid'] = md5($session_key);
            $key = 'ses_'.$data['sid'];
            $user_json = json_encode($data);
            $this->cache->redis->save($key,$user_json,$time);
            $this->return['data'] = $data;
        } else {
            $this->render(0,$errCode);
        }
    }

    public function vget($url){
        $info=curl_init();
        curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($info,CURLOPT_HEADER,0);
        curl_setopt($info,CURLOPT_NOBODY,0);
        curl_setopt($info,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($info,CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($info,CURLOPT_URL,$url);
        $output= curl_exec($info);
        curl_close($info);
        return $output;
    }


}