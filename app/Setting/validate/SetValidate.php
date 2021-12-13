<?php


namespace app\Setting\validate;


use think\Validate;

class SetValidate extends Validate
{
    protected $rule = [
        'start_time'=>'require|date',
        'over_time'=>'require|date|gt:start_time',
    ];
    protected $message = [
        'start_time.require'=>'必填选项——开始时间',
        'over_time.require'=>'必填选项——结束时间',
        'start_time.date'=>'非时间格式字段，请重新输入',
        'over_time.date'=>'非时间格式字段，请重新输入',
        'over_time.gt:start_time'=>'结束时间不能小于开始时间',

    ];
    protected $scene = [
        'time'=>['start_time','over_time'],
    ];
}