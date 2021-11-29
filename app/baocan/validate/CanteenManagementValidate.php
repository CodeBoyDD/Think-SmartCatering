<?php
namespace app\baocan\validate;

use think\Validate;

class CanteenManagementValidate extends Validate
{
    //验证规则
    protected $rule = [
        'school_id'       => 'require',
        'canteen_name'    => 'require',
        'canteen_address' => 'require',
        'canteen_status'  => 'require',
    ];

    //验证信息
    protected $message  =   [
        'school_id.require'       => '学校编号必须',
        'canteen_name.require'    => '食堂名称必须',
        'canteen_address.require' => '食堂地址必须',
        'canteen_status.require'  => '食堂状态必须',
    ];
}
