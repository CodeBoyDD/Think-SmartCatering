<?php
namespace app\baocan\controller;


use \think\Request;
use think\facade\Db;
use app\baocan\model\NoticeModel;
use cmf\controller\AdminBaseController;


class NoticeController extends AdminBaseController
{

    //显示公告
    public function index()
    {
        $list = NoticeModel::paginate(10);
        $page = $list->render();
        $time = time();

        $this->assign('time',date("Y-m-d",$time));
        $this->assign('list', $list);
        $this->assign('page', $page);

        return $this->fetch();
    }



    //添加公告
    public function noticeAdd()
    {
        $notice = new NoticeModel();
        $time = time();
        $this->assign('time',date("Y-m-d",$time));

        if(request()->ispost()) {

            $notice->notice_title   = input('post.notice_title');

            //富文本数据库转义
            $notice_content = htmlspecialchars_decode($_POST['notice_content']);
            $notice->notice_content = $notice_content;

            $notice->notice_status  = input('notice_status');
            $notice->notice_date    = input('notice_date');

            $notice->save();
            return $this->success('修改成功', 'index');
        }
        return $this->fetch();
    }

    //编辑公告
    public function noticeEdit()
    {
        $noticeedit = NoticeModel::where('notice_id', input('notice_id'))->find();
        $this->assign('editdata', $noticeedit);

        if (request()->isPost()) {
            $noticeedit->notice_title   =  input('post.notice_title');

            //富文本数据库转义
            $notice_content = htmlspecialchars_decode($_POST['notice_content']);
            $noticeedit->notice_content =  $notice_content;

            $noticeedit->save();
            return $this->success('修改数据成功', 'index');
        }
        return $this->fetch();
    }

    //删除公告
    public function noticeDelete()
    {
        if($this->request->isPost()) {
            NoticeModel::where('notice_id', input('notice_id'))->delete();
            return $this->success('删除成功!');
        }
    }

    //公告详情
    public function noticeDetail()
    {
        $noticedateil = NoticeModel::where('notice_id',input('notice_id'))->find();
        $this->assign('noticedetail',$noticedateil);

        return $this->fetch();
    }

    //搜索公告
    public function noticeSearch()
    {
        $notice       = new NoticeModel();
        $notice_id    = input('notice_id');
        $notice_date  = input('notice_date');
        $notice_title = input('notice_title');

        $time         = time();
        $this->assign('time',date("Y-m-d",$time));

        $noticeinfo = $notice->noticeSearch($notice_id,$notice_title,$notice_date);

        $this->assign('data',$noticeinfo);
        return $this->fetch();
    }

    //发布公告
    public function enable()
    {
        $status = NoticeModel::where('notice_id', input('notice_id'))->find();

        $status->notice_status = 1;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改成功！');
    }

    //禁用公告
    public function disable()
    {
        $status = NoticeModel::where('notice_id', input('notice_id'))->find();

        $status->notice_status = 0;
        $status->save();

        //返回ajax请求不刷新页面
        return $this->success('修改状态成功！');
    }



    //上传文件，图片
    public function upload()
    {
        return $this->fetch();
    }


}
