<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/24/19
 * Time: 23:23
 */

class UserModel extends Model
{
    private static $token;

    function __construct()
    {
        self::$token = Parser::getBearerToken();
    }

    public function checkTokenExisting ($token) {
        return $this->query(SQL::SELECT(["GET" => ["EMAIL"], "WHERE" => ["TOKEN" => $token]], 0, USERS));
    }

    public function getPassword ($login) {
        return $this->query(SQL::SELECT(["GET" => ["PASSWORD"], "WHERE" => ["EMAIL" => $login]], 0, USERS))[0]["PASSWORD"];
    }

    public function setUserTokenIP ($email, $token, $ip) {
        $this->query(SQL::UPDATE(["SET" => ["TOKEN" => $token, "IP" => $ip, "ONLINE" => 1], "WHERE" => ["EMAIL" => $email]], 0, USERS));
    }

    public function logout () {
        $this->query(SQL::UPDATE(["SET" => ["IP" => "","TOKEN" => "","ONLINE" => 0], "WHERE" => ["TOKEN" =>  self::$token]], 0, USERS));
    }

    public function setPassword ($password) {
        $this->query(SQL::UPDATE(["SET" => ["PASSWORD" => $password], "WHERE" => ["TOKEN" =>  self::$token]], 0, USERS));
    }

    public function setFPassword ($email, $password) {
        $this->query(SQL::UPDATE(["SET" => ["PASSWORD" => $password], "WHERE" => ["EMAIL" => $email]], 0, USERS));
    }

    public function setFIO ($f, $l) {
        $this->query(SQL::UPDATE(["SET" => ["FIRST_NAME" => $f, "LAST_NAME" => $l], "WHERE" => ["TOKEN" =>  self::$token]], 0, USERS));
    }

    public function setPhone ($phone) {
        $this->query(SQL::UPDATE(["SET" => ["PHONE" => $phone], "WHERE" => ["TOKEN" =>  self::$token]], 0, USERS));
    }

    public function setEmail ($email) {
        $this->query(SQL::UPDATE(["SET" => ["EMAIL" => $email], "WHERE" => ["TOKEN" =>  self::$token]], 0, USERS));
    }

    public function setSex ($sex) {
        $this->query(SQL::UPDATE(["SET" => ["SEX" => $sex], "WHERE" => ["TOKEN" =>  self::$token]], 0, USERS));
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
        $menu_will_return = [];

        $input_token = isset(self::$token) ?
            self::$token : false;

        $user_ip = $_SERVER['REMOTE_ADDR'];
        $db_user_ip = (new DB)->query(
            SQL::SELECT(["GET" => ['IP'], "WHERE" => ["TOKEN" => self::$token]], 0, USERS)
        )[0]["IP"];

        if ($input_token && !empty($db_user_ip) && $user_ip == $db_user_ip) {
            $user_access = $this->query(
                SQL::SELECT(["GET" => ["ACCESS"], "WHERE" => ["TOKEN" =>  $input_token]], 0, USERS)
            )[0]["ACCESS"];
        } else {
            $user_access = 0;
        }

        $db_menu = $this->query(SQL::SELECT(["GET" => ["*"]], 0, USER_MENU));

        foreach ($db_menu as $menu) {
            $accesses = explode(" ", $menu["ACCESS"]);
            foreach ($accesses as $access) {
                if ($access == $user_access) {
                    array_push($menu_will_return, $menu);
                }
            }
        }

        return $menu_will_return;
    }

    public function getInfo () {
        $user_data = $this->query(SQL::SELECT(["GET" => ["*"], "WHERE" => ["TOKEN" =>  self::$token]], 0, USERS))[0];

        $parameters_to_hiding = ["PASSWORD", "TOKEN", "IP"];

        foreach ($parameters_to_hiding as $item) {
            if (isset($user_data[$item])) {
                unset($user_data[$item]);
            }
        }

        return $user_data;
    }

    public function sendEmailWithTemporaryToken ($email, $token) {
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
                      Hello, dear '.$email.'
                    </div>
                  
                  <div style="margin-top: 10px; text-align: center; font-size: 15px">
                      You send change password request.
                    </div>
                
                   <div style="margin-left: auto; margin-right: auto; margin-top: 50px;  width:200px; text-align: center; background-color: whitesmoke; padding: 20px;">
                        <a href="http://tickets-api.zzz.com.ua/user/forgetPasswordSetNew/'.$token.'" style="height: 120px; width: 200px; text-decoration: none; color: darkgray; font-size: 20px">
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

        EmailSender::send('tics@tickets-api.zzz.com.ua', $email, "TIC.S", $subject, $message);
    }

    public function addTemporaryToken ($email, $token, $type) {
        $this->query(
            SQL::INSERT(["ID" => "0", "TOKEN" => $token, "CREATED_D" => date("Y-m-d H:i:s"), "EXPIRATION" => DEFAULT_EXPIRATION_TIME, "TYPE" => $type, "USER" => $email])
        );
    }

    public function getToken () {
        do {
            $token = TokenGenerator::generate();
            $check = !empty($this->checkTokenExisting($token)[0]["EMAIL"]);
        } while ($check);

        return $token;
    }

    public function setUser ($login, $token) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->setUserTokenIP($login, $token, $ip);
    }

    public function authorization ($login, $password) {
        $user_password = $this->getPassword($login);

        if (!empty($user_password)) {
            if ($password == $user_password) {
                $token = $this->getToken();
                $this->setUser($login, $token);
                return $token;
            }
        }

        ResponseControl::generateStatus(401, "Authorization failed");
    }

    public function register($params) {
        $this->query(SQL::INSERT(["SET" => [$params]], 0, USERS));
        return $this->authorization($params["EMAIL"], $params["PASSWORD"]);
    }

    public function online ($online) {
        $this->query(SQL::UPDATE(["SET" => ["ONLINE" => $online], "WHERE" => ["TOKEN" => self::$token]], 0, USERS));
    }

    public function getTableColumns($params) {
        $all_tables_info = $this->query("SHOW COLUMNS FROM ".DB_NAME.'.'.$params);

        foreach ($all_tables_info as $item) {
            $cut_info[] = $item["Field"];
        }

        return $cut_info;
    }
}