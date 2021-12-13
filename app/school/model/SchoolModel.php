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

}