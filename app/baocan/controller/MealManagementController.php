<?php

namespace app\baocan\controller;

use think\Db;
use cmf\controller\AdminBaseController;
use app\baocan\model\ClassModel;
use app\baocan\model\SchoolModel;
use app\baocan\model\CanteenModel;

class MealManagementController extends AdminBaseController
{
    public function index()
    {

        return $this->fetch();
    }

}

