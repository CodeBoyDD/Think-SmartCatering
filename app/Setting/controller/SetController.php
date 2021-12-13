<?php


namespace app\Setting\controller;


use app\canteen\model\CanteenModel;
use app\school\model\SchoolModel;
use app\Setting\model\SetModel;
use cmf\controller\AdminBaseController;
use think\facade\Cache;
use think\facade\Db;

class SetController extends AdminBaseController
{
    public function index(){
        $cantenn_detail = new SetModel();
        $canteen_data = $cantenn_detail->canteenDetail();
//        dump($canteen_data);
        $this->assign('data',$canteen_data);
        return $this->fetch();
    }

    public function list2(){
        $cus_detail = new SetModel();
        $cus_data = $cus_detail->cusDetail();
//        dump($cus_data);
        $this->assign('data',$cus_data);
        return $this->fetch();
    }

    public function common(){
        $school = new SchoolModel();
        $canteen = new CanteenModel();
        $select = $school::select()->toArray();
        $this->assign([
            'select'=>$select,
            'select2'=>null,
            'data'=>null]);
        Cache::set("school_no",$this->request->param('school_no'));
        if (Cache::get("school_no")){
            $select2 = $canteen::where('school_no',Cache::get("school_no"))->select()->toArray();
            $this->assign('select2',$select2);
            Cache::set("can_no",$this->request->param('can_no'));
        }
    }


    //    供应界面
    public function privide()
    {
        $this->common();
        if (Cache::get("can_no")){
            $set = new SetModel();
            $data = $set->getChang(Cache::get("can_no"),Cache::get("school_no"));
            $this->assign('data', $data);
        }

        return $this->fetch();
    }
//    改变食堂供应状态
    public function pri_set1(){
        if ($this->request->isPost()) {
            $id = $this->request->param('id', 0, 'intval');
            if (!empty($id)) {
                $result = Db::table("yfc_a_setting")->where("id", $id)->update(['job_status' => 0]);
                if ($result !== false) {
                    $this->success("申请成功！",url("set/index"));
                    //dump会影响该行代码的执行，以至报出：
                    //Failed to load resource: the server responded with a status of 500 (Internal Server Error)
                    //的错误
                } else {
                    $this->error('申请失败！',url("set/index"));
                }
            } else {
                $this->error('数据传入失败！',url("set/index"));
            }
        }
    }
    public function pri_set2(){
        if ($this->request->isPost()) {
            $id = $this->request->param('id', 0, 'intval');
            if (!empty($id)) {
                $result = Db::table("yfc_a_setting")->where("id",$id)->update(['job_status'=>'1']);
                if ($result !== false) {
                    $this->success("申请成功！",url("set/index"));
                } else {
                    $this->error('申请失败！',url("set/index"));
                }
            } else {
                $this->error('数据传入失败！',url("set/index"));
            }
        }
    }



    //设置食堂报餐费用
    public function setfee(){
        $this->common();
        if (Cache::get("can_no")) {
            $set = new SetModel();
            $data = $set->feelist(Cache::get('school_no'));
            $this->assign('data', $data);
        }
        return $this->fetch();
    }

    public function fee(){
        if ($this->request->isPost()) {
            $id = $this->request->param('id', 0, 'intval');
            if (!empty($id)) {
                $result = Db::table("yfc_a_type")->where(["id" => $id])->update(['fee' => $_POST['fee']]);
                if ($result !== false) {
                    $this->success("申请成功！",url("set/index"));

                } else {
                    $this->error('申请失败！',url("set/index"));
                }
            } else {
                $this->error('数据传入失败！',url("set/index"));
            }
        }
    }


    //修改食堂报餐时间
    public function aboutTime(){
        $this->common();
        if (Cache::get("can_no")) {
            $set = new SetModel();
            $data = $set->timelist(Cache::get('school_no'));
//            dump($data);
            $this->assign('data', $data);
        }
        return $this->fetch();
    }
    public function getTime(){
        {
            $id = $this->request->param("id", 0, 'intval');
            if ($this->request->isPost()) {
                $data = $this->request->param();
//                dump($data);
                $result = $this->validate($data,'set.time');
                if ($result !== true) {
                    // 验证失败 输出错误信息
                    $this->error($result);
                } else {
                    $res = Db::table('yfc_a_setting')->where(["id" => $id])->update(['start_time' => $data['start_time'],'over_time' => $data['over_time']]);
                    if ($res!== false) {
                        $this->success("设置成功！",url("set/index"));
                    } else {
                        $this->error("设置失败！",url("set/index"));
                    }
                }
            }
        }
    }



    //修改用户是否允许可取消报餐
    /**
     * 1、现在用户表中添加一个字段——是否可以允许取消报餐状态
     * 2、通过该状态修改用户取消报餐状态
     * 3、当在设置用户为不可取消报餐时将其订单的取消报餐时间段设置为默认10分钟，即是此时
     * 无法设置用户的取消报餐时间
     * 4、当在设置用户为可取消报餐时其订单的取消时间段可以修改
     * P.S.: 在yfc_a_customer表里添加字段有is_allow_status、is_allow_time（time格式）
     *       可以将yfc_a_setting表中的can_no删除掉 该字段多余，且外键的连接已通过了type_no与yfc_a_type连上再让yfc_a_type表与yfc_a_canteen表连接
     */

    public function allowStudent(){
        $this->common();
        if(Cache::get("can_no")){
            $set = new SetModel();
            $data = $set->cuslist(Cache::get('school_no'));
            $this->assign('data',$data);
//            dump($data);
        }
        return $this->fetch();
    }

    public function allow1(){
        if ($this->request->isPost()) {
            $id = $this->request->param('id', 0, 'intval');
            if (!empty($id)) {
                $result = Db::table("yfc_a_customer")->where(["id" => $id])->update(['is_allow_status' => 0,'is_allow_time'=>'00:10:00']);
                $find = Db::table("yfc_a_customer")->where(["id" => $id])->find();
                $result1 = Db::table('yfc_a_order')->where(["cus_no"=>$find['cus_no']])->update(["allow_cancel_time"=>'00:10:00']);
                if ($result !== false && $result1 !== false) {
                    $this->success("禁用成功！",url("set/list2"));
                } else {
                    $this->error('禁用失败！',url("set/list2"));
                }
            } else {
                $this->error('数据传入失败！',url("set/list2"));
            }
        }
    }
    public function allow2(){
        if ($this->request->isPost()) {
            $id = $this->request->param('id', 0, 'intval');
            if (!empty($id)) {
                $result = Db::table("yfc_a_customer")->where(["id" => $id])->update(['is_allow_status' => 1,'is_allow_time'=>'00:10:00']);
                if ($result !== false) {
                    $this->success("允许成功！",url("set/list2"));
                } else {
                    $this->error('禁用失败！',url("set/list2"));
                }
            } else {
                $this->error('数据传入失败！',url("set/list2"));
            }
        }
    }

    public function allowTime(){
        $is_allow_time = $this->request->param('is_allow_time');
        if ($is_allow_time) {
//            此处的id筛选器未能筛选出对应的id 猜测action不可带参数传输
            $id = $this->request->param('id','0','intval');
            if (!empty($id)) {
                $result = Db::table("yfc_a_customer")->where(["id" => $id])->update(['is_allow_time' => $is_allow_time]);
                if ($result !== false) {
                    $this->success("修改成功！",url("set/list2"));
                } else {
                    $this->error('修改失败！',url("set/list2"));
                }
            } else {
                $this->error('数据传入失败！',url("set/list2"));
            }
        }

    }

    

}