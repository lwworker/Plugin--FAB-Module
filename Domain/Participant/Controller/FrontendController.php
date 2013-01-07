<?php

namespace FabFrontend\Controller;

class FrontendController extends \lw_ddd_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultAction = "showListAction";
    }
}
