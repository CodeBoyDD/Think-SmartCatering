<?php

namespace app\baocan\model;

use think\Model;
use think\facade\Db;

class NoticeModel extends Model
{
    protected $pk = 'notice_id';
    public $table = 'cmf_baocan_notice';


    // 编号，标题，日期模糊搜索
    public function noticeSearch($notice_id,$notice_title,$notice_date)
    {
        // 日期模糊搜索
        $wheredate = [];
        if ($notice_date){
            $wheredate = function ($query) use($notice_date){
                $query->where('notice_date','LIKE',"%$notice_date%");
            };
        }

        // 编号搜索
        $noticeid = [];
        if ($notice_id){
            $noticeid = function ($query) use($notice_id){
                $query->where('notice_id',$notice_id);
            };
        }

        // 标题模糊搜索
        $noticetitle = [];
        if ($notice_title){
            $noticetitle = function ($query) use($notice_title){
                $query->where('notice_title','LIKE',"%$notice_title%");
            };
        }

        // 搜索SQL
        $noticeinfo = Db::table('cmf_baocan_notice')
            ->where($noticeid)
            ->whereOr($noticetitle)
            ->whereOr($wheredate)
            ->select();
        return $noticeinfo;
    }
}
