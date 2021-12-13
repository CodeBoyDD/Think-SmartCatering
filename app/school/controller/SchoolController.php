<?php

namespace app\school\controller;

use app\school\model\SchoolModel;
use cmf\controller\AdminBaseController;
use app\school\validate\SchoolValidate;

class SchoolController extends AdminBaseController
{
    /*显示学校信息*/
    public function index()
    {
        $data = SchoolModel::paginate(10);
        /*计算食堂总数*/
        $sum     = $data->total();

        /*分页*/
        $page    = $data->render();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        /*渲染模板*/
        $this->assign('sum',$sum);
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('pagenum',$pagenum);

        return $this->fetch();
    }

    /*录入学校*/
    public function add()
    {
        $school = new SchoolModel();

        if (request()->isPost())
        {
            $data = input('param.');
            /*验证器*/
            $validate = $this->validate($data,'School.add');
            if($validate !== true) {
                return $this->error($validate);
            } else {
                /*创建食堂*/
                $school->insert($data);
                $this->success('创建食堂成功','index');
            }
        }
        return $this->fetch();
    }

    /*删除学校信息*/
    public function delete()
    {
        if (request()->isPost()){
            SchoolModel::where('id', input('id'))->delete();
            $this->success('删除学校成功');
        }
        return $this->fetch();
    }

    /*修改学校信息*/
    public function edit()
    {
        /*查询所需编辑数据*/
        $edit = SchoolModel::where('id', input('id'))->find();
        $this->assign('data', $edit);

        if (request()->isPost())
        {
            $data = input('param.');
            /*验证器*/
            $validate = $this->validate($data,'School.edit');
            if(!$validate == true) {
                $this->error($validate);
            } else {
                $edit->update($data);
                $this->success('编辑学校信息成功', 'index');
            }
        }
        return $this->fetch();
    }

     /*搜索学校信息*/
    public function search()
    {
        $school = new SchoolModel();
        if (request()->isPost())
        {
            $no = input('school_no');
            $name = input('school_name');
            $data = $school->schoolSearch($no,$name);

            if ($data->select()->toArray() == null) {
                return $this->error('查询不到数据','search');
            }

            /*根据分页数+$key+1实现递增序号*/
            $pagenum = input('page');
            if($pagenum  <= 1) {$pagenum = 1;}
            $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

            $data = $data->paginate(10);
            $sum  = $data->total();
            $page = $data->render();

            $this->assign('sum',$sum);
            $this->assign('pagenum',$pagenum);
            $this->assign('page',$page);
            $this->assign('data',$data);
        }
        return $this->fetch();
    }
}