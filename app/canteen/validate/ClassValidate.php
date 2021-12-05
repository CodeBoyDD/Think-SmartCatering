<?php

namespace app\canteen\validate;

use think\Validate;

class ClassValidate extends Validate
{
    protected $rule = [
        'school_no'    => 'require',
        'class_no'     => 'require|max:11',
        'class'        => 'require|max:255',
        'class_number' =>'require|max:11',
    ];

    protected $message = [
        'school_no.require'    => '学校编号必须',

        'class_no.require'     => '学校编号必须',
        'class_no.max'         => '班级编号过长',

        'class.require'        => '班级编号必须',
        'class.max'            => '班级名称过长',

        'class_number.require' => '学生人数必须',
        'class_number.max'     => '学生人数位数过长',

    ];

    protected $scene = [
        'create' => ['school_no','class_no','class','class_number'],
        'edit'   => ['school_no','class_no','class','class_number'],
    ];
}