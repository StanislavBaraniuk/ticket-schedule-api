<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 14:13
 */

class TicketModel extends Model
{
    function add ($params = []) {
        $this->query(SQL::INSERT($params));
    }

    function delete ($params = []) {
        $this->query(SQL::DELETE($params, 0, TICKETS));
    }

    function update ($params = []) {
        $this->query(SQL::UPDATE($params, 0, TICKETS));
    }

    function get ($params = []) {
        return $this->query(SQL::SELECT($params, 0, TICKETS));
    }

    function getById ($params) {
        return $this->query(SQL::SELECT(array("GET" => ["*"], "WHERE" => ["ID" => $params]), 0, TICKETS));
    }
}