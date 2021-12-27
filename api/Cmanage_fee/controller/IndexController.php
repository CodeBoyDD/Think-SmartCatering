<?php


namespace api\Cmanage_fee\controller;


use api\Ccommon\controller\changeUserController;
use api\Ccommon\model\CanteenModel;
use api\Ccommon\model\CustomerModel;
use api\Ccommon\model\ClassModel;
use api\Cmanage_fee\model\IndexModel;
use cmf\controller\RestBaseController;
use think\facade\Db;

class IndexController extends RestBaseController
{
    public function index(){
        $cus = changeUserController::index();
        $cus_detail = CustomerModel::where('cus_no',$cus['no'])->find();
        $data = new IndexModel();
        $res = $data->getOrderlist($cus_detail['school_no'],1,'1111');
        dump($res);
    }
}