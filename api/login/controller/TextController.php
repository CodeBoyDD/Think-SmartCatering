<?php


namespace api\login\controller;


use cmf\controller\RestBaseController;
use think\facade\Db;

class TextController extends RestBaseController
{
    public function index()
    {
        $d = Db::table('yfc_a_token')->field('cus_no')->where(['openid' => 'oMVS66TuB6qWCvCfsTGd0vfLpivs'])->select()->toArray();
//        dump($d[0]['cus_no']);
        $this->token = md5('cus_no' . $d[0]['cus_no'] . 'openid=>oMVS66TuB6qWCvCfsTGd0vfLpivs',true);
        var_dump($this->token);
//        Db::table('yfc_a_token')->where(['openid' => 'oMVS66TuB6qWCvCfsTGd0vfLpivs'])
//            ->update(['openid_time' => time(), 'token' => $token, 'token_time' => time()]);

    }
}