<?php

/**
 * 报餐记录页
 */

namespace api\Cstudent\controller;

use api\Ccommon\controller\changeUserController;
use api\Cstudent\model\OrderModel;
use cmf\controller\RestBaseController;
use think\facade\Db;

class OrderController extends RestBaseController
{
    /**
     * 显示已点的订单的信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     *  获取头部的token即可
     */
    public function index(){
        $info = new changeUserController();
        $student = $info->index();
        $res = OrderModel::where(['cus_no'=>$student['no'],'finish_status'=>0])->with('types')->select()->toArray();
        dump($res);
//        return json($res,200);
    }

    /**
     * 在已点的订单中取消报餐
     * @return \think\response\Json
     *
     * 传入参数：
     *  id          订单的id排序
     *  order_time  订单的报餐时间
     *  在index中以获取，将数据绑定到按钮上传入到接口进行即可
     */
    public function canceled(){
        $id = $this->request->param('id');
        $T = strtotime($this->request->param('order_time'));
        if(!is_null($id)){
            $res = OrderModel::where(['id'=>$id])->
            where('allow_cancel_time','>',date('0'.':i:'.'0',time()-$T))->update(['finish_status'=>2]);
//            dump(date('00'.':i:'.'00',time()-$T));
//            dump($res);
            if ($res){
                return json(['code'=>200,'msg'=>'报餐已被取消']);
            }else{
                return json(['code'=>220,'msg'=>'超过取消报餐时间，无法取消']);
            }
        }else{
            return json(['code'=>2001,'msg'=>'无法定位到对应的数据，无法进行变更']);
        }
    }

    /**
     * 显示该用户所有的订单信息（2为已取消，0为报餐中，1为报餐完成）
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *  获取头部的token即可
     */
    public function all_list(){
        $info = new changeUserController();
        $student = $info->index();
        $res = OrderModel::where(['cus_no'=>$student['no']])->with('types')->select()->toArray();
        return json($res,200);
    }

    /**
     * 显示用户已取消的所有订单信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 获取头部的token即可
     */
    public function canceled_list(){
        $info = new changeUserController();
        $student = $info->index();
        $res = OrderModel::where(['cus_no'=>$student['no'],'finish_status'=>2])->with('types')->select()->toArray();
        return json($res,200);
    }

}