<?php

namespace app\baocan\model;

use think\facade\Db;
use think\Model;

class OpinionModel extends Model
{
    protected $pk = 'opinion_id';
    protected $table = 'cmf_baocan_opinion';

    // 编号，标题，日期模糊搜索
    public function opinionSearch($opinion_id,$opinion_title,$opinion_date)
    {
        // 日期模糊搜索
        $wheredate = [];
        if ($opinion_date){
            $wheredate = function ($query) use($opinion_date){
                $query->where('opinion_date','LIKE',"%$opinion_date%");
            };
        }

        // 编号搜索
        $opinionid = [];
        if ($opinion_id){
            $opinionid = function ($query) use($opinion_id){
                $query->where('opinion_id',$opinion_id);
            };
        }

        // 标题模糊搜索
        $opiniontitle = [];
        if ($opinion_title){
            $opiniontitle = function ($query) use($opinion_title){
                $query->where('opinion_title','LIKE',"%$opinion_title%");
            };
        }

        // 搜索SQL
        $opinioninfo = Db::table('cmf_baocan_opinion')
            ->where($opinionid)
            ->whereOr($opiniontitle)
            ->whereOr($wheredate)
            ->select();

        return $opinioninfo;
    }

}

