<?php

namespace app\Order\controller;

use app\Order\model\CanteenModel;
use app\Order\model\OrderModel;
use app\Order\model\SchoolModel;
use cmf\controller\AdminBaseController;
use think\facade\Cache;
use think\facade\Db;

class OrderController extends AdminBaseController
{
    public function index()
    {
        return $this->fetch();
    }

    //
    public function cuscontrol(){
        $cus = new OrderModel();
        $data = $cus->samepart()->select()->toArray();
        //dump($data);
        $this->assign('data',$data);
        return $this->fetch();
    }

    //
    public function ordcontrol(){
        $time1 =$this->request->param('time1');
        $time2 = $this->request->param('time2');
        $data = $this->request->param();
        if ($time1 && $time2){
            dump($data['device_type']);

            $arr = array('time1'=>$time1,'time2'=>$time2);
            $val = $this->validate($arr,'order.select');
            if ($val !== true) {
                $this->error($val);
            }else{ $cus = new OrderModel();
                $data = $cus->samepart()
                    ->where('or.order_time','between time', [$time1,$time2])
                    ->select()->toArray();
                if ($data == null){
                    $this->error('该时间段无订单信息');
                }
            }
        }else{
            $data = null;
        }
        $time = time();
        $this->assign('time',$time);
        $this->assign('data',$data);
        return $this->fetch();

    }

    //供应界面
    public function privide()
    {
        $school = new SchoolModel();
        $select = $school::select()->toArray();

        //$can_no='';
        $this->assign([
            'select'=>$select,
            'select2'=>null,
            'data'=>null]);
        $school_no = $this->request->param('school_no');
        $canteen = new CanteenModel();
        if ($school_no){

            $select2 = $canteen::where('school_no',$school_no)->select()->toArray();
            $this->assign('select2',$select2);
//            dump($select2);
//            $can_no = $this->request->param('can_no');

            Cache::set("can_no",$this->request->param('can_no'));
            }
            if (Cache::get("can_no")){
                $data = $canteen->backtype($school_no);
                $this->assign('data', $data);
       }

        return $this->fetch();
    }

    //改变供应状态
    public function pri_set1(){
        if ($this->request->isPost()) {
            $id = $this->request->param('id', 0, 'intval');
            if (!empty($id)) {
                $result = Db::table("yfc_a_type")->where("id", $id)->update(['fee' => null]);
                if ($result !== false) {
                    $this->success("申请成功！", url("order/privide"));
                    //dump会影响该行代码的执行，以至报出：
                    //Failed to load resource: the server responded with a status of 500 (Internal Server Error)
                    //的错误
                } else {
                    $this->error('申请失败！');
                }
            } else {
                $this->error('数据传入失败！');
            }
        }
    }

    //
    public function pri_set2(){
        if ($this->request->isPost()) {
            $id = $this->request->param('id', 0, 'intval');
            if (!empty($id)) {
                $result = Db::table("yfc_a_type")->where("id",$id)->update(['fee'=>'1']);
                if ($result !== false) {
                    $this->success("申请成功！", url("order/privide"));
                } else {
                    $this->error('申请失败！');
                }
            } else {
                $this->error('数据传入失败！');
            }
        }
    }
}