<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 00:25
 */

/**
 * Class CityController
 *
 * Stations control
 */
class CityController extends Controller {

    /**
     * Contain body of the HTTP request
     *
     * @var mixed
     */
    private $request;

    function __construct()
    {

        /**
         * @var model contain object of Model
         */
        $this->model = $this->getModel(__CLASS__);

        /**
         * Assign value of body of the input request
         * Aquilon base method
         */
        $this->request = Parser::json();
    }

    function addAction() {
        /*
         * Check do you have acces to this function
         */
        Access::_RUN_(["authorization", "admin"]);
        
        $this->model->add($this->request);
    }

    function deleteAction($id) {
        Access::_RUN_(["authorization", "admin"]);
        ResponseControl::outputGet($this->model->delete($id));
    }

    function getAction () {
        ResponseControl::outputGet($this->model->get($this->request));
    }

    function getwithkeysAction () {
        ResponseControl::outputGet($this->model->getWithKeys());
    }
}