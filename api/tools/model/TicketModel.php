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

        ResponseControl::outputGet();
    }

    function delete ($params) {
        $this->query(SQL::DELETE(["ID" => $params], 0, TICKETS));
        $this->query(SQL::DELETE(["TICKET_ID" => $params], 0, ORDERS));

        ResponseControl::outputGet();
    }

    function update ($params) {
        $this->query(SQL::UPDATE($params, 0, TICKETS));

        ResponseControl::outputGet("");
    }

    function get ($params = []) {
        $tickets = $this->query(
            SQL::SELECT($params, 0, TICKETS)
        );

        foreach ($tickets as &$ticket) {
            $ticket["STATIONS"] = explode(",", $ticket["STATIONS"]);
            $ticket["PLACES"] = explode(",", $ticket["PLACES"]);
        }

        return $tickets;
    }

    function getById ($params) {
        return $this->query(SQL::SELECT(array("GET" => ["*"], "WHERE" => ["ID" => $params]), 0, TICKETS));
    }
}