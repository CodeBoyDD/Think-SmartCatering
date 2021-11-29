<?php

namespace app\canteen\controller;

use \think\Request;
use think\facade\Db;
use cmf\controller\AdminBaseController;
use app\canteen\model\CanteenjoinModel;

class CanteenJoinController extends AdminBaseController
{
    //显示申请列表
    public function index()
    {
        $list =  CanteenjoinModel::paginate(10)->order('apply_status','desc');
        $page = $list->render();
        $time = time();

        $this->assign('time',date("Y-m-d",$time));
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }

    //申请详情
    public function applyDetail()
    {
        $detail = CanteenjoinModel::where('apply_id', input('apply_id'))->find();
        $this->assign('detail', $detail);

        if($this->request->isPost()){
            $detail->apply_status = input('apply_status');
            $detail->save();

            return $this->success('修改数据成功', 'index');
        }
        return $this->fetch();
    }

    //删除申请
    public function applyDetele()
    {
        if($this->request->isPost()) {
            CanteenjoinModel::where('apply_id', input('apply_id'))->delete();
            return $this->success('删除成功!');
        }
    }

    //查询申请
    public function applySearch()
    {
        $applyinfo = new CanteenjoinModel();
        $time = time();

        $this->assign('time',date("Y-m-d",$time));

        if ($this->request->isPost()) {
            $data = $applyinfo
                ->aSearch(input('apply_id'),input('apply_name'),input('apply_date'));

            if ($data->select()->toArray() == null){
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