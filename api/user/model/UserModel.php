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
}