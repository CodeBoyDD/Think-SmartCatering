<?php


namespace api\school\set\controller;

use api\school\common\changeUser;
/**
 * 管理后台设置食堂价格接口
 */

use api\school\set\model\PriceModel;
//use api\school\set\validate\ToolValidate;
use cmf\controller\RestBaseController;
use think\facade\Db;
use think\Request;

class PriceController extends RestBaseController
{
    public function index(Request $request){
//      获取对应的食堂
        $can_no = $request->header('can_no');

        $data = $request->param();
        $val = $this->validate($data,'tool.getTimePriceStatus');
        if($val !== true){
            $this->error($val);
        }else{
            $breakfast = ['fee'=>$data['fee1'],'start_time'=>$data['time1'],'over_time'=>$data['time2']];
            $lunch = ['fee'=>$data['fee2'],'start_time'=>$data['time3'],'over_time'=>$data['time4']];
            $dinner = ['fee'=>$data['fee3'],'start_time'=>$data['time5'],'over_time'=>$data['time6']];
            $status = $data['status'];

            //获取食堂后获取餐类
            $type = Db::table('yfc_a_type')->where(['can_no'=>$can_no])->select()->toArray();
            $type_no = [];
            //遍历出一个数组
            /**
             * 其格式维 type=>type_no
             */
            foreach ($type as &$value){
                $t = $value["type"];
                $type_no["$t"] = $value['type_no'];
            }



            $p = new PriceModel();
            $b_f = $p->changePrice($type_no['早餐'],$breakfast['fee']);
            $b_t = $p->changeTime($type_no['早餐'],$breakfast['start_time'],$breakfast['over_time']);

            $l_f = $p->changePrice($type_no['午餐'],$lunch['fee']);
            $l_t = $p->changeTime($type_no['午餐'],$lunch['start_time'],$lunch['over_time']);

            $d_f = $p->changePrice($type_no['晚餐'],$dinner['fee']);
            $d_t = $p->changeTime($type_no['晚餐'],$dinner['start_time'],$dinner['over_time']);

            $st = $p->changeAllCanSta($can_no,$status);

            if ($b_f && $b_t && $l_f && $l_t && $d_f && $d_t && $st){
                $this->success('修改完成');
            }else{
                $this->success('342');
            }
        }
    }

}