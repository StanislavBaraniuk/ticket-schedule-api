<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 14:13
 */

class ClientModel extends Model
{
    function add ($params) {
        $this->query(SQL::INSERT($params));
    }

    function delete ($params) {
        $this->query(SQL::DELETE(["ID" => $params], 0, USERS));
    }

    function update ($params) {
        $this->query(SQL::UPDATE($params, 0, USERS));
    }

    function get () {
        return $this->query(SQL::SELECT(["GET"=> ["*"]], 0, USERS));
    }

    function getById ($params) {
        return $this->query(SQL::SELECT(array("GET" => ["ID","FIRST_NAME","LAST_NAME","EMAIL","PASSWORD","PHONE","SEX","ONLINE","ACTIVE_ORDER","AVATAR"], "WHERE" => ["ID" => $params]), 0, USERS));
    }

    function genders ($params) {
        $genders = [];
        $genders[0] = $this->query(SQL::SELECT(array("GET" => ["COUNT(ID)"], "WHERE" => ["SEX" => 0]), 0, USERS))[0]["COUNT(ID)"];
        $genders[1] = $this->query(SQL::SELECT(array("GET" => ["COUNT(ID)"], "WHERE" => ["SEX" => 1]), 0, USERS))[0]["COUNT(ID)"];
        $genders[2] = $this->query(SQL::SELECT(array("GET" => ["COUNT(ID)"], "WHERE" => ["SEX" => 2]), 0, USERS))[0]["COUNT(ID)"];

        return $genders;
    }

    function count () {
        return $this->query(SQL::SELECT(array("GET" => ["COUNT(ID)"]), 0, USERS))[0]["COUNT(ID)"];
    }

    function online () {
        return $this->query(SQL::SELECT(array("GET" => ["COUNT(ID)"], "WHERE" => ["ONLINE" => 1]), 0, USERS))[0]["COUNT(ID)"];
    }

}