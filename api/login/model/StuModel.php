<?php


namespace api\login\model;


use think\facade\Db;
use think\Model;

class StuModel extends Model
{
    protected $name = 'a_customer';

    public function Sdata(){
        return Db::table('yfc_a_customer');
    }
}