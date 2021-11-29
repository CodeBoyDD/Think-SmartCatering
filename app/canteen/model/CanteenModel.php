<?php
namespace app\canteen\model;

use think\Model;
use \think\db\Query;
use think\facade\Db;

class CanteenModel extends Model
{
    protected $pk    =  'id';
    protected $table =  'yfc_canteen';

    //食堂表Join学校表
    public function canJoinSch()
    {
        $data = Db::table('yfc_school')
            ->alias('sch')
            ->Join(['yfc_canteen'=>'can'],'can.school_id = sch.school_id');
        return $data;
    }

    //搜索食堂信息-->[id,名称]
    public function canSearch($can_id,$name)
    {
        // 编号搜索
        $canteen_id = [];
        if ($can_id){
            $canteen_id = function ($query) use($can_id){
                $query->where('can_id',$can_id);
            };
        }

        // 标题模糊搜索
        $canteen_name = [];
        if ($name){
            $canteen_name = function ($query) use($name){
                $query->where('name','LIKE',"%$name%");
            };
        }

        //查询关联信息
         $data = $this->canJoinSch()
            ->where($canteen_id)
            ->whereOr($canteen_name);
        return $data;
    }
}
