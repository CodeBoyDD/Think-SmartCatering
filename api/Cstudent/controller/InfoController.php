<?php
/**
 * 个人信息页
 */

namespace api\Cstudent\controller;

use api\Cstudent\model\ClassModel;
use api\Cstudent\model\InfoModel;
use api\Ccommon\controller\changeUserController;
use cmf\controller\RestBaseController;
use think\facade\Db;

class InfoController extends RestBaseController
{

    /**
     * 显示个人中心信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        $info = new changeUserController();
        $student = $info->index();
        $res = InfoModel::where(['cus_no'=>$student['no'],'cus_type'=>$student['type']])
                ->field('id,customer,phone,class_no,can_no')
            ->with(['classes','canteenes'])->find()->toArray();
        return json($res,200);
    }

    /**
     * 修改用户信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update_data(){
        $id = $this->request->param('id');
        if(!is_null($id)){
            $name = $this->request->param('name');
            $class = $this->request->param('class');
            if ($name && $class){
                $no = ClassModel::where(['class'=>$class])->find();
//                dump($no['class_no']);
                if ($no){
                    $res = Db::table('yfc_a_customer')
                        ->where('id','=',$id)
                        ->update(['customer'=>$name,'class_no'=>$no['class_no']]);
                    if ($res){
                        return json(['code'=>200,'msg'=>'数据已更新']);
                    }else{
                        return  json(['code'=>500,'msg'=>'系统错误，无法更新']);
                    }
                }else{
                    return  json(['code'=>2001,'msg'=>'无对应的班级']);
                }
            }else{
                return json(['code'=>220,'msg'=>'已是该状态，不用进行修改']);
            }
        }else{
            return json(['code'=>2001,'msg'=>'无法定位到对应的数据，无法修改']);
        }

    }

    /**
     * 注销用户
     * @return \think\response\Json
     */
    public function delete_data(){
        $id = $this->request->param('id');
        if(!is_null($id)){
            $res = InfoModel::destroy($id);
            if ($res){
                return json(['code'=>200,'msg'=>'用户已被注销']);
            }else{
                return json(['code'=>500,'msg'=>'系统错误，无法删除']);
            }
        }else{
            return json(['code'=>2001,'msg'=>'无法定位到对应的数据，无法删除']);
        }
    }

}