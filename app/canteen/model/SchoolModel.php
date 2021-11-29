<?php

namespace app\canteen\model;

use think\facade\Db;
use think\Model;

class SchoolModel extends Model
{
    protected $pk = 'school_id';
    protected $table = 'cmf_baocan_school';

    //展示学校-食堂关联信息
    public function canteenList(){
        $data = Db::table('cmf_baocan_school')
            ->alias('school')
            ->rightJoin(['cmf_baocan_canteen'=>'canteen'],'school.school_id=canteen.school_id')
            ->select();
        return $data;
    }
}