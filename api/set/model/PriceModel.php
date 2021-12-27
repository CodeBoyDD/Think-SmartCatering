<?php


namespace api\school\set\model;


use think\facade\Db;
use think\Model;

class PriceModel extends Model
{
    protected $name = 'a_setting';
    //修改价格
    public  function changePrice($type,$fee){
        return Db::table('yfc_a_type')->where('type_no',$type)
            ->update(['fee'=>$fee]);
    }


    //修改时间
    public function changeTime($type,$start,$end){
        return $this->where('type_no',$type)
            ->update(['start_time'=>$start,'over_time'=>$end]);
    }

    //修改允许取消报餐状态
    public function changeAllCanSta($canteen,$status){
        return $this->where('can_no',$canteen)
            ->update(['cancel_status'=>$status]);
    }


}