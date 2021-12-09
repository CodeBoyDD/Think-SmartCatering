<?php
namespace app\canteen\controller;

use app\canteen\model\ClassModel;
use \think\Request;
use cmf\controller\AdminBaseController;
use app\canteen\model\CustomerModel;

class CustomerController extends AdminBaseController
{
    /*首页*/
    public function index()
    {
        $customer= new CustomerModel();

        $data = $customer->customerList()->paginate(10);
        //dump($data);
        $page = $data->render();

        /*计算用户总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        $this->assign('data', $data);
        $this->assign('page', $page);
        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);

        return $this->fetch();

    }

    /*启用用户*/
    public function enable()
    {
        $status = CustomerModel::where('id', input('id'))->find();

        $status->cus_status = 1;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改状态成功！');
    }

    /*禁用用户*/
    public function disable()
    {
        $status = CustomerModel::where('id', input('id'))->find();

        $status->cus_status = 0;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改状态成功！');
    }

    /*用户详情*/
    public function cusDetail()
    {
        $customer = new CustomerModel();
        $data = $customer->customerDetail(input('id'));
        //dump($data);
        $this->assign('data', $data);

        return $this->fetch();
    }

    /*编辑用户 -> 修改用户班级*/
    public function cusEdit()
    {
        /*修改用户所属班级*/
        $edit = CustomerModel::where('id',input('id'))->find();
        $class = ClassModel::select();
        $this->assign('class',$class);
        $this->assign('data',$edit);

        if(request()->isPost()) {
            $edit->class_no = input('class_no');
            $edit->save();
            $this->success('修改学生信息成功！','index');
        }
        return $this->fetch();
    }

    /*搜索用户*/
    public function cusSearch()
    {
        $customer = new CustomerModel();
        $data = $customer->customerSearch(input('cus_no'),input('customer'))->paginate(10);;
        dump($data);

        $page = $data->render();

        /*计算搜索用户总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        $this->assign('data', $data);
        $this->assign('page', $page);
        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);
        return $this->fetch();
    }

    /*删除用户*/
    public function cusDelete()
    {
        CustomerModel::where('id', input('id'))->delete();
        $this->success('删除成功！');
        return $this->fetch();
    }

    /*各班级绑定统计*/
    public function classBindCount()
    {
        $class = new CustomerModel();
        $data = $class->classBindTotal()->paginate(10);
        $page = $data->render();

        /*计算班级总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        //dump($data);
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('pagenum',$pagenum);
        $this->assign('sum',$sum);

        return $this->fetch();
    }

    /*各班级绑定统计内班级 -> 班级搜索*/
    public function classBindSearch()
    {
        $class = new CustomerModel();
        $data = $class->classTotalSearch(input('class_no'),input('class'))->paginate(10);
        dump($data);

        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    /*某班级绑定用户详情*/
    public function classBindDetail()
    {
        $class = new CustomerModel();

        /*列举出该班级内绑定用户*/
        $data = $class->classBindDetails(input('class_no'))->paginate(10);

        $page = $data->render();
        /*计算班级总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        $this->assign('data', $data);
        $this->assign('page', $page);
        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);

        return $this->fetch();
    }

    /*各食堂绑定用户统计*/
    public function canBindCount()
    {
        $canteen = new CustomerModel();
        $data = $canteen->canteenTotal()->paginate(10);

        //dump($data);

        $page = $data->render();
        /*计算搜索用户总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);
        return $this->fetch();
    }

    /*各食堂绑定用户统计内搜索 -> 食堂搜索*/
    public function canBindSearch()
    {
        $class = new CustomerModel();
        $data = $class->canteenTotalSearch(input('can_no'),input('canteen'))->paginate(10);
        //dump($data);

        $page = $data->render();
        /*计算搜索用户总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    /*某食堂绑定用户详情*/
    public function canBindDetail()
    {
        $canteen = new CustomerModel();
        $data  = $canteen->canteenBindDetail(input('can_no'));

        dump($data);

        /*计算搜索用户总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);

        return $this->fetch();
    }

}
