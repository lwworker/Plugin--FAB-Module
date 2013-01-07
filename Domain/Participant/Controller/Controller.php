<?php

namespace FabFrontend\Controller;
use \LWddd\Controller as Controller;

class FrontendController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultAction = "showListAction";
    }
}
