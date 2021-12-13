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

}