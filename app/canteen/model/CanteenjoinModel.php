<?php

namespace app\canteen\model;

use think\Model;
use \think\db\Query;
use think\facade\Db;

class CanteenjoinModel extends Model
{
    protected $pk    =  'apply_id';
    protected $table =  'cmf_baocan_canteenjoin';

    public function aSearch($apply_id, $apply_name, $apply_date)
    {
        // 编号搜索
        $applyid = [];
        if ($apply_id){
            $applyid = function ($query) use($apply_id){
                $query->where('apply_id',$apply_id);
            };
        }

        // 标题模糊搜索
        $applyname = [];
        if ($apply_name){
            $applyname = function ($query) use($apply_name){
                $query->where('apply_name','LIKE',"%$apply_name%");
            };
        }

        //时间搜索
        $applydate = [];
        if ($apply_date){
            $applydate = function ($query) use($apply_date){
                $query->where('apply_date','LIKE',"%$apply_date%");
            };
        }

        // 搜索SQL
        $data = Db::table('cmf_baocan_canteenjoin')
            ->where($applyid)
            ->whereOr($applyname)
            ->whereOr($applydate);
        return $data;

    }
}