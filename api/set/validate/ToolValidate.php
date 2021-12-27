<?php


namespace api\school\set\validate;


use think\Validate;

class ToolValidate extends Validate
{
    protected $rule=[
        'fee1'=>'require',
        'fee2'=>'require',
        'fee3'=>'require',
        'time1'=>'require|date',
        'time2'=>'require|date|gt:time1',
        'time3'=>'require|date',
        'time4'=>'require|date|gt:time3',
        'time5'=>'require|date',
        'time6'=>'require|date|gt:time5',
    ];

    protected $message=[
        'fee1.require'=>'请输入早餐的费用',
        'fee2.require'=>'请输入午餐的费用',
        'fee3.require'=>'请输入晚餐的费用',
        'time1.require'=>'请选择早餐起始时间',
        'time2.require'=>'请选择早餐终止时间',
        'time1.date'=>'早餐起始时间格式不正确',
        'time2.date'=>'早餐终止时间格式不正确',
        'time2.gt'=>'早餐终止时间必须大于起始时间',
        'time3.require'=>'请选择午餐起始时间',
        'time4.require'=>'请选择午餐终止时间',
        'time3.date'=>'午餐起始时间格式不正确',
        'time4.date'=>'午餐终止时间格式不正确',
        'time4.gt'=>'午餐终止时间必须大于起始时间',
        'time5.require'=>'请选择晚餐起始时间',
        'time6.require'=>'请选择晚餐终止时间',
        'time5.date'=>'晚餐起始时间格式不正确',
        'time6.date'=>'晚餐终止时间格式不正确',
        'time6.gt'=>'晚餐终止时间必须大于起始时间',
    ];

    protected $scene=[
        'getTimePriceStatus'=>['time1','time2'],
    ];

}