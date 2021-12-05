<?php

namespace app\canteen\controller;

use think\Db;
use app\canteen\model\ClassModel;
use app\canteen\model\SchoolModel;
use cmf\controller\AdminBaseController;


class ClassController extends AdminBaseController
{
    /*展示信息*/
    public function index()
    {
        $class = new ClassModel();

        $data = $class->classList()->paginate(10);
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

    /*创建班级*/
    public function create()
    {
        $class = new ClassModel();
        $school  = SchoolModel::select();
        $this->assign("school",$school);

        if (request()->isPost())
        {
            $data = input('param.');
            /*验证器*/
            $validate = $this->validate($data,'Class.create');
            if (!$validate == true) {
                $this->error($validate);
            } else {
                $class->insert($data);
                $this->success('创建班级成功', 'index');
            }
        }
        return $this->fetch();
    }

    /*删除班级*/
    public function delete()
    {
        if (request()->isPost()) {
            ClassModel::where('id', input('id'))->delete();
            return $this->success('删除数据成功！', 'index');
        }
        return $this->fetch();
    }

    /*班级编辑*/
    public function edit()
    {
        /*查询所需编辑数据*/
        $edit = ClassModel::where('id', input('id'))->find();

        $school  = SchoolModel::select();

        $this->assign("school",$school);
        $this->assign('data', $edit);

        if (request()->isPost())
        {
            $data = input('param.');
            /*验证器*/
            $validate = $this->validate($data,'Class.edit');
            if(!$validate == true) {
                return $this->error($validate);
            } else {
                $edit->update($data);
                return $this->success('编辑班级信息成功', 'index');
            }
        }
        return $this->fetch();
    }

    /*搜索班级*/
    public function search()
    {
        $class = new ClassModel();

        $class_no = input('class_no');
        $name     = input('class');

        $data = $class->classSearch($class_no,$name);
        //dump($data->select());

        if($data->select()->toArray() == null){
            return $this->error('查询不到数据','index');
        }

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        $data = $data->paginate(10);
        $page = $data->render();
        /*计算班级总数*/
        $sum     = $data->total();



        $this->assign('page',$page);
        $this->assign('data',$data);
        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);

        return $this->fetch();
    }

}
