<?php
namespace app\school\validate;

use think\Validate;

class SchoolValidate extends Validate
{
    //验证规则
    protected $rule = [
        'school_no'     => 'require|max:11',
        'school_name'   => 'require',
    ];

    //验证信息
    protected $message  =   [
        'school_no.require'  => '学校编号必须',
        'school_no.max'         => '学校编号最多为11位',
        'school_name.require'  => '学校名称必须',
    ];

    protected $scene = [
        'add'    => ['school_no','school_name'],
        'edit'   => ['school_no','school_name'],
    ];
}
