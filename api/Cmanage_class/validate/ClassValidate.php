<?php


namespace api\Cmanage_class\validate;


use think\Validate;

class ClassValidate extends Validate
{
    protected $rule = [
        'name'=>'require|chs',
        'class'=>'require',
    ];

    protected $message = [
        'name.require'=>'名字必须填写',
        'name.chs'=>'非法字符，只能用中文',
        'class.require'=>'班级必须填写'
    ];

    protected $scene = [
        'add'=>['name','class'],
    ];
}