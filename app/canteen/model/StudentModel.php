<?php
namespace app\canteen\model;

use think\facade\Db;
use think\Model;

class StudentModel extends Model
{
    protected $pk = 'student_id';
    protected $table = 'cmf_baocan_student';

    //班级-学生-食堂关联信息 [学生<=>班级<=>食堂]
    public function studentList()
    {
        //左连接-->未绑定
        $data = Db::table('cmf_baocan_student')
            ->alias('student')
            ->leftJoin(['cmf_baocan_class'=>'class'],'student.class_id=class.class_id')
            ->leftjoin(['cmf_baocan_canteen'=>'canteen'],'student.canteen_id=canteen.canteen_id')
            ->paginate(10);
        return $data;
    }


    //编辑班级-学生-食堂管理信息 [学生<=>班级<=>食堂]
    public function editList($student_id)
    {
        $data = Db::table('cmf_baocan_student')
            ->alias('student')
            ->leftJoin(['cmf_baocan_class'=>'class'],'student.class_id=class.class_id')
            ->leftjoin(['cmf_baocan_canteen'=>'canteen'],'student.canteen_id=canteen.canteen_id')
            ->where('student_id',$student_id)
            ->find();
        return $data;
    }

    //各班级绑定统计 [学生<=>班级]
    public function classTotal()
    {
        //先分组在统计
        $data = Db::table('cmf_baocan_student')
            ->alias('student')
            ->rightJoin(['cmf_baocan_class'=>'class'],'student.class_id=class.class_id')
            ->field('class.class_id,count(student.class_id) AS student_total,class_name,class_number')
            ->group('class.class_id')
            ->paginate(10);
        return $data;
    }

    //各班级绑定统计内搜索班级(班级名称)
    public function classTotalSearch($class_name)
    {
        $data = null;
        return $data;
    }

    //某班级绑定详情 [学生<=>班级]
    public function classBindDetail($class_id)
    {
        $data = Db::table('cmf_baocan_student')
            ->alias('student')
            ->leftJoin(['cmf_baocan_class'=>'class'],'student.class_id = class.class_id')
            ->where('class.class_id',$class_id)
            ->paginate(10);

        return $data;
    }

    //某班级绑定详情内搜索学生
    public function classBindSearch($class_name)
    {
        $data = null;
        return $data;
    }

    //各食堂绑定统计 [学生<=>食堂]
    public function canteenTotal()
    {
        $data = Db::table('cmf_baocan_student')
            ->alias('student')
            ->rightJoin(['cmf_baocan_canteen'=>'canteen'],'student.canteen_id=canteen.canteen_id')
            ->field('canteen.canteen_id,count(student.canteen_id) AS canteen_total,canteen_name,canteen_status')
            ->group('canteen.canteen_id')
            ->paginate(10);
        return $data;
    }

    //某食堂绑定详情 [学生<=>食堂]
    public function canteenBindDetail($canteen_id)
    {
        $data = Db::table('cmf_baocan_student')
            ->alias('student')
            ->Join(['cmf_baocan_canteen'=>'canteen'],'student.canteen_id = canteen.canteen_id')
            ->Join(['cmf_baocan_class'=>'class'],'student.class_id = class.class_id')
            ->where('canteen.canteen_id',$canteen_id)
            ->paginate(10);

        return $data;
    }

    //搜索学生信息及关联信息(学号，姓名)
    public function stuSearch($student_number,$student_name)
    {
        // 学号模糊搜索
        $number = [];
        if ($student_number){
            $number = function ($query) use($student_number){
                $query->where('student_number','LIKE',"%$student_number%");
            };
        }

        // 姓名模糊搜索
        $name = [];
        if ($student_name){
            $name = function ($query) use($student_name){
                $query->where('student_name','LIKE',"%$student_name%");
            };
        }

        // 搜索SQL
        $data = Db::table('cmf_baocan_student')
            ->alias('student')
            ->leftJoin(['cmf_baocan_class'=>'class'],'student.class_id=class.class_id')
            ->leftjoin(['cmf_baocan_canteen'=>'canteen'],'student.canteen_id=canteen.canteen_id')
            ->where($number)
            ->whereOr($name)
            ->paginate(10);
        return $data;
    }



}
