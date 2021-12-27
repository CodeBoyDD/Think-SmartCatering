<?php


namespace api\Ccommon\controller;


use cmf\controller\RestBaseController;
use think\Facade\Db;

class changeUserController extends RestBaseController
{
    public static function index(){
//        $token = $this->request->header('token');
        $token = '5678';
        //获取对应的用户id
        $result = Db::table('yfc_a_token')->where(['token'=>$token])->find();
        if ($result){
            //获取用户的类别
            $type = Db::table('yfc_a_customer')->where(['cus_no'=>$result['cus_no']])->find();
            $arr = array('no'=>$result['cus_no'],'type'=>$type['cus_type']);
//            dump($arr);
            return $arr;
        }else{return ['code'=>'-1','msg'=>'无对应的用户'];}
    }
}