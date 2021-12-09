<?php
namespace app\opinion\controller;

use cmf\controller\AdminBaseController;
use app\opinion\model\OpinionModel;
use \think\Request;

class OpinionController extends AdminBaseController
{
    //首页显示 -> 分页显示
    public function index()
    {
        $opinion = new OpinionModel();
        $data = $opinion->opinionList()->paginate(10);
        $page =  $data->render();

        //dump($data);
        /*计算总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10

        $time = time();

        $this->assign('time',date("Y-m-d",$time));
        $this->assign('sum',$sum);
        $this->assign('pagenum',$pagenum);
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /*意见详情 ->查看意见 —> 意见回访*/
    public function detail()
    {
        $opinion = new OpinionModel();
        $id = input('id');
        $data = $opinion->opinionDetail($id)->find();
        //dump($data);
        //意见回访
//        if (request()->isPost()) {
//            $this->visit($id);
//        }
        $this->assign('data', $data);
        return $this->fetch();
    }

    //意见搜索
    public function search()
    {
        $opinion   = new OpinionModel();
        $op_no    = input('op_no');
        $op_title = input('op_title');
        $op_date  =  input('op_date');

        //dump(input('param.'));

        $data = $opinion->opinionSearch($op_no, $op_title, $op_date)->paginate(10);

        $time = time();
        $this->assign('time',date("Y-m-d",$time));
        /*计算总数*/
        $sum     = $data->total();

        /*根据分页数+$key+1实现递增序号*/
        $pagenum = input('page');
        if($pagenum  <= 1) {$pagenum = 1;}
        $pagenum = ((int)$pagenum -1)*10;//当前页码减1乘以10




        $this->assign('pagenum',$pagenum);
        $this->assign('sum',$sum);
        $this->assign('data',$data);

        return $this->fetch();
    }

    //删除意见
    public function delete()
    {
        $moreinfo = OpinionModel::where('opinion_id', input('opinion_id'))->delete();
        $this->success('删除成功！');
        return $this->fetch();
    }

    //意见回访
    public function visit($opinion_id)
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