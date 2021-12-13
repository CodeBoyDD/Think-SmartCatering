<?php


namespace app\Order\validate;

use think\Validate;

class OrderValidate extends Validate
{
    protected $rule = [
        'time1'=>'require|date',
        'time2'=>'require|date|gt:time1'
    ];

    protected $message = [
        'time1.require'=>'请选择起始时间',
        'time2.require'=>'请选择终止时间',
        'time1.date'=>'起始时间格式不正确',
        'time2.date'=>'终止时间格式不正确',
        'time2.gt'=>'终止时间必须大于起始时间',
    ];

    protected $scene = [
        'select'=>['time1','time2'],
    ];
}