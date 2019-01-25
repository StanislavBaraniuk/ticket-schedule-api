<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 14:36
 */

class SiteController extends Controller
{
    function __construct()
    {
        $this->model = $this->getModel(__CLASS__);
    }

    function onAction() {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->on();
    }

    function offAction() {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->off();
    }
}