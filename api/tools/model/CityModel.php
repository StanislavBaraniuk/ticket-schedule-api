<?php

class CityModel extends Model {
    function add ($params) {
        // Aquilon base method
        $this->query(SQL::INSERT($params));
    }

    function delete ($params) {
        $this->query(SQL::DELETE(["ID" => $params], 0, STATIONS));
        
        $this->query(SQL::DELETE(["FROM_PLACE" => $params], 0, ORDERS));
        $this->query(SQL::DELETE(["TO_PLACE" => $params], 0, ORDERS));

        $this->query(SQL::DELETE(["FROM_PLACE" => $params], 0, TICKETS));
        $this->query(SQL::DELETE(["TO_PLACE" => $params], 0, TICKETS));

        $tickets = $this->query(SQL::SELECT(["GET" => ["ID","STATIONS"]], 0, TICKETS));

        foreach ($tickets as $key => &$ticket) {
            if (strpos($ticket["STATIONS"], $params) !== false) {
                $ticket["STATIONS"] = str_replace($params, '', $ticket["STATIONS"]);

                $ticket["STATIONS"] = explode(',', $ticket["STATIONS"]);

                foreach ($ticket["STATIONS"] as $s_key => $STATION) {
                    if (empty($STATION)) {
                        array_splice($ticket["STATIONS"], $s_key, 1);
                    }
                }

                $ticket["STATIONS"] = implode(',', $ticket["STATIONS"]);

                $this->query(SQL::UPDATE(["SET" => ["STATIONS" => $ticket["STATIONS"]], "WHERE" => ["ID" => $ticket["ID"]]], 0, TICKETS));
            }
        }

        return count($tickets);
    }

    function get () {
        $load_cities = $this->query(SQL::SELECT(["GET"=>["*"]], 0, STATIONS));
        foreach ($load_cities as &$city) {
            $return_cities[$city["ID"]] = $city["NAME"];
        }

        return $return_cities;
    }

    function getWithKeys () {
        return $this->query(SQL::SELECT(["GET"=>["*"]], 0, STATIONS));
    }
}


