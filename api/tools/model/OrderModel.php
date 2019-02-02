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
        return $this->query(SQL::SELECT($params, 0, ORDERS));
    }

    function getByCode ($params = []) {
        return $this->query(SQL::SELECT(array("GET" => ["*"], "WHERE" => ["CODE" => $params]), 0, ORDERS));
    }
}