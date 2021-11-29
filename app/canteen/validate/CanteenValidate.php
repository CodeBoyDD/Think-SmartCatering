<?php
namespace app\canteen\validate;

use think\Validate;

class CanteenValidate extends Validate
{
    //验证规则
    protected $rule = [
        'school_id'   => 'require|max:11',
        'can_id'      => 'require|max:8',
        'name'        => 'require|max:255',
        'address'     => 'require|max:255',
        'status'      => 'require',
    ];

    //验证信息
    protected $message  =   [

        'school_id.require' => '学校编号必须',
        'school_id.max'     => '学校编号最多为11位',

        'can_id.require'    => '食堂编号必须',
        'can_id.max'        => '食堂编号最多为8个位',

        'name.require'      => '食堂名称必须',
        'name.max'          => '食堂名称过长',

        'address.require'   => '食堂地址必须',
        'address.max'       => '食堂地址过长',

        'status.require'    => '食堂状态必须',
    ];

    protected $scene = [
        'create' => ['school_id','can_id','name','address','status'],
        'edit'   => ['school_id','can_id','name','address','status'],
    ];
}
