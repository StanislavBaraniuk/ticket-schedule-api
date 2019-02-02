<?php

class CityModel extends Model {
    function add ($params = []) {
        $this->query(SQL::INSERT($params));
    }

    function delete ($params = []) {
        $this->query(SQL::DELETE($params, 0, STATIONS));
    }

    function get ($params = []) {
        $load_cities = $this->query(SQL::SELECT(["GET"=>["*"]], 0, STATIONS));
        foreach ($load_cities as &$city) {
            $return_cities[$city["ID"]] = $city["NAME"];
        }

        return $return_cities;
    }
}


