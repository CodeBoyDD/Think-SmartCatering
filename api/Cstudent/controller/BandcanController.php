<?php
//食堂解绑重绑页

namespace api\Cstudent\controller;


use api\Ccommon\controller\changeUserController;
use api\Cstudent\model\CanModel;
use api\Cstudent\model\InfoModel;
use cmf\controller\RestBaseController;

class BandcanController extends RestBaseController
{

    /**
     * 显示用户绑定食堂的信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 参数 ：token    获取头部的请求头中token
     */
    public function index(){
        $info = new changeUserController();
        $student = $info->index();
        $res = InfoModel::where(['cus_no'=>$student['no'],'cus_type'=>$student['type'],'cus_status'=>1])
            ->field('can_no,can_status,school_no')->with('canteenes')->find()->toArray();
//        dump($res);
        return json($res,200);
    }


    /**
     * 解除用户绑定食堂
     * @return \think\response\Json
     * 参数：将信息也中获得的cus_no，与解绑按钮绑定到一起传入给到该接口
     */
    public function Unbound(){
        $cus_no = $this->request->param('cus_no');
        if (!is_null($cus_no)){
            $result = InfoModel::where(['cus_no'=> $cus_no,'cus_status'=>1])
                ->update(['can_no'=>null,'can_status'=>0]);
            if ($result){
                return json(['code'=>200,'msg'=>'解绑成功']);
            }else{
                return json(['code'=>500,'msg'=>'系统错误,解绑失败']);
            }
        }else{
            return json(['code'=>2001,'msg'=>'系统错误,无法定位']);
        }
    }


    /**
     * 绑定用户信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 参数：
     *  cus_no      将信息也中获得的cus_no，与解绑按钮绑定到一起传入给到该接口
     *  can_no      input输入框输入
     */
    public function Binding(){
        $cus_no = $this->request->param('cus_no');
        $can_no = $this->request->param('can_no');
        if (!is_null($cus_no) && !is_null($can_no)){
            $is_in = CanModel::where(['can_no'=>$can_no])->find();
            $canteen = $is_in['canteen'];
            if($is_in){
                $result = InfoModel::where(['cus_no'=> $cus_no,'cus_status'=>1])
                    ->update(['can_no'=>$can_no,'can_status'=>1]);
                if ($result){
                    return json(['msg'=>'绑定成功','data'=>['canteen'=>$canteen,'can_no'=>$can_no]],200);
                }else{
                    return json(['code'=>500,'msg'=>'系统错误,绑定失败']);
                }
            }else{
                return json(['code' => 2001, 'msg' => '系统错误,无法定位']);
            }
        }else{
            return json(['code'=>500,'msg'=>'数据缺失']);
        }
    }
}