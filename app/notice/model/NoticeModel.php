<?php

namespace app\notice\model;

use think\Model;
use think\facade\Db;

class NoticeModel extends Model
{
    protected $pk = 'id';
    public $table = 'yfc_a_notice';

/*=====================表操作=====================*/

    /*1.公告表左连接食堂表*/
    public function nLjCan()
    {
        $data = Db::table('yfc_a_notice')
            ->alias('notice')
            ->leftJoin(['yfc_a_canteen'=>'canteen'],'notice.can_no = canteen.can_no');
        return $data;
    }

/*====================功能实现====================*/

    /*查询食堂发布的公告信息*/
    public function noticeList()
    {
        $data = $this->nLjCan()
            ->field('*,notice.id AS n_id');
        return $data;
    }

    /*编号，标题，日期模糊搜索*/
    public function noticeSearch($n_no,$n_title,$n_date)
    {
        /*日期模糊搜索*/
        $wheredate = [];
        if ($n_date){
            $wheredate = function ($query) use($n_date){
                $query->where('n_date','LIKE',"%$n_date%");
            };
        }
        /*编号模糊搜索*/
        $no = [];
        if ($n_no){
            $no = function ($query) use($n_no){
                $query->where('n_no','LIKE',"%$n_no%");
            };
        }
        /*标题模糊搜索*/
        $title = [];
        if ($n_title){
            $title = function ($query) use($n_title){
                $query->where('n_title','LIKE',"%$n_title%");
            };
        }
        /*搜索SQL*/
        $data = $this->noticeList()
            ->where($no)
            ->whereOr($title)
            ->whereOr($wheredate);
        return $data;
    }
}
