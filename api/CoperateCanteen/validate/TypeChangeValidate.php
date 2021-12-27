<?php


namespace api\CoperateCanteen\validate;


use think\Validate;

class TypeChangeValidate extends Validate
{
    protected $rule = [
        //添加中使用
        'type_no'   => 'require',
        'type'      => 'require',
        'fee'       => 'require',
        'start_time'=> 'require|date',
        'over_time' => 'require|date|gt:start_time',


        //修改中使用
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

    protected $message = [
        //添加
        'type_no.require'=>'类型参数缺失',
        'type.require'=>'类型名字缺失',
        'fee.string'=>'费用缺失',
        'start_time.require'=>'类型开始时间缺失',
        'over_time.require' =>'类型结束时间缺失',
        'over_time.gt'=>'时间差值为负，时间设置不正确',

        //修改
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

    protected $scene = [
        'add'=>['type_no','type','fee','start_time','over_time'],
        'update'=>['fee1','fee2','fee3','time1','time2','time3','time4','time5','time6'],
    ];
}