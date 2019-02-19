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
        $is_user_exist = count($this->query(SQL::SELECT(["GET" => ["ID"], "WHERE" => ['EMAIL' => $params["EMAIL"]]], 0, USERS))) > 0;
        if (!$is_user_exist) {
            $this->query(SQL::INSERT($params, 0, USERS));
            $token = TokenGenerator::generate();
            ( new UserModel )->sendEmailWithTemporaryToken( $params[ 'EMAIL' ] , $token );
            ( new UserModel )->addTemporaryToken( $params[ "EMAIL" ] , $token , "password" );
            ResponseControl::generateStatus( 200 , "OK" );
            return '';
        }
        ResponseControl::generateStatus( 409 , "User with same e-mail exist already" );
    }

    function delete ($params) {
        $this->query(SQL::DELETE(["USER_ID" => $params, "STATUS" => 1], 0, ORDERS));
        $this->query(SQL::DELETE(["ID" => $params], 0, USERS));

        ResponseControl::outputGet("");
    }

    function update ($params) {
        $this->query(SQL::UPDATE($params, 0, USERS));

        ResponseControl::outputGet("");
    }

    function get () {
        $users_data = $this->query(SQL::SELECT(["GET"=> ["*"]], 0, USERS));

        $parameters_to_hiding = ["PASSWORD", "TOKEN", "IP"];


        foreach ($users_data as $item) {
            foreach ($parameters_to_hiding as $param)
            if (isset($item[$param])) {
                unset($item[$param]);
            }
        }

        return $users_data;
    }

    function getById ($params) {
        return $this->query(SQL::SELECT(array("GET" => ["ID","FIRST_NAME","LAST_NAME","EMAIL","PASSWORD","PHONE","SEX","ONLINE","STATUS"], "WHERE" => ["ID" => $params]), 0, USERS));
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