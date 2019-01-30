<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/24/19
 * Time: 23:23
 */

class UserModel extends Model
{
    public function checkTokenExisting ($token) {
        return $this->query(SQL::SELECT(["GET" => ["EMAIL"], "WHERE" => ["TOKEN" => $token]], 0, USERS));
    }

    public function getPassword ($login) {
        return $this->query(SQL::SELECT(["GET" => ["PASSWORD"], "WHERE" => ["EMAIL" => $login]], 0, USERS))[0]["PASSWORD"];
    }

    public function setUserIP ($email, $ip) {
        $this->query(SQL::UPDATE(["SET" => ["IP" => $ip], "WHERE" => ["EMAIL" => $email]], 0, USERS));
    }

    public function setUserToken ($email, $token) {
        $this->query(SQL::UPDATE(["SET" => ["TOKEN" => $token], "WHERE" => ["EMAIL" => $email]], 0, USERS));
    }

    public function logout () {
        $this->query(SQL::UPDATE(["SET" => ["IP" => ""], "WHERE" => ["TOKEN" =>  explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS));
        $this->query(SQL::UPDATE(["SET" => ["TOKEN" => ""], "WHERE" => ["TOKEN" =>  explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS));
    }

    public function setPassword ($password) {
        $this->query(SQL::UPDATE(["SET" => ["PASSWORD" => $password], "WHERE" => ["TOKEN" =>  explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS));
    }

    public function setFPassword ($email, $password) {
        $this->query(SQL::UPDATE(["SET" => ["PASSWORD" => $password], "WHERE" => ["EMAIL" => $email]], 0, USERS));
    }

    public function setFIO ($f, $l) {
        $this->query(SQL::UPDATE(["SET" => ["FIRST_NAME" => $f, "LAST_NAME" => $l], "WHERE" => ["TOKEN" =>  explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS));
    }

    public function setPhone ($phone) {
        $this->query(SQL::UPDATE(["SET" => ["PHONE" => $phone], "WHERE" => ["TOKEN" =>  explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS));
    }

    public function setEmail ($email) {
        $this->query(SQL::UPDATE(["SET" => ["EMAIL" => $email], "WHERE" => ["TOKEN" =>  explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS));
    }

    public function setSex ($sex) {
        $this->query(SQL::UPDATE(["SET" => ["SEX" => $sex], "WHERE" => ["TOKEN" =>  explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS));
    }

    public function getTemporaryTokenUser ($token) {
        $token_info = $this->query(SQL::SELECT(["GET" => ["*"], "WHERE" => ["TOKEN" =>  $token]], 0, T_T))[0];

        $dteStart = new DateTime($token_info["CREATED_D"]);
        $dteEnd   = new DateTime("now");

        $dteDiff  = $dteStart->diff($dteEnd);

        $difference = $dteDiff->format("%H:%I:%S");

        $ex_d = new DateTime($token_info['EXPIRATION']);
        $ex = $ex_d->format("H:i:s");

        if ($difference <= $ex) {
            return $token_info["USER"];
        }


        $this->deleteTemporaryToken($token);
        return null;
    }

    public function deleteTemporaryToken ($token) {
        $this->query(SQL::DELETE(["TOKEN" => $token], 0, T_T));
    }

    public function getMenu () {
        $user_access = $this->query(SQL::SELECT(["GET" => ["ACCESS"], "WHERE" => ["TOKEN" =>  explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS))[0]["ACCESS"];
        $menu_array = [];
        for ($i = 0; $i <= $user_access; $i++) {
            $menu_response = $this->query(SQL::SELECT(["GET" => ["*"], "WHERE" => ["ACCESS" => $i]], 0, USER_MENU));
            foreach ($menu_response as $menu) {
                $menu_array [] = $menu;
            }
        }
        return json_encode($menu_array);
    }

    public function sendEmailWithTemporaryToken ($email, $token) {
        $to  = $email ;

        $subject = "Update ".substr($token, 0, 5);

        $message = ' 
            <html> 
                <head> 
                    <title>Forget password?</title> 
                </head> 
                <body> 
                    <div style="margin-top: 10px; width: 125px; text-align: center; margin-left: auto; margin-right: auto;">
                      <p style="margin-left: calc(50% - 50px); background-color: #38863d; font-size: 20px; padding: 10px; color: white; font-weight: bolder; letter-spacing: 5px; padding-left: 15px">
                        TIC.S
                      </p>
                    </div>
                  
                  <div style="margin-top: 10px; text-align: center; font-size: 15px">
                      Hello, dear '.$to.'
                    </div>
                  
                  <div style="margin-top: 10px; text-align: center; font-size: 15px">
                      You send change password request.
                    </div>
                
                   <div style="margin-left: auto; margin-right: auto; margin-top: 50px;  width:200px; text-align: center; background-color: whitesmoke; padding: 20px;">
                        <a href="http://tickets-api.zzz.com.ua/user/forgetNewPassword/'.$token.'" style="height: 120px; width: 200px; text-decoration: none; color: darkgray; font-size: 20px">
                            Set new password
                        </a>
                    </div>
                    
                    <div style="margin-top: 10px; text-align: center; font-size: 15px">
                      Ignore, if you didn\'t send this.
                    </div>
                    
                    <div style="margin-top: 50px; text-align: center; font-size: 13px">
                      Doesn\'t share this link
                    </div>
                </body> 
            </html>';

        $headers  = "Content-type: text/html; charset=windows-1251 \r\n";
        $headers .= "From: TIC.S <tics@tickets-api.zzz.com.ua>\r\n";
        $headers .= "Bcc: tics@tickets-api.zzz.com.ua\r\n";

        if (mail($to, $subject, $message, $headers)) {
            echo "200 OK";
        } else {
            echo "SENDING ERROR";
        }

    }

    public function addTemporaryToken ($email, $token, $type) {
        $this->query(SQL::INSERT(["ID" => "0", "TOKEN" => $token, "CREATED_D" => date("Y-m-d H:i:s"), "EXPIRATION" => DEFAULT_EXPIRATION_TIME, "TYPE" => $type, "USER" => $email]));
    }

}