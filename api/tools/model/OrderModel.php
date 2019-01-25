<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 14:14
 */

class OrderModel extends Model
{
    function add ($params = []) {
        $this->query(SQL::INSERT($params));
    }

    function delete ($params = []) {
        $this->query(SQL::DELETE($params, 0, ORDERS));
    }

    function get ($params = []) {
        $this->query(SQL::SELECT($params, 0, ORDERS));
    }

    function getById ($params = []) {
        return json_encode($this->query(SQL::SELECT(array("GET" => ["*"], "WHERE" => ["ID" => $params]), 0, ORDERS)));
    }
}