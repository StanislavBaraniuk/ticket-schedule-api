<?php

class CityModel extends Model {
    function add ($params = []) {
        $this->query(SQL::INSERT($params));
    }

    function delete ($params = []) {
        $this->query(SQL::DELETE($params, 0, STATIONS));
    }

    function get ($params = []) {
        echo json_encode($this->query(SQL::SELECT($params, 0, STATIONS)));
    }
}