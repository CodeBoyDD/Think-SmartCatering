<?php
namespace app\canteen\model;

use think\facade\Db;
use think\Model;
use app\canteen\model\classModel;

class CustomerModel extends Model
{
    protected $pk = 'id';
    protected $table = 'yfc_a_customer';

/*=======================表操作========================*/

    /*1.用户表为主表,左连接班级，左连接食堂，左连接学校表*/
    public function cusLjClaLjCanLjSch()
    {
        $data = Db::table('yfc_a_customer')
            ->alias('customer')
            ->leftJoin(['yfc_a_class'=>'class'],'customer.class_no=class.class_no')
            ->leftjoin(['yfc_a_canteen'=>'canteen'],'customer.can_no=canteen.can_no')
            ->leftJoin(['yfc_a_school'=>'school'],'class.school_no=school.school_no');
        return $data;
    }

    /*2.班级表为主表,左连接用户，左连接食堂，左连接学校表*/
    public function claLjCusLjSch()
    {
        $data = Db::table('yfc_a_class')
            ->alias('class')
            ->leftJoin(['yfc_a_customer'=>'customer'],'class.class_no=customer.class_no')
            ->leftjoin(['yfc_a_canteen'=>'canteen'],'customer.can_no=canteen.can_no')
            ->leftJoin(['yfc_a_school'=>'school'],'class.school_no=school.school_no');
        return $data;
    }

    /*3.班级表为主表,左连接用户表 -> 实现班级下绑定的用户*/
    public function claLjCus()
    {
        $data = Db::table('yfc_a_class')
            ->alias('class')
            ->leftJoin(['yfc_a_customer'=>'customer'],'class.class_no=customer.class_no');
        return $data;
    }

    /*4.用户表为主表,左连接班级表 —> 实现用户绑定班级信息,当用户不绑定班级*/
    public function cusLjcla()
    {
        $data = Db::table('yfc_a_customer')
            ->alias('customer')
            ->leftJoin(['yfc_a_class'=>'class'],'customer.class_no=class.class_no');
        return $data;
    }

    /*5.用户表为主表,右连接食堂表*/
    public function CusRjCanLjCla()
    {
        $data = Db::table('yfc_a_customer')
            ->alias('customer')
            ->rightJoin(['yfc_a_canteen'=>'canteen'],'customer.can_no = canteen.can_no')
            ->leftJoin(['yfc_a_class'=>'class'],'customer.class_no = class.class_no');
        //->field('canteen.canteen_id,count(student.canteen_id) AS canteen_total,canteen_name,canteen_status')
        return $data;
    }


/*======================功能实现=======================*/

    /*查询用户的所有信息*/
    public function customerList()
    {
        $data = $this->cusLjClaLjCanLjSch()
            /*保存覆盖掉的相同字段信息*/
            ->field('*,customer.id AS cus_id,class.id AS class_id');
        return $data;
    }

    /*查询指定ID用户的信息*/
    public function customerDetail($class_id)
    {
        $data = $this->customerList()
            ->where('customer.id',$class_id)
            ->find();
        return $data;
    }

    /*搜索用户信息及关联信息(用户编号，用户姓名)*/
    public function customerSearch($cus_no,$customer)
    {
        /*用户编号模糊搜索*/
        $number = [];
        if ($cus_no){
            $number = function ($query) use($cus_no){
                $query->where('cus_no','LIKE',"%$cus_no%");
            };
        }
        /*姓名模糊搜索*/
        $name = [];
        if ($customer){
            $name = function ($query) use($customer){
                $query->where('customer','LIKE',"%$customer%");
            };
        }
        /*搜索SQL*/
        $data = $this->customerList()
            ->where($number)
            ->whereOr($name);
        return $data;
    }

    /*各班级绑定用户统计*/
    public function classBindTotal()
    {
        /*先分组在统计*/
        $data = $this->claLjCus()
            ->field('*,class.id AS class_id,class.class_no AS cclass_no,count(customer.class_no) AS customer_total')
            ->group('class.class_no');
        return $data;
    }

    /*各班级绑定统计内->搜索班级(班级编号,班级名称)*/
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
        /*搜索*/
        $data = $this->classBindTotal()
            ->where($id)
            ->whereOr($cname);
        return $data;
    }

    /*指定ID班级的用户绑定详情*/
    public function classBindDetails($class_no)
    {
        $data = $this->claLjCusLjSch()
            ->field('*,customer.id AS cus_id,class.id AS class_id')
            ->where('customer.class_no',$class_no);
        return $data;
    }

    /*各食堂绑定统计*/
    public function canteenTotal()
    {
        $data = $this->CusRjCanLjCla()
            ->field('*,customer.id AS cus_id,count(customer.can_no) AS canteen_total')
            ->group('canteen.can_no');
        return $data;
    }

    /*各食堂绑定统计内->搜索食堂(食堂编号,食堂名称)*/
    public function canteenTotalSearch($can_no,$canteen)
    {
        /*编号搜索*/
        $no = [];
        if ($can_no){
            $no = function ($query) use($can_no){
                $query->where('canteen.can_no','LIKE',"%$can_no%");
            };
        }
        /*名称模糊搜索*/
        $cname = [];
        if ($canteen){
            $cname = function ($query) use($canteen){
                $query->where('canteen','LIKE',"%$canteen%");
            };
        }
        /*搜索*/
        $data = $this->canteenTotal()
            ->where($no)
            ->whereOr($cname);
        return $data;
    }

    //某食堂绑定详情 [学生<=>食堂]
    public function canteenBindDetail($can_no)
    {
        $data = $this->CusRjCanLjCla()
            ->where('canteen.can_no',$can_no)
            ->field('*,customer.id AS cus_id')
            ->paginate(10);
        return $data;
    }

/*====================================================*/
}


