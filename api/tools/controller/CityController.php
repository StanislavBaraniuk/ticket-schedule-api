<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 00:25
 */

class CityController extends Controller {

    function __construct()
    {
        $this->model = $this->getModel(__CLASS__);
    }

    function addAction($params = []) {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->add(Parser::json());
    }

    function deleteAction($params = []) {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->delete(Parser::json());
    }

    function getAction () {
        Access::_RUN_(["authorization"]);
        $this->model->get(Parser::json());
    }
}