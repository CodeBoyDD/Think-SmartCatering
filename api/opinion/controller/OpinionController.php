<?php

namespace api\opinion\controller;

use app\opinion\model\OpinionModel;
use cmf\controller\RestBaseController;
use think\Request;

class OpinionController extends RestBaseController
{
    /*显示意见信息*/
    public function index()
    {
        $opinion = new OpinionModel();
        $data = $opinion->opinionList()->select();

        if ($data != null){
            return $this->success("返回成功",$data);
        }else{
            return $this->error("失败");
        }
    }

    /*意见反馈*/
    public function opAdd()
    {
        $opinion = new OpinionModel();

        if (request()->isPost()){
//            $data = [
//                op_no => $request->param('op_no'),
//                op_title => $request->param('op_title'),
//                op_content => $request->param('op_content'),
//                cus_no => $request->param('cus_no'),
//                can_no => $request->param('can_no')
//            ];
            $data = input('param.');
            /*验证器*/
            $opinion->insert($data);

            if ($data != null){
                return $this->success("提交意见成功",$data);
            }else{
                return $this->error("失败");
            }
        }
    }
}