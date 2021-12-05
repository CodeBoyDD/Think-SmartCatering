<?php
namespace app\canteen\model;

use think\Model;
use think\facade\Db;

class CanteenModel extends Model
{
    protected $pk    =  'id';
    protected $table =  'yfc_a_canteen';

    /*学校表Join食堂表 => 食堂部分相同字段覆盖学校字段*/
    public function schJoinCan()
    {
        $data = Db::table('yfc_a_school')
            ->alias('school')
            ->Join(['yfc_a_canteen'=>'canteen'],'canteen.school_no = school.school_no');
        return $data;
    }

    /*搜索食堂信息-->[编号,食堂]*/
    public function canSearch($can_no,$canteen)
    {
        /*编号模糊搜索*/
        $can_id = [];
        if ($can_no){
            $can_id = function ($query) use($can_no){
                $query->where('can_no','LIKE',"%$can_no%");
            };
        }

        /*标题模糊搜索*/
        $can_name = [];
        if ($canteen){
            $can_name = function ($query) use($canteen){
                $query->where('canteen','LIKE',"%$canteen%");
            };
        }

        /*查询关联信息*/
         $data = $this->schJoinCan()
            ->where($can_id)
            ->whereOr($can_name);
        return $data;
    }
}
