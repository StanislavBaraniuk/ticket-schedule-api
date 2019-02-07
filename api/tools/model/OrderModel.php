<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 14:14
 */

class OrderModel extends Model
{
    /**
     * Create new order
     *
     * @param array $params
     */
    function add ($params = []) {
        $this->query(SQL::INSERT($params, 0, ORDERS));

        $places = explode(',', $this->query(SQL::SELECT(["GET" => ["PLACES"], "WHERE" => ["ID" => $params["TICKET_ID"]]], 0, TICKETS))[0]["PLACES"]);

        unset($places[array_search($params["PLACE"], $places)]);

        $user_email = $this->query(SQL::SELECT(array("GET" => ["EMAIL"], "WHERE" => ["TOKEN" => Parser::getBearerToken()]), 0, USERS))[0];

        $this->query(SQL::UPDATE(["SET" => ["PLACES" => implode(',', $places)], "WHERE" => ["ID" => $params["TICKET_ID"]]], 0, TICKETS));

        $this->sendTicketToUser($user_email["EMAIL"],
            $params["CODE"],
            $this->query(SQL::SELECT(["GET" => ["*"], "WHERE" => ["ID" => $params["TICKET_ID"]]], 0, TICKETS))[0]);
    }

    private function sendTicketToUser ($email, $token, $ticket) {
        $subject = "Ticket ".substr($token, 0, 5);

        $message = ' 
            <!DOCTYPE html>
            <html lang="en">
              <head>
                    <title>YOUR TICKET</title> 
                </head> 
                <body> 
                    <div style="margin-top: 10px; width: 125px; text-align: center; margin-left: auto; margin-right: auto;">
                      <p style="margin-left: calc(50% - 60px); background-color: #38863d; font-size: 20px; padding: 10px; color: white; font-weight: bolder; letter-spacing: 5px; padding-left: 15px">
                        TIC.S
                      </p>
                    </div>
                  
                  <div style="margin-top: 10px; text-align: center; font-size: 20px">
                      '.$ticket["NAME"].'
                    </div>
                  
                  <div style="margin-top: 10px; text-align: center; font-size: 15px">
                      Предявіть цей QR
                    </div>
                  <div style="margin-left: auto; margin-right: auto; margin-top: 20px;  width:250px; text-align: center;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data='.$token.'" alt="">
                  </div>
                   <div style="margin-top: 10px; text-align: center; font-size: 15px">
                      Або продиктуйте код
                    </div>
                    
                   <div style="margin-top: 10px; text-align: center; font-size: 15px; color: red">
                      '.$token.'
                    </div>
                </body> 
            </html>';

        EmailSender::send('tics@tickets-api.zzz.com.ua', $email, "TIC.S", $subject, $message);
    }

    public function getToken () {
        do {
            $token = TokenGenerator::generate();
            $check = !empty($this->checkTokenExisting($token)[0]["ID"]);
        } while ($check);

        return $token;
    }

    public function checkTokenExisting ($token) {
        return $this->query(SQL::SELECT(["GET" => ["ID"], "WHERE" => ["CODE" => $token]], 0, ORDERS));
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

    function getByUser () {

        $user_id = $this->query(SQL::SELECT(["GET" => ["ID"], "WHERE" => ['TOKEN' => Parser::getBearerToken()]], 0, USERS))[0]["ID"];
        $user_orders = $this->query(SQL::SELECT(["GET" => ["*"], "WHERE" => ['USER_ID' => $user_id]], 0, ORDERS));

        $output = [];

        foreach ($user_orders as $user_order) {
            $ticket = $this->query(SQL::SELECT(array("GET" => ["*"], "WHERE" => ["ID" => $user_order["TICKET_ID"] ]), 0, TICKETS))[0];

            $ticket["STATIONS"] = explode(',',$ticket["STATIONS"]);

            $ticket["FROM_PLACE"] = $this->query(SQL::SELECT(array("GET" => ["NAME"], "WHERE" => ["ID" => $ticket["FROM_PLACE"] ]), 0, STATIONS))[0]["NAME"];
            $ticket["TO_PLACE"] = $this->query(SQL::SELECT(array("GET" => ["NAME"], "WHERE" => ["ID" => $ticket["TO_PLACE"] ]), 0, STATIONS))[0]["NAME"];

            foreach ($ticket["STATIONS"] as &$STATION) {
                $STATION = $this->query(SQL::SELECT(array("GET" => ["NAME"], "WHERE" => ["ID" => $STATION ]), 0, STATIONS))[0]["NAME"];
            }
            $output[] = ["ORDER" => $user_order, "TICKET" => $ticket];
        }

        return $output;
    }

    function cancel ($code) {
        return $this->query(SQL::DELETE(["CODE" => $code["CODE"]], 0, ORDERS)) ? 1 : 0;
    }
}