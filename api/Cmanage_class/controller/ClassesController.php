<?php

/**
 * 班级用户详情
 */

namespace api\Cmanage_class\controller;


use api\Ccommon\controller\changeUserController;
use api\Cmanage_class\model\ClassesModel;
use api\Cselect\controller\SchooltoClassController;
use cmf\controller\RestBaseController;
use think\facade\Db;

class ClassesController extends RestBaseController
{

    /**
     * 渲染搜索
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 1、将用户信息查询出来
     * 2、获取对应的学校编号，
     * 3、通过学校编号查出对应的所有班级信息
     * 4、将其渲染到页面中进行班级选择
     */
    public function index(){
        $cus = changeUserController::index();
//        dump($info->index());
        //通过用户编号查询出管理端对应的学校编号
        $cus_Data = ClassesModel::where(['cus_no'=>$cus['no'],'cus_type'=>$cus['type']])->find();
        $schoolNo = $cus_Data['school_no'];
//        dump($schoolNo);
        $data = SchooltoClassController::index($schoolNo);
        return json($data,200,['school_no'=>$schoolNo]);
    }

    /**
     * 查
     * 选择完班级后的点击事件绑定的接口，
     * 该接口将对应的班级信息返回到头部中，
     * 并将用户的信息传入到新的页面中
     */
    public function to(){
        $class_no = $this->request->param('class_no');
        if ($class_no){
            $data = ClassesModel::field('id,customer,phone,cus_status')
                ->where(['class_no'=>$class_no,'cus_type'=>1])
                ->select()->toArray();
            return json(['msg'=>'continue','data'=>$data],200,['class_no'=>$class_no]);
        }else{
            return json('error');
        }
    }

    /**
     * 增
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 添加学生到班级中，班级存在前提
     * 传入参数
     *  name    学生名字
     *  class   班级
     */
    public function add(){
        if ($_POST){
            $name = $this->request->param('name');
            $class = $this->request->param('class');
            $arr = array('name'=>$name,'class'=>$class);
            $val = $this->validate($arr,'class.add');
            if ($val !== true){
                return json($val);
            }else{
                //获取班级对应的编号
                $school_no = $this->request->header('school_no');
//                $school_no = '190506';
                $no = Db::table('yfc_a_class')->where(['school_no'=>$school_no,'class'=>$class])->find();
                if ($no){
                    $res = ClassesModel::where([
                        'customer'      =>      $name,
                        'cus_status'    =>      1,
                        'cus_type'      =>      1,
                        ])->update([
                        'class_no'      =>      $no['class_no'],
                        'class_status'  =>      1,
                        'school_no'     =>      $school_no
                    ]);
                    if ($res){
                        return json('添加成功');
                    }else{
                        return json('添加失败');
                    }
                }else{
                    return json('无对应班级信息');
                }
            }
        }else{
            return json('无数据输入');
        }
    }

    /**
     * 删
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * 将用户从班级中删除，但用户还存在与数据库，
     * 删除之后用户所有的班级、食堂信息将被清理掉
     */
    public function delete(){
        $cl_no = $this->request->header('class_no');
        $sc_no = $this->request->header('school_no');
//        $can_no = $request->param('can_no');
        $id = $this->request->param('id');
        if(!is_null($id)){
            $res = ClassesModel::where([
                'id'            =>          $id,
                'class_no'      =>          $cl_no,
                'school_no'     =>          $sc_no,
            ])->update([
                'class_no'          =>          null,
                'can_no'            =>          null,
                'class_status'      =>          0,
                'can_status'        =>          0,
                'meal_status'       =>          0,
                'is_allow_status'   =>          0
            ]);
            if($res !== false){
                return json(['code'=>200,'msg'=>'删除成功',]);
            }else{
                return json(['code'=>500,'msg'=>'系统错误,删除失败',]);
            }
        }else{
            return json(['code'=>2001,'msg'=>'无法定位到对应的数据，无法删除']);
        }

    }

    /**
     * 点击查看用户信息详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * 传入参数
     *  id      用户的标识
     */
    public function see_data(){
        $id = $this->request->param('id');
        if(!is_null($id)){
            $res = Db::table('yfc_a_customer cus , yfc_a_class cl')
                    ->where('cus.class_no=cl.class_no')->where(['cus.id'=>$id])
                    ->field('cus.customer,cl.class,cus.cus_status,cus.id')->find();
            if ($res){
                return json(['code'=>200,'data'=>$res,'msg'=>'数据加载成功']);
            }else{
                return json(['code'=>500,'msg'=>'系统错误']);
            }
        }else{
            return json(['code'=>2001,'msg'=>'无法定位到对应的数据，无法查看']);
        }
    }

    /**
     * 用户详情页下的修改用户状态
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * 传入参数
     *  key     用户状态（0/1）
     *
     */
    public function update(){
        $id = $this->request->param('id');
        if(!is_null($id)){
            $key = $this->request->param('key');
            if ($key){
                $res = Db::table('yfc_a_customer')
                    ->where('id','=',$id)
                    ->update(['cus_status'=>$key]);
                if ($res){
                    return json(['code'=>200,'msg'=>'数据已更新']);
                }else{
                    return  json(['code'=>500,'msg'=>'系统错误，无法更新']);
                }
            }else{
                return json(['code'=>220,'msg'=>'已是该状态，不用进行修改']);
            }
        }else{
            return json(['code'=>2001,'msg'=>'无法定位到对应的数据，无法修改']);
        }
    }

}