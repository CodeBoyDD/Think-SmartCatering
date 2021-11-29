<?php

namespace app\baocan\controller;

use cmf\controller\AdminBaseController;

class PayManagementController extends AdminBaseController
{
    public function index()
    {
        return $this->fetch();
    }
}

