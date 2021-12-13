<?php


namespace app\Paymanage\controller;


use app\common\CommonModel;
use app\Paymanage\model\PayModel;
use cmf\controller\AdminBaseController;
use think\Request;

class PayController extends AdminBaseController
{
    public function index(){
        return $this->fetch();
    }

    public function listRough(){
        $pay = new PayModel();
        $data = $pay->culuedata();
        dump($data);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function listDetail(){
        $pay = new PayModel();
        $data1 = $pay->detaildata()->select()->toArray();
        $tool = new CommonModel();
        $data = $tool->machiningData($data1,'school_name','canteen');
        dump($data);
        $this->assign('data',$data);

        $time1 = $this->request->param('time1');
        $time2 = $this->request->param('time2');
        if($time1 && $time2){
            $arr = array('time1'=>$time1,'time2'=>$time2);
            $val = $this->validate($arr,'pay.tie');
            dump($val);
            if ($val !== true) {
                $this->error($val);
            }else {
                $data1 = $pay->detaildata()->where('or.order_time', 'between time', [$time1, $time2])->select()->toArray();
                $data = $tool->machiningData($data1, 'school_name', 'canteen');
                $this->assign('data', $data);
            }
        }
        return $this->fetch();
    }

    public function getSum(Request $request){
        $sum = new PayModel();
        $this->assign('data',null);
        if($request->param()){
            $status = $request->param('status');
            $data = $sum->sumPaid($status);
            dump($data);
            $this->assign('data',$data);
            if(array_keys($data)[0] == ""){
                $this->error('无数据');
            }
        }
        return $this->fetch();
    }
}