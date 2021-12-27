<?php


namespace api\Cselect\controller;
/**
 *
 * 学校的食堂选择
 */


use api\Cselect\model\SchooltoCanteenModel;
use cmf\controller\RestBaseController;
use think\Request;

class SchooltoCanteenController extends RestBaseController
{


    /**
     * 修改报餐时间的前一步选择
     * @param $school_no
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 调用index方法时先已经确认了管理端用户的信息
     * 查出对应管理员的学校信息 传入一下接口展示学校的食堂信息
     */
    public function index($school_no){
        $canteen = new SchooltoCanteenModel();
        $canteen_list = $canteen->field('canteen,can_no')->where('school_no',$school_no)->select()->toArray();
        return json($canteen_list);
    }
    /**
     * @param Request $request
     * 将获得到的食堂信息返回到头部中
     */
    public function select(Request $request){
        $can_no = $request->param('can_no');
        $this->success('',$can_no,['can_no'=>$can_no]);
    }
}