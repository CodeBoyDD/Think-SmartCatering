<?php


namespace api\Cstudent\controller;


use api\Ccommon\controller\changeUserController;
use api\Cstudent\model\CanModel;
use api\Cstudent\model\InfoModel;
use api\Cstudent\model\OpinionModel;
use cmf\controller\RestBaseController;

class OpinionController extends RestBaseController
{
    //写入意见
    public function index(){
//        获取用户编号
        $cus = changeUserController::index();
//        dump($cus);
//        获取用户的食堂编号,食堂名字
        $can_no = InfoModel::where('cus_no',$cus['no'])->value('can_no');
        $canteen = CanModel::where('can_no',$can_no)->value('canteen');
//        获取意见输入信息
        $op_content = $this->request->param('op_content');
        if (!is_null($op_content)){
            $res = OpinionModel::insert([
                'cus_no'        =>      $cus['no'],
                'can_no'        =>      $can_no,
                'op_no'         =>      $canteen.'_'.date('yds',time()),
                'op_content'    =>      $op_content,
                'op_title'      =>      $canteen.'_'.$cus['no'],
            ]);
            dump($res);
            if ($res){
                return json(['msg'=>'感谢你的反馈，祝您生活愉快'],200);
            }else{
                return json(['msg'=>'无法读取数据'],500);
            }
        }else{
            return json(['msg'=>'没有编写意见'],2001);
        }

    }



}