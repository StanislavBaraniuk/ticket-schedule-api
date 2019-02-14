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
        Access::_RUN_(["authorization", "site_online"]);
        $this->model->update($this->request);
    }

    function deleteAction($params) {
        Access::_RUN_(["authorization", "admin"]);
        $this->model->delete($params);
    }

    function getAction() {
        Access::_RUN_(["authorization", "site_online"]);
        ResponseControl::outputGet($this->model->get());
    }

    function getByIdAction($params) {
        Access::_RUN_(["authorization", "site_online"]);
        ResponseControl::outputGet($this->model->getById($params));
    }

    function gendersAction () {
        Access::_RUN_(["authorization", "admin"]);
        ResponseControl::outputGet($this->model->genders(Parser::json()));
    }

    function countAction () {
        Access::_RUN_(["authorization", "admin"]);
        ResponseControl::outputGet($this->model->count());
    }

    function onlineAction () {
        Access::_RUN_(["authorization", "admin"]);
        ResponseControl::outputGet($this->model->online(Parser::json()));
    }
}