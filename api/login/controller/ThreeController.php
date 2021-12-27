<?php


namespace api\login\controller;


use cmf\controller\RestBaseController;
//use api\lib\exception\ExceptionHandler;

class ThreeController extends RestBaseController
{
    private $input;
    private $cache;

    public function __construct()
    {
        parent::__construct();
        $this->sid = $this->input->get_request_header('sid',TRUE);
        if(!$this->sid)
        {
            \api\lib\exception\ExceptionHandler\render(0,'参数错误');
        }
        $key = 'ses_'.$this->sid;
        $this->user = json_decode($this->cache->redis->get($key),TRUE);
        if($this->user)
        {
            $this->openid = $this->user['openId'];
        }
        else
        {
            \api\lib\exception\ExceptionHandler\render(-1,'未登录');
        }
    }
}