<?php

/**
 * 报餐设置页
 */

namespace api\CoperateCanteen\controller;

use api\CoperateCanteen\model\TypeChangeModel;
use cmf\controller\RestBaseController;
use think\facade\Db;
use think\Request;

class TypeChangeController extends RestBaseController
{
    /**
     * 管理员添加食堂的餐类接口
     * 传入参数有：
     *          食堂编号：can_no
     *          餐类编号：type_no
     *          餐类名称：type
     *          餐类费用：fee
     *          开始时间：start_time
     *          结束时间：over_time
     */
    public function add(Request $request)
    {
        $can_no = $request->header('can_no');
//        $can_no = $request->param('can_no');
        $param = $request->param();
        $val = $this->validate($param, 'TypeChange.add');
        if ($val !== true) {
            $this->error($val);
        } else {
            $model = new TypeChangeModel();
            $res1 = $model->setType($can_no, $param);
            $res2 = $model->setSetting($can_no, $param);
            if ($res1 && $res2) {
                return json(['code' => 200, 'msg' => '添加成功',]);
            } else {
                return json(['code' => 500, 'msg' => '系统错误，添加失败',]);
            }
        }
    }


    public function delete(Request $request){
        $can_no = $request->header('can_no');
//        $can_no = $request->param('can_no');
        $type = $request->param('name');
        if(!is_null($type)){
            $result1 = Db::table('yfc_a_type')->where(['type'=>$type,'can_no'=>$can_no])->find();
            $result2 = Db::table('yfc_a_setting')->where(['type_no'=>$result1['type_no'],'can_no'=>$can_no])->find();

            $res1 = Db::table('yfc_a_type')->delete($result1['id']);
            $res2 = Db::table('yfc_a_type')->delete($result2['id']);

            if($res1 !== false && $res2 !== false){
                return json(['code'=>200,'msg'=>'删除成功',]);
            }else{
                return json(['code'=>500,'msg'=>'系统错误,删除失败',]);
            }
        }else{
            return json(['code'=>2001,'msg'=>'无法定位到对应的数据，无法删除']);
        }

    }


    public function update(Request $request)
    {
//        获取对应的食堂
//        $can_no = $request->param('can_no');
        $can_no = $request->header('can_no');

        $data = $request->param();
        $val = $this->validate($data, 'TypeChange.update');
        if ($val !== true) {
            $this->error($val);
        } else {
            $breakfast = ['fee' => $data['fee1'], 'start_time' => $data['time1'], 'over_time' => $data['time2']];
            $lunch = ['fee' => $data['fee2'], 'start_time' => $data['time3'], 'over_time' => $data['time4']];
            $dinner = ['fee' => $data['fee3'], 'start_time' => $data['time5'], 'over_time' => $data['time6']];
            $status = $data['status'];

            //获取食堂后获取餐类
            $type = Db::table('yfc_a_type')->where(['can_no' => $can_no])->select()->toArray();
            $type_no = [];
            //遍历出一个数组
            /**
             * 其格式维 type=>type_no
             */
            foreach ($type as &$value) {
                $t = $value["type"];
                $type_no["$t"] = $value['type_no'];
            }

            $p = new TypeChangeModel();
            $b_f = $p->changePrice($type_no['早餐'], $breakfast['fee']);
            $b_t = $p->changeTime($type_no['早餐'], $breakfast['start_time'], $breakfast['over_time']);

            $l_f = $p->changePrice($type_no['午餐'], $lunch['fee']);
            $l_t = $p->changeTime($type_no['午餐'], $lunch['start_time'], $lunch['over_time']);

            $d_f = $p->changePrice($type_no['晚餐'], $dinner['fee']);
            $d_t = $p->changeTime($type_no['晚餐'], $dinner['start_time'], $dinner['over_time']);

            $st = $p->changeAllCanSta($can_no, $status);

            if ($b_f && $b_t && $l_f && $l_t && $d_f && $d_t && $st) {
                return json(['code' => 200, 'msg' => '修改成功']);
            } else {
                return json(['code' => 500, 'msg' => '修改失败']);
            }
        }
    }

}