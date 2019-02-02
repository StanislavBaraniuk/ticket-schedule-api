<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 00:25
 */

class CityController extends Controller {

    private $request;

    function __construct()
    {
        $this->model = $this->getModel(__CLASS__);
        $this->request = Parser::json();
    }

    function addAction() {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->add($this->request);
    }

    function deleteAction() {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->delete($this->request);
    }

    function getAction () {
        ResponseControl::outputGet($this->model->get($this->request));
    }
}