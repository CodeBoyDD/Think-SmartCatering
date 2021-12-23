<?php

namespace api\customer;

use app\canteen\model\CustomerModel;
use cmf\controller\RestBaseController;
use think\Request;

class CustomerController extends RestBaseController
{
    /**
     * 用户登录类型选择
     * 0 学生 / 1 管理员
     * @param cus_no
     * @param
     * @return
     */
    public function userType(Request $request)
    {
        $data = null;

        /*找到用户*/
        $cus_no = $request->param('cus_no');
        $customer = CustomerModel::where('cus_no',$cus_no)->find();

        /*修改用户类型*/
        $cus_type = $request->param('cus_type');
        $customer->cus_type = $cus_type;
        $customer->save();

        if ($customer->save()){
            return $this->success("返回成功",$data);
        }else{
            return $this->error("失败");
        }
    }

}