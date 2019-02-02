<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 00:24
 */

class ClientController extends Controller {

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

    function updateAction() {
        Access::_RUN_(["authorization"]);
        $this->model->update($this->request);
    }

    function deleteAction() {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->delete($this->request);
    }

    function getAction() {
        Access::_RUN_(["authorization"]);
        ResponseControl::outputGet($this->model->get($this->request));
    }

    function getByIdAction($params) {
        Access::_RUN_(["authorization"]);
        ResponseControl::outputGet($this->model->getById($params));
    }
}