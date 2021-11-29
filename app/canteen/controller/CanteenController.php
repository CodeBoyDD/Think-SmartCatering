<?php

namespace app\canteen\controller;

use think\Db;
use \think\Request;
use \think\db\Query;
use app\canteen\model\CanteenModel;
use cmf\controller\AdminBaseController;
use app\canteen\validate\CanteenValidate;


class CanteenController extends AdminBaseController
{
    //展示食堂信息
    public function index()
    {
        $canInfo = new CanteenModel();

        $data    = $canInfo->canJoinSch()->paginate(10);
        $page    = $data->render();
        //dump($data);
        $this->assign('data',$data);
        $this->assign('page',$page);

        return $this->fetch();
    }

    //创建食堂
    public function create()
    {
        $canteen = new CanteenModel();

        if (request()->isPost())
        {
            $data = input('param.');

            //验证器
            $validate = $this->validate($data,'Canteen.create');

            if($validate !== true)
            {
                $this->error($validate);
            }
            else
            {
                //创建食堂
                $canteen->insert($data);
                $this->success('创建食堂成功', 'index');
            }
        }
        return $this->fetch();
    }

    //删除食堂
    public function delete()
    {
        if (request()->isPost())
        {
            CanteenModel::where('id', input('id'))->delete();
            return $this->success('删除数据成功', 'index');
        }
        return $this->fetch();
    }

    //编辑食堂
    public function edit()
    {
        $edit = CanteenModel::where('id', input('id'))->find();
        $this->assign('data', $edit);

        if (request()->isPost())
        {
            $data = input('param.');

            //验证器
            $validate = $this->validate($data,'Canteen.edit');
            if(!$validate == true)
            {
                $this->error($validate);
            }
            else
            {
                $edit->update($data);
                $this->success('编辑食堂信息成功', 'index');
            }
        }
        return $this->fetch();
    }

    //搜索食堂
    public function search()
    {
        $canteen = new CanteenModel();
        if (request()->isPost())
        {
            $data = $canteen
                ->canSearch(input('can_id'),input('name'));

            if ($data->select()->toArray() == null)
            {
                return $this->error('查询不到数据','index');
            }
            else
            {
                $data = $data->paginate(10);
                $page = $data->render();

                $this->assign('page',$page);
                $this->assign('data',$data);
            }
        }
        return $this->fetch();
    }

    //启用食堂
    public function enable()
    {
        $data = input('id');
        $status = CanteenModel::where('id',$data)->find();

        $status->status = 1;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改食堂状态成功！','index');
    }

    //禁用食堂
    public function disable()
    {
        $data = input('id');
        $status = CanteenModel::where('id', $data)->find();

        $status->status = 0;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改食堂状态成功！','index');
    }

}
