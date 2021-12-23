<?php

namespace api\canteen\controller;

use app\canteen\model\CustomerModel;
use cmf\controller\RestBaseController;
use app\canteen\model\CanteenModel;
use think\Request;

class CanteenController extends RestBaseController
{
    /**
     *
     * @return canteen 食堂信息
     *
     */
    public function canteenInfo()
    {
        $canteen = new CustomerModel();
        $data = $canteen->canteenTotal()->select();
        //dump($data);

        if ($data != null){
            return $this->success("返回成功",$data);
        }else{
            return $this->error("失败");
        }
    }

    /**
     *
     * 用户绑定食堂
     * @param  cus_no  用户编号
     * @param  can_no  食堂编号
     * @return canteen 食堂名称
     */
    public function bind(Request $request)
    {
        $customer = new CustomerModel();

        $cus_no = $request->param('cus_no');

        $customer = $customer->where('cus_no',$cus_no)->find();

        //$arr = $customer->toArray();
        //dump($arr['cus_no']);

         if ($request->isPost()) {
             $can_no = $request->param('can_no');
             $customer->can_no = $can_no;
             $canteen = CanteenModel::where('can_no',$can_no)->find()->toArray();
             $customer->save();

             if ($customer->save()) {
                 return $this->success("返回成功",$canteen['canteen']);
             } else {
                 return $this->error("失败");
             }
         }
    }

    /**
     *
     * 用户解除绑定食堂
     * @param cus_no 用户编号
     *
     */
    public function unBind(Request $request)
    {
        if ($request->isPost())
        {
            $cus_no = $request->param('cus_no');
            $customer = CustomerModel::where('cus_no',$cus_no)->find();

            $customer->can_no = null;
            $customer->save();

            if ($customer->save()) {
                return $this->success("返回成功");
            } else {
                return $this->error("失败");
            }

        }
    }



}