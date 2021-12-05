<?php
namespace app\canteen\model;

use think\facade\Db;
use think\Model;
use app\canteen\model\classModel;

class CustomerModel extends Model
{
    protected $pk = 'id';
    protected $table = 'yfc_a_customer';

    //用户-班级-食堂关联信息 [用户<=>班级<=>食堂] => Join school表
    public function customerList()
    {
        //左连接-->未绑定
        $data = Db::table('yfc_a_customer')
            ->alias('customer')
            ->leftJoin(['yfc_a_class'=>'class'],'customer.class_no=class.class_no')
            ->leftjoin(['yfc_a_canteen'=>'canteen'],'customer.can_no=canteen.can_no')
            ->leftJoin(['yfc_a_school'=>'school'],'class.school_no=school.school_no')
            ->field('*,customer.id AS cus_id,class.id AS class_id');
        return $data;
    }

    /*班级表leftjoin用户表*/
    public function claLjoinCus()
    {
        $data = Db::table('yfc_a_class')
            ->alias('class')
            ->leftJoin(['yfc_a_customer'=>'customer'],'class.class_no=customer.class_no');
        return $data;
    }

    /*用户表leftjoin班级表*/
    public function cusLjoincla()
    {
        $data = Db::table('yfc_a_customer')
            ->alias('customer')
            ->leftJoin(['yfc_a_class'=>'class'],'customer.class_no=class.class_no')
            ->field('*,customer.id AS cus_id,class.id AS class_id');
        return $data;
    }

    /*查询所需编辑用户的信息*/
    public function editList($id)
    {
        $data = $this->customerList()
            ->where('customer.id',$id)
            ->find();
        return $data;
    }

    //各班级绑定统计 [学生<=>班级]
    public function classBindTotal()
    {
        //先分组在统计
        $data = $this->claLjoinCus()
            ->field('*,class.id AS class_id,class.class_no AS cclass_no,count(customer.class_no) AS customer_total')
            ->group('class.class_no');
        return $data;
    }

    //各班级绑定统计内-搜索班级(班级编号,班级名称)
    public function classTotalSearch($class_no,$class)
    {
        /*编号搜索*/
        $id = [];
        if ($class_no){
            $id = function ($query) use($class_no){
                $query->where('class.class_no','LIKE',"%$class_no%");
            };
        }

        /*名称模糊搜索*/
        $cname = [];
        if ($class){
            $cname = function ($query) use($class){
                $query->where('class','LIKE',"%$class%");
            };
        }


        $data = $this->classBindTotal()
            ->where($id)
            ->whereOr($cname);
        return $data;
    }

    //某班级用户绑定详情
    public function classBindDetails($class_no)
    {
        $data = $this->cusLjoincla()
            ->where('customer.class_no',$class_no);
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

    /*搜索用户信息及关联信息(用户编号，用户姓名)*/
    public function customerSearch($cus_no,$customer)
    {
        // 用户编号模糊搜索
        $number = [];
        if ($cus_no){
            $number = function ($query) use($cus_no){
                $query->where('cus_no','LIKE',"%$cus_no%");
            };
        }

        // 姓名模糊搜索
        $name = [];
        if ($customer){
            $name = function ($query) use($customer){
                $query->where('customer','LIKE',"%$customer%");
            };
        }

        // 搜索SQL
        $data = $this->customerList()
            ->where($number)
            ->whereOr($name)
            ->paginate(10);
        return $data;
    }

}
