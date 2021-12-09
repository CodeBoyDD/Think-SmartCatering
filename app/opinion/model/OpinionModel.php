<?php

namespace app\opinion\model;

use think\facade\Db;
use think\Model;

class OpinionModel extends Model
{
    protected $pk = 'id';
    protected $table = 'yfc_a_opinion';

    /*===============表操作===============*/

    /*意见表左连接用户和食堂*/
    public function opLjCusLjCan()
    {
        $data = Db::table('yfc_a_opinion')
            ->alias('opinion')
            ->leftJoin(['yfc_a_customer'=>'customer'],'opinion.cus_no = customer.cus_no')
            ->leftJoin(['yfc_a_canteen'=>'canteen'],'opinion.can_no = canteen.can_no');
        return $data;
    }



    /*==============功能实现==============*/

    /*查询意见表及关联信息*/
    public function opinionList()
    {
        $data = $this->opLjCusLjCan()
            ->field('*,opinion.id AS op_id,customer.id AS cus_id,canteen.id AS can_id');
        return $data;
    }

    /*意见详情关联信息*/
    public function opinionDetail($id)
    {
        $data = $this->opinionList()
            ->where('opinion.id',$id);
        return $data;
    }

    // 编号，标题，日期模糊搜索
    public function opinionSearch($op_no,$op_title,$op_date)
    {
        // 日期模糊搜索
        $whereDate = [];
        if ($op_date){
            $whereDate = function ($query) use($op_date){
                $query->where('op_date','LIKE',"%$op_date%");
            };
        }

        // 编号搜索
        $whereNo = [];
        if ($op_no){
            $whereNo = function ($query) use($op_no){
                $query->where('op_no','LIKE',"%$op_no%");
            };
        }

        // 标题模糊搜索
        $whereTitle = [];
        if ($op_title){
            $whereTitle = function ($query) use($op_title){
                $query->where('op_title','LIKE',"%$op_title%");
            };
        }

        // 搜索SQL
        $data = $this->opinionList()
            ->where($whereNo)
            ->whereOr($whereTitle)
            ->whereOr($whereDate);

        return $data;
    }

}

