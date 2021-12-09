<?php
namespace app\notice\controller;

use app\canteen\model\CanteenModel;
use \think\Request;
use think\facade\Db;
use app\notice\model\NoticeModel;
use cmf\controller\AdminBaseController;


class NoticeController extends AdminBaseController
{
    /*显示公告*/
    public function index()
    {
        $notice = new NoticeModel();
        $data = $notice->noticeList()->paginate(10);
        $page = $data->render();
        $time = time();

        /*计算食堂总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10


        $this->assign('time',date("Y-m-d",$time));
        $this->assign('data', $data);
        $this->assign('page', $page);
        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);

        return $this->fetch();
    }

    /*添加公告*/
    public function add()
    {
        $notice = new NoticeModel();
        $canteen = CanteenModel::select();

        $time = time();
        $this->assign('time',date("Y-m-d",$time));
        $this->assign('canteen',$canteen);

        if(request()->ispost())
        {
            $notice->n_no      = input('n_no');
            $notice->n_title   = input('n_title');
            $notice->can_no    = input('can_no');

            //富文本数据库转义
            $n_content = htmlspecialchars_decode($_POST['n_content']);
            $notice->n_content = $n_content;

            $notice->n_status  = input('n_status');
            $notice->n_date    = input('n_date');

            $notice->save();


            return $this->success('创建公告成功', 'index');
        }
        return $this->fetch();
    }

    /*编辑公告*/
    public function edit()
    {
        $edit = NoticeModel::where('id', input('id'))->find();
        $canteen = CanteenModel::select();
        $this->assign('canteen',$canteen);
        $this->assign('data', $edit);

        if (request()->isPost()) {
            $edit->n_no      =  input('n_no');
            $edit->n_title   =  input('n_title');
            $edit->can_no    =  input('can_no');

            //富文本数据库转义
            $n_content = htmlspecialchars_decode($_POST['n_content']);
            $edit->n_content =  $n_content;

            $edit->save();
            return $this->success('修改数据成功', 'index');
        }
        return $this->fetch();
    }

    /*删除公告*/
    public function delete()
    {
        if(request()->isPost()) {
            NoticeModel::where('id', input('id'))->delete();
            return $this->success('删除成功!');
        }
    }

    /*公告详情*/
    public function detail()
    {
        $notice = new NoticeModel();

        $id = input('id');
        $data = $notice->noticeList()->where('notice.id',$id)->find();

        //dump($data);
        $this->assign('data',$data);

        return $this->fetch();
    }

    /*搜索公告*/
    public function search()
    {
        $notice       = new NoticeModel();

        $time         = time();
        $this->assign('time',date("Y-m-d",$time));

        $n_no    = input('n_no');
        $n_date  = input('n_date');
        $n_title = input('n_title');
        $data = $notice->noticeSearch($n_no,$n_title,$n_date)->paginate(10);
        $page = $data->render();

        $this->assign('data',$data);

        /*计算食堂总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        $this->assign('page',$page);
        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);

        return $this->fetch();
    }

    /*发布公告*/
    public function enable()
    {
        $status = NoticeModel::where('id', input('id'))->find();

        $status->n_status = 1;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改成功！');
    }

    /*禁用公告*/
    public function disable()
    {
        $status = NoticeModel::where('id', input('id'))->find();

        $status->n_status = 0;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改状态成功！');
    }

    /*上传文件，图片*/
    public function upload()
    {
        return $this->fetch();
    }


}
