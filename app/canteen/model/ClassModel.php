<?php
namespace app\canteen\model;

use think\Model;
use think\facade\Db;

class ClassModel extends Model
{
    protected $pk    =  'id';
    protected $table =  'yfc_a_class';

    /*班级Join用户Join学校*/
    public function claJoinStuJoinSch()
    {
        return Db::table('yfc_a_class')
            ->alias('class')
            ->leftjoin(['yfc_a_school'=>'school'],'class.school_no = school.school_no')
            ->leftjoin(['yfc_a_customer'=>'customer'],'class.class_no = customer.class_no')
            ->field('*,class.id AS class_id,customer.id AS cus_id,class.class_no AS cla_no');
    }

    /*首页显示学校-班级-学生关联信息*/
    public function classList()
    {
         $data = $this->claJoinStuJoinSch()
            ->field('count(customer.class_no) AS bind_number')
            ->group('class.class_no');
        return $data;
    }

    /*模糊搜索班级信息*/
    public function classSearch($class_no,$class)
    {
         /*编号搜索*/
        $id = [];
        if ($class_no){
            $id = function ($query) use($class_no){
                $query->where('class.class_no','LIKE',"%$class_no%");
            };
        }

        /*标题模糊搜索*/
        $cname = [];
        if ($class){
            $cname = function ($query) use($class){
                $query->where('class','LIKE',"%$class%");
            };
        }

        $data = $this->classList()
            ->where($id)
            ->whereOr($cname);
        return $data;
    }

}
