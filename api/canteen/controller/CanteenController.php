<?php

namespace api\canteen\controller;

use cmf\controller\RestBaseController;
use app\canteen\model\CanteenModel;


class CanteenController extends RestBaseController
{
    //展示食堂信息
    public function index()
    {
        $canteen = new CanteenModel();

        $data = $canteen->schJoinCan()->paginate(10);

        if ($data != null){
            return $this->success("返回成功",$data);
        }else{
            return $this->error("失败");
        }
    }


}