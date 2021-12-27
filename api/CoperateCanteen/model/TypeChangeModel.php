<?php


namespace api\CoperateCanteen\model;


use think\facade\Db;
use think\Model;

class TypeChangeModel extends Model
{
    protected $name = 'a_setting';
    //添加到type表里
    public function setType($can_no,$param){
        return Db::table('yfc_a_type')
            ->insert(['can_no'=>$can_no,
                'type_no'=>$param['type_no'],
                'type'=>$param['type'],
                'fee'=>$param['fee']
            ]);
    }

    //添加到setting表里
    public function setSetting($can_no,$param){
        return $this->insert(['can_no'=>$can_no,
                'type_no'=>$param['type_no'],
                'start_time'=>$param['start_time'],
                'over_time'=>$param['over_time'],
                'job_status'=>1
            ]);
    }

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