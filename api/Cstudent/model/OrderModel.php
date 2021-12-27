<?php


namespace api\Cstudent\model;


use think\Model;

class OrderModel extends Model
{
    protected $name = 'a_order';

    public function types(){
        return $this->hasOne(TypeModel::class, "type_no", "typenos")->bind(['type','fee']);
    }

}