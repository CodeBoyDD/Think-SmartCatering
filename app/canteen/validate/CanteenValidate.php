<?php
namespace app\canteen\validate;

use think\Validate;

class CanteenValidate extends Validate
{
    //验证规则
    protected $rule = [
        'school_no'   => 'require',
        'can_no'      => 'require|max:8',
        'canteen'     => 'require|max:255',
        'address'     => 'require|max:255',
        'can_status'  => 'require',
    ];

    //验证信息
    protected $message  =   [

        'school_no.require'  => '学校编号必须',

        'can_no.require'     => '食堂编号必须',
        'can_no.max'         => '食堂编号最多为8个位',

        'canteen.require'    => '食堂名称必须',
        'canteen.max'        => '食堂名称过长',

        'address.require'    => '食堂地址必须',
        'address.max'        => '食堂地址过长',

        'can_status.require' => '食堂状态必须',
    ];

    protected $scene = [
        'create' => ['can_no','canteen','address','can_status'],
        'edit'   => ['school_no','can_no','canteen','address','can_status'],
    ];
}
