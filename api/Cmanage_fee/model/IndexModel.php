<?php


namespace api\Cmanage_fee\model;


use api\Ccommon\model\ClassModel;
use api\Ccommon\model\CustomerModel;
use api\Ccommon\model\PaylistModel;
use think\facade\Db;
use think\Model;

class IndexModel extends Model
{
    protected $name = 'a_school';

    //1     通过用户获取所有食堂信息
    //2     通过食堂信息中的食堂编号    获取对应的   班级信息
    //3     通过班级信息的班级编号     获取对应的   用户信息
    //4     通过用户信息的用户编号     获取对应的   支付信息
    //5     通过支付信息的支付状态     获取对应的   缴费人数

    //缴费
    public function status($school_no ,$class = null){
        if (is_null($class)){
            return Db::table('yfc_a_customer cus,yfc_a_school sc,
                            yfc_a_class cl,yfc_a_paylist pl')
                ->where('sc.school_no = cl.school_no')
                ->where('cl.class_no = cus.class_no')
                ->where('cus.cus_no = pl.cus_no')
                ->where(['cus_status'=>1,'cus_type'=>1])
                ->where(['sc.school_no'=>$school_no]);
        }else{
            return Db::table('yfc_a_customer cus,
                            yfc_a_class cl,yfc_a_paylist pl')
                ->where('cus.class_no = cl.class_no')
                ->where('cus.cus_no = pl.cus_no')
                ->where(['cus_status'=>1,'cus_type'=>1])
                ->where(['cl.class_no'=>$class]);
        }
    }

    //所有班级缴费统计列表
    public function get_status($school_no){
        return $this->status($school_no)
            ->field('cl.class,count(pl.cus_no) as paid,cl.class_number - count(pl.cus_no) as onpay')
            ->where(['pay_status'=>1])
            ->group('cl.class')->select()->toArray();
    }

    //选择是否已缴费的列表
    public function getPaylist($school_no,$status,$class){
        return $this->status($school_no,$class)
                ->field('customer,date_fee_count')
                ->where(['pay_status'=>$status])
                ->select()->toArray();

    }

    //报餐
    public function status1($school_no ,$class = null){
        if (is_null($class)){
            return Db::table('yfc_a_customer cus,yfc_a_school sc,
                            yfc_a_class cl,yfc_a_order o')
                ->where('sc.school_no = cl.school_no')
                ->where('cl.class_no = cus.class_no')
                ->where('cus.cus_no = o.cus_no')
                ->where(['cus_status'=>1,'cus_type'=>1])
                ->where(['sc.school_no'=>$school_no]);
        }else{
            return Db::table('yfc_a_customer cus,
                            yfc_a_class cl,yfc_a_order o,
                                yfc_a_type t')
                ->where('cl.class_no = cus.class_no')
                ->where('cus.cus_no = o.cus_no')
                ->where('o.type_no = t.type_no')
                ->where(['cus_status'=>1,'cus_type'=>1])
                ->where(['cus.class_no'=>$class]);
        }
    }

    //所有班级报餐统计列表
    public function get_status1($school_no){
        return $this->status1($school_no)
            ->field('cl.id,class,count(distinct(o.cus_no)) ing,count(o.cancel_time) ed
                    ,class_number-count(distinct(o.cus_no)) adj')
            ->where(['o.finish_status'=>1])
            ->group('cl.class')->select()->toArray();
    }

    //选择是否已报餐的列表
    public function getOrderlist($school_no,$status,$class){
        return $this->status1($school_no,$class)
            ->field('cus.customer,t.type')
            ->where(['o.finish_status'=>$status])
            ->select()->toArray();
    }

}