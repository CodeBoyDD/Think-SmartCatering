<?php
namespace app\canteen\controller;

use \think\Request;
use cmf\controller\AdminBaseController;
use app\canteen\model\StudentModel;

class CustomerController extends AdminBaseController
{
    //首页
    public function index()
    {
        $studentinfo = new StudentModel();

        $data = $studentinfo->studentList();
        $page = $data->render();

        //dump($data);
        $this->assign('data', $data);
        $this->assign('page', $page);

        return $this->fetch();

    }

    //启用用户
    public function enable()
    {
        $status = StudentModel::where('student_id', input('student_id'))->find();

        $status->student_status = 1;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改状态成功！');
    }

    //禁用用户
    public function disable()
    {
        $status = StudentModel::where('student_id', input('student_id'))->find();

        $status->student_status = 0;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改状态成功！');
    }

    //用户详情
    public function studentDetail()
    {
        $studentinfo = new StudentModel();
        $data = $studentinfo->editList(input('student_id'));
        //dump($data);
        $this->assign('data', $data);

        return $this->fetch();
    }

    //编辑用户
    public function studentEdit()
    {
        $studentinfo = new StudentModel();
        $editdata = $studentinfo->editList(input('student_id'));
        //dump($editdata);
        $this->assign('data', $editdata);

            //修改用户所属班级
            $edit = StudentModel::where('student_id',input('student_id'))->find();
            if($this->request->isPost()) {
                $edit->class_id = input('class_id');

                $edit->save();

                $this->success('修改学生信息成功！','index');
            }
        return $this->fetch();
    }

    //搜索用户
    public function studentSearch()
    {
        $stuSearch = new StudentModel();
        $data = $stuSearch->stuSearch(input('student_number'),input('student_name'));

        $page = $data->render();
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }

    //删除用户
    public function studentDelete()
    {
        StudentModel::where('student_id', input('student_id'))->delete();
        $this->success('删除成功！','index');
        return $this->fetch();
    }

    //各班级绑定统计
    public function classBind()
    {
        $class = new StudentModel();
        $data = $class->classTotal();
        $page = $data->render();
        //dump($data);
        $this->assign('data',$data);
        $this->assign('page',$page);

        return $this->fetch();
    }

    //各班级绑定统计内班级 -> 班级搜索
    public function classBindSearch()
    {

    }

    //某班级绑定详情
    public function classBindDetail()
    {
        $class = new StudentModel();
        $data  = $class->classBindDetail(input('class_id'));

        //dump($data);

        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);

        return $this->fetch();
    }

    //某班级绑定详情 -> 该班级内用户搜索
    public function classBindDetailSearch()
    {

    }

    //各食堂绑定用户统计
    public function canteenBind()
    {
        $canteen = new StudentModel();
        $data = $canteen->canteenTotal();
        $page = $data->render();
        //dump($data);

        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    //各食堂绑定用户统计内搜索 -> 食堂搜索
    public function canteenBindSearch()
    {

    }

    //某食堂绑定用户详情
    public function canteenBindDetail()
    {
        $canteen = new StudentModel();
        $data  = $canteen->canteenBindDetail(input('canteen_id'));

        dump($data);

        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);

        return $this->fetch();
    }

    //某食堂绑定用户详情内搜索 -> 该食堂内用户搜索
    public function canteenBindDetailSearch()
    {

    }

}
