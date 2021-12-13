<?php


namespace app\Setting\model;


use app\common\CommonModel;
use think\facade\Db;
use think\Model;

class SetModel extends Model
{
    protected $name = 'a_setting';

    public function canteenDetail(){
        $data = Db::table('yfc_a_setting s,yfc_a_type t,yfc_a_canteen c,yfc_a_school sc')
            ->field('s.job_status,s.start_time,s.over_time,
                            t.type,t.fee,
                             c.canteen,
                             sc.school_name')
            ->where('t.can_no = c.can_no')
            ->where('s.type_no = t.type_no')
            ->where('c.school_no = sc.school_no')
            ->where(['c.can_status'=>1])
            ->select()->toArray();

        $tool = new CommonModel();
        return $tool->machiningData($data,'school_name','canteen');

        //数据分类
//        $item=array();
//        foreach($data as $k=>$v){
//            if(!isset($item[$v['school_name']])){
//                if (!isset($item[$v['canteen']])){
//                    $item[$v['school_name']][$v['canteen']][]=$v;
//                }else{
//                    $item[$v['school_name']][$v['canteen']][]=$v;
//                }
//
//            }else{
//                if (!isset($item[$v['canteen']])){
//                    $item[$v['school_name']][$v['canteen']][]=$v;
//                }else{
//                    $item[$v['school_name']][$v['canteen']][]=$v;
//                }
//            }
//        }
//        return $item;
    }

    public function cusDetail(){
        $data = Db::table('yfc_a_customer cus,yfc_a_canteen can,yfc_a_class cl,yfc_a_school sc')
            ->field('cus.customer,cus.cus_no,cus.meal_status,cus.is_allow_status,cus.is_allow_time,
                            cl.class,can.canteen,sc.school_name')
            ->where('cus.class_no = cl.class_no')
            ->where('cus.can_no=can.can_no')
            ->where(['cus.cus_type'=>1,
                    'can.can_status'=>1,
                    'cus.cus_status'=>1])
            ->select()->toArray();
        //数据分类
        $tool = new CommonModel();
        return $tool->machiningData($data,'school_name','class');
//        $item=array();
//        foreach($data as $k=>$v){
//            if(!isset($item[$v['school_name']])){
//                if (!isset($item[$v['class']])){
//                    $item[$v['school_name']][$v['class']][]=$v;
//                }else{
//                    $item[$v['school_name']][$v['class']][]=$v;
//                }
//
//            }else{
//                if (!isset($item[$v['class']])){
//                    $item[$v['school_name']][$v['canteen']][]=$v;
//                }else{
//                    $item[$v['school_name']][$v['class']][]=$v;
//                }
//            }
//        }
//        return $item;
    }
    //供应
    public function getChang($can_no,$school_no){
        return Db::table('yfc_a_setting s,yfc_a_type t,yfc_a_canteen c')
            ->field('s.*,t.type,c.canteen')
            ->where('t.can_no = c.can_no')
            ->where('s.type_no = t.type_no')
            ->where(['c.school_no'=>$school_no,
                     'c.can_no'=>$can_no])
            ->select()->toArray();
    }
    //费用
    public function feelist($school_no){
        return Db::table('yfc_a_setting s,yfc_a_type t,yfc_a_canteen c')
            ->field('s.job_status,t.*,c.canteen')
            ->where('t.can_no = c.can_no')
            ->where('s.type_no = t.type_no')
            ->where('c.school_no',$school_no)
            ->where('s.job_status','=',1)
            ->select()->toArray();
    }
    //时间
    public function timelist($school_no){
        return Db::table('yfc_a_setting s,yfc_a_type t,yfc_a_canteen c')
            ->field('s.*,t.type,c.canteen')
            ->where('t.can_no = c.can_no')
            ->where('s.type_no = t.type_no')
            ->where('c.school_no',$school_no)
            ->where(['s.job_status'=>1])
            ->select()->toArray();
    }
    //用户权限
    public function cuslist($school_no){
        return Db::table('yfc_a_customer cus,yfc_a_canteen can,yfc_a_class cl')
            ->field('cus.id,cus.customer,cl.class,cus.is_allow_status,cus.is_allow_time')
            ->where('cus.class_no = cl.class_no')
            ->where('cus.can_no=can.can_no')
            ->where('can.school_no',$school_no)
            ->where(['cus.cus_type'=>1,
                     'can.can_status'=>1,
                     'cus.cus_status'=>1])
            ->select()->toArray();
    }

}