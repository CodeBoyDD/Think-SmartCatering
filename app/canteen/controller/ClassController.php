<?php

namespace app\canteen\controller;

use think\Db;
use app\canteen\model\ClassModel;
use cmf\controller\AdminBaseController;


class ClassController extends AdminBaseController
{
    //展示信息
    public function index()
    {
        $class = new ClassModel();

        $data = $class->classList();
        $page = $data->render();

        $this->assign('data', $data);
        $this->assign('page', $page);

        return $this->fetch();
    }

    //创建班级
    public function create()
    {
        $class = new ClassModel();
        if (request()->isPost())
        {
            $data = input('param.');
            //dump($data);
            //验证器
            $validate = $this->validate($data,'Class.create');
            if (!$validate == true)
            {
                $this->error($validate);
            }
            else
            {
                $class->insert($data);
                $this->success('创建班级成功', 'index');
            }
        }
        return $this->fetch();
    }

    //删除班级
    public function delete()
    {
        if (request()->isPost()) {
            ClassModel::where('id', input('id'))->delete();
            return $this->success('删除数据成功！', 'index');
        }
        return $this->fetch();
    }

    //班级编辑
    public function edit()
    {
        $edit = ClassModel::where('id',input('id'))->find();

        $this->assign('data', $edit);

        if (request()->isPost())
        {
            $data = input('param.');

            //验证器
            $validate = $this->validate($data,'Class.edit');
            if(!$validate == true)
            {
                $this->error($validate);
            }
            else
            {
                $edit->update($data);
                $this->success('编辑班级信息成功', 'index');
            }
        }

        return $this->fetch();
    }

    //搜索班级
    public function search()
    {
        $class = new ClassModel();
        if (request()->isPost())
        {
            $class_id = input('class_id');
            $name     = input('name');

            $data = $class->classSearch($class_id,$name);
            dump($data->select());

            if($data->select()->toArray() == null){
                return $this->error('查询不到数据','index');
            }else{
                $data = $data->paginate(10);
                $page = $data->render();

                $this->assign('page',$page);
                $this->assign('data',$data);
            }
        }
        return $this->fetch();
    }

}
