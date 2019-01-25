<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 14:13
 */

class ClientModel extends Model
{
    function add ($params = []) {
        $this->query(SQL::INSERT($params));
    }

    function delete ($params = []) {
        $this->query(SQL::DELETE($params, 0, USERS));
    }

    function update ($params = []) {
        $this->query(SQL::UPDATE($params, 0, USERS));
    }

    function get ($params = []) {
        $this->query(SQL::SELECT($params, 0, USERS));
    }

    function getById ($params) {
        return json_encode($this->query(SQL::SELECT(array("GET" => ["ID","FIRST_NAME","LAST_NAME","EMAIL","PASSWORD","PHONE","SEX","ONLINE","ACTIVE_ORDER","AVATAR"], "WHERE" => ["ID" => $params]), 0, USERS)));
    }
}