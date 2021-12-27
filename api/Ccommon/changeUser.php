<?php


namespace api\Ccommon;


use cmf\controller\RestBaseController;
use think\Facade\Db;

class changeUser extends RestBaseController
{
    public function getuser(){
//        $token = $this->request->header('token');
        $token = '1234';
        //获取对应的用户id
        $result = Db::table('yfc_a_token')->where(['token'=>$token])->find();
        if ($result){
            //获取用户的类别
            $type = Db::table('yfc_a_customer')->where(['cus_no'=>$result['cus_no']])->find();
            $arr = array($result['cus_no'],$type['cus_type']);
            return $arr;
        }else{return ['code'=>'-1','msg'=>'无对应的用户'];}
    }
}