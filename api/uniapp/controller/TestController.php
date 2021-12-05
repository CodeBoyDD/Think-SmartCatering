<?php

namespace api\uniapp\controller;

use cmf\controller\RestBaseController;
use app\canteen\model\CanteenModel;


class TestController extends RestBaseController
{
    //展示食堂信息
    public function index()
    {
        $canteen = new CanteenModel();

        $data = $canteen->canJoinSch()->paginate(10);

        if ($data != null){
            return $this->success("返回成功",$data);
        }else{
            return $this->error("失败");
        }
    }

    //搜索食堂信息
    public function CanSearch($can_no,$canteen)
    {

        $can = new CanteenModel();
        $data = $can->canSearch($can_no,$canteen)->select()->toJson();

        if ($data != null){
            return $this->success("返回成功",$data);
        }else{
            return $this->error("失败");
        }

    }


}