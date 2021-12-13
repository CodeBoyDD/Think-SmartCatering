<?php


namespace app\Order\model;

use think\Model;
use think\facade\Db;

class OrderModel extends Model
{
//    protected $name = 'a_order';

    public function samepart(){
        return  Db::table('yfc_a_customer cs , yfc_a_order or ,
                                  yfc_a_school sl , yfc_a_class cl ,
                                   yfc_a_canteen ct , yfc_a_type ty ')
                ->field('cs.customer , or.order_no , or.order_time , or.cancel_time ,
                              cl.class , ct.canteen , sl.school_name as school
                              , ty.type')
                ->where('cs.cus_no = or.cus_no')
                ->where('cl.class_no = cs.class_no')
                ->where('ct.can_no = cs.can_no')
                ->where('sl.school_no = cl.school_no')
                ->where('or.type_no = ty.type_no');
    }


}