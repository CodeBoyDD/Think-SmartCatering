<?php


namespace api\login\controller;



use api\login\model\StuModel;
use cmf\controller\RestBaseController;
use think\facade\Db;
use think\Request;

class StuController extends RestBaseController
{
    public function index(Request $request){
        $can_no = $request->param('can_no');
        $cus_type = $request->param('cus_type');
        $customer = $request->param('customer');

        $cus = new StuModel();
        if($customer){
            $is_cus = $cus->sdata()->where('customer',$customer)->find();
            if ($is_cus){
                $update = $cus->sdata()->where('customer',$customer)->update(['can_no'=>$can_no,'cus_type'=>$cus_type]);
                if($update){
                    return json(['res'=>'123','message'=>'更新数据成功',]);
                }else{
                    return json(['res'=>'010','message' => '操作错误',]);
                }
            }else{
                $a = $cus->sdata()->insert(['customer'=>$customer]);
                if($a) {
                    $res = $cus->sdata()->where('customer', $customer)->update(['can_no'=>$can_no,'cus_type'=>$cus_type]);
                    if ($res) {
                        return json(['res'=>'123','message' => '更新数据成功',]);
                    } else {
                        return json(['res'=>'000','message' => '数据传入失败',]);
                    }
                }else{
                    return json(['res'=>'010','message' => '操作错误',]);
                }
            }
        }else {
            return json(['res'=>'000','message' => '数据传入失败',]);
        }
    }

}