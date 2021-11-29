<?php
namespace app\canteen\controller;

use \think\Request;
use cmf\controller\AdminBaseController;

class IndexController extends AdminBaseController
{
    public function index()
    {
        return $this->fetch();
    }
}


