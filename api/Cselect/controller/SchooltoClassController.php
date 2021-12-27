<?php


namespace api\Cselect\controller;


use api\Cselect\model\SchooltoClassModel;
use cmf\controller\RestBaseController;
use think\Request;

class SchooltoClassController extends RestBaseController
{


    /**
     * @param $school_no
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function index($school_no){
        $class = new SchooltoClassModel();
        $class_list = $class->field('class,class_no')->where('school_no',$school_no)->select()->toArray();
        return $class_list;
    }
    /**
     * @param Request $request
     * 将获得到的班级信息返回到头部中
     *
     */
    public function select(){
        $class_no = $this->request->param('class_no');
        $this->success('',$class_no,['class_no'=>$class_no]);
    }
}
{

}