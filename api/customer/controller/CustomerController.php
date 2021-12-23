<?php

namespace api\customer\controller;

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
            return $this->success("返回成功");
        }else{
            return $this->error("失败");
        }
    }

    /**
     * 学生权限下
     * 用户班级绑定
     * @param cus_no
     */

    /**
     * 学生权限下
     * 用户输入学生姓名
     * @param customer
     */
    public function inputStuName(Request $request)
    {
        /*向数据库中插入用户信息*/
        $customer = new CustomerModel();

        //自动生成用户编号
        $customer->cus_no   = 'cus'.date('ymd',time());
        $customer->customer = $request->param('customer');
        $customer->save();

        if ($customer->save()){
            return $this->success("返回成功");
        }else{
            return $this->error("失败");
        }
    }
}