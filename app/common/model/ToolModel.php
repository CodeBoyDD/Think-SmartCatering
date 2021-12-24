<?php

namespace app\common\model;

use think\Model;

class ToolModel extends Model
{
    //用户表[主表]绑定食堂表
    public function can(){
        return $this->hasOne(CanModel::class,'can_no','can_no')->joinType('full')
            ->bind(['customer','cus_no','meal_status','is_allow_status','is_allow_time']);
    }
}