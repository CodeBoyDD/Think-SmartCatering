<?php


namespace api\Cstudent\model;


use think\facade\Db;
use think\Model;

class InfoModel extends Model
{
    protected $name = 'a_customer';

    public function classes(){
        return $this->hasOne(ClassModel::class, "class_no", "class_no")->bind(['class']);
    }

    public function canteenes(){
        return $this->hasOne(CanModel::class, "can_no", "can_no")->joinType("FULL")->bind(['canteen']);
    }
}