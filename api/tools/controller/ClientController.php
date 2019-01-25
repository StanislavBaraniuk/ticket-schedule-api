<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 00:24
 */

class ClientController extends Controller {

    function __construct()
    {
        $this->model = $this->getModel(__CLASS__);
    }

    function addAction($params = []) {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->add(Parser::json());
    }

    function updateAction($params = []) {
        Access::_RUN_(["authorization"]);
        $this->model->update(Parser::json());
    }

    function deleteAction($params = []) {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->delete(Parser::json());
    }

    function getAction($params = []) {
        Access::_RUN_(["authorization"]);
        $this->model->get(Parser::json());
    }

    function getByIdAction($params) {
        Access::_RUN_(["authorization"]);
        echo $this->model->getById($params);
    }
}