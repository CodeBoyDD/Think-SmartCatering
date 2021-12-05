<?php

namespace app\canteen\controller;

use think\Db;
use \think\Request;
use \think\db\Query;
use app\canteen\model\CanteenModel;
use app\canteen\model\SchoolModel;
use cmf\controller\AdminBaseController;
use app\canteen\validate\CanteenValidate;

class CanteenController extends AdminBaseController
{
    /*展示食堂信息*/
    public function index()
    {
        /*数据库查询*/
        $canteen = new CanteenModel();
        $data    = $canteen->schJoinCan()->paginate(10);

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

    /*创建食堂*/
    public function create()
    {
        $canteen = new CanteenModel();
        $school  = SchoolModel::select();
        $this->assign("school",$school);

        if (request()->isPost())
        {
            $data = input('param.');
            /*验证器*/
            $validate = $this->validate($data,'Canteen.create');
            if($validate != true) {
                return $this->error($validate);
            } else {
                /*创建食堂*/
                $canteen->insert($data);
                $this->success('创建食堂成功','index');
            }
        }
        return $this->fetch();
    }

    /*删除食堂*/
    public function delete()
    {
        if (request()->isPost())
        {
            CanteenModel::where('id', input('id'))->delete();
            return $this->success('删除数据成功', 'index');
        }
        return $this->fetch();
    }

    /*编辑食堂*/
    public function edit()
    {
        /*查询所需编辑数据*/
        $edit = CanteenModel::where('id', input('id'))->find();

        $school  = SchoolModel::select();

        $this->assign("school",$school);
        $this->assign('data', $edit);

        if (request()->isPost())
        {
            $data = input('param.');
            /*验证器*/
            $validate = $this->validate($data,'Canteen.edit');
            if(!$validate == true) {
                $this->error($validate);
            } else {

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

        $can_no  = input('can_no');
        $name    = input('canteen');

        $data = $canteen->canSearch($can_no,$name);

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
        $this->assign('pagesum',$pagenum);
        $this->assign('page',$page);
        $this->assign('data',$data);

        return $this->fetch();
    }

    //启用食堂
    public function enable()
    {
        $data = input('id');
        $status = CanteenModel::where('id',$data)->find();

        $status->can_status = 1;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改食堂状态成功！','index');
    }

    //禁用食堂
    public function disable()
    {
        $data = input('id');
        $status = CanteenModel::where('id', $data)->find();

        $status->can_status = 0;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改食堂状态成功！','index');
    }

}
