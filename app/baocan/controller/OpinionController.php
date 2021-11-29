<?php
namespace app\baocan\controller;

use cmf\controller\AdminBaseController;
use app\baocan\model\OpinionModel;
use \think\Request;

class OpinionController extends AdminBaseController
{

    //首页显示 -> 分页显示
    public function index()
    {
        $list =  OpinionModel::paginate(10);
        $page =  $list->render();

        $time = time();
        $this->assign('time',date("Y-m-d",$time));

        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }

    //意见详情 ->查看意见 —> 意见回访
    public function opinionDetail()
    {
        $opinion_id = input('opinion_id');
        $moreinfo = OpinionModel::where('opinion_id',$opinion_id )->find();

        //意见回访
        if ($this->request->isPost()) {
            $this->opinionVisit($opinion_id);
        }
        $this->assign('moreinfo', $moreinfo);
        return $this->fetch();
    }

    //意见搜索
    public function opinionSearch()
    {
        $opinioninfo   = new OpinionModel();
        $opinion_id    = input('opinion_id');
        $opinion_title = input('opinion_title');
        $opinion_date  =  input('opinion_date');

        $time = time();
        $this->assign('time',date("Y-m-d",$time));

        $data = $opinioninfo->opinionSearch($opinion_id, $opinion_title, $opinion_date);
        $this->assign('data',$data);

        return $this->fetch();
    }

    //删除意见
    public function opinionDelete()
    {
        $moreinfo = OpinionModel::where('opinion_id', input('opinion_id'))->delete();
        $this->success('删除成功！');
        return $this->fetch();
    }

    //意见回访
    public function opinionVisit($opinion_id)
    {
        $visit = OpinionModel::find($opinion_id);

        //富文本数据库转义
        $opinion_visit = htmlspecialchars_decode($_POST['opinion_visit']);

        $visit->opinion_visit = $opinion_visit;
        $visit->visit_status  = 1;
        $visit->save();
        return $this->success("回访成功！",'index');
    }
}