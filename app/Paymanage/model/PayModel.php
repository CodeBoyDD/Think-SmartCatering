<?php


namespace app\Paymanage\model;

use app\common\CommonModel;
use think\Model;
use think\facade\Db;

class PayModel extends Model
{
    /**
     * @var string
     * 缴费功能：(未使用到 暂时未进行操作)
     * 涉及到缴费的话订单表中需要有一个判断订单情况的字段
     * 将该字段设置为某一个值，然后用这个值进行判断。若订单完成，
     * 在支付列表中将该订单的价格存入到支付列表中，然后与每日价格进行加法运算，并计入时间
     * 再使用该订单的完成时传入的该状态值中插入该订单的时间，与支付表中的时间进行比对，若不同天数
     * 将支付表格中的每日价格赋值给每月价格，并与之前的数值进行累加
     *
     * P.S.: 所以在yfc_a_order中添加了finish_status字段
     *          在yfc_a_pay中添加了date_fee和month_fee字段
     */

    protected $name = 'a_order';

    public function common(){
        return Db::table('yfc_a_paylist p,yfc_a_order or,
                                yfc_a_customer cus,yfc_a_class cl, 
                                yfc_a_school sch,yfc_a_canteen can,
                                yfc_a_type ty'
                        )
            ->where('cus.cus_no = or.cus_no')
            ->where('p.cus_no = cus.cus_no')
            ->where('or.type_no = ty.type_no')
            ->where('cus.class_no = cl.class_no')
            ->where('cl.school_no = sch.school_no')
            ->where('cus.can_no = can.can_no')
            ->where(['or.finish_status'=>1]);//表示已经完成的订单
    }

    public function culuedata(){
        $data = $this->common()->field('sch.school_name,can.canteen,cus.customer,or.order_no,ty.type,ty.fee')->select()->toArray();
        $tool = new CommonModel();
        return $tool->machiningData($data,'school_name','canteen');
    }

    public function detaildata(){
        return $this->common()->field('sch.school_name,can.canteen,
                                                can.address,cl.class,
                                                cus.customer,or.order_no,
                                                or.order_time,ty.type,ty.fee');
//                                    ->select()->toArray();
//        $tool = new CommonModel();
//        return $tool->machiningData($data,'school_name','canteen');
    }

    public function sumPaid($status){
        $data = $this->common()
            ->field('school_name,cl.class,cus.customer,p.pay_no,can.canteen,p.pay_time,sum(ty.fee) as sum')
            ->where(['p.pay_status'=>$status])
            ->where('or.order_time','<','p.pay_time')
            ->select()->toArray();
        $tool = new CommonModel();
        return $tool->machiningData($data,'school_name','class');
    }
}