<?php

namespace app\school\model;

use think\facade\Db;
use think\Model;

class SchoolModel extends Model
{
    protected $pk = 'school_id';
    protected $table = 'yfc_a_school';

    /*搜索学校信息*/
    public function schoolSearch($school_no,$school_name)
    {
        $no =[];
        if ($school_no){
            $no = function ($query) use($school_no){
                $query->where('school_no','LIKE',"%$school_no%");
            };
        }

        $name = [];
        if ($school_name){
            $name = function ($query) use($school_name){
                $query->where('school_name','LIKE',"%$school_name%");
            };
        }

        $data = Db::table('yfc_a_school')
            ->alias('school')
            ->where($no)
            ->where($name);
        return $data;
    }

    //
    public function backtype($school_no){
        return Db::table('yfc_a_setting s,yfc_a_type t,yfc_a_canteen c')
            ->field('s.*,t.type,c.canteen')
            ->where('t.can_no = c.can_no')
            ->where('s.type_no = t.type_no')
            ->where('c.school_no',$school_no)
            ->select()->toArray();
    }

}