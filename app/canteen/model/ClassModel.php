<?php
namespace app\canteen\model;

use think\Model;
use think\facade\Db;

class ClassModel extends Model
{
    protected $pk    =  'id';
    protected $table =  'yfc_class';

    //班级Join用户Join学校
    public function claJoinStuJoinSch()
    {
        $data = Db::table('yfc_class')
            ->alias('class')
            ->leftjoin(['yfc_school'=>'school'],'class.school_id = school.school_id')
            ->leftjoin(['yfc_customer'=>'customer'],'class.class_id = customer.class_id');
        return $data;
    }


    //首页显示学校-班级-学生关联信息
    public function classList()
    {
         $data = $this->claJoinStuJoinSch()
            ->field('class.id,class.school_id,school_name,class.class_id,class.name,number,count(customer.class_id) AS bind_number')
            ->group('class.class_id')
            ->paginate(10);
        return $data;
    }

    //模糊搜索班级信息
    public function classSearch($class_id,$name)
    {
        // 编号搜索
        $classid = [];
        if ($class_id){
            $classid = function ($query) use($class_id){
                $query->where('class.class_id',$class_id);
            };
        }

        // 标题模糊搜索
        $classname = [];
        if ($name){
            $classname = function ($query) use($name){
                $query->where('class.name','LIKE',"%$name%");
            };
        }

        $data = $this->claJoinStuJoinSch()
            ->where($classid)
            ->whereOr($classname);
        return $data;
    }


}
