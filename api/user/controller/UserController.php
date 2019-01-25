<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 00:32
 */

class UserController extends Controller
{
    function __construct()
    {
        $this->model = $this->getModel(__CLASS__);
    }

    function loginAction () {
        $user_data = Parser::json();
        return $this->authorization($user_data['login'],$user_data['password']);
    }

    private function generateToken () {
        $token = '';

        for ($i = 0; $i < 30; $i++) {
            $symbol = [rand(48, 57), rand(97, 122), rand(65, 90)];

            $token .= chr($symbol[rand(0, 2)]);
        }

        return $token;
    }

    private function getToken () {
        do {
            $token = $this->generateToken();
            $check = !empty($this->model->checkTokenExisting($token)[0]["EMAIL"]);
        } while ($check);

        return $token;
    }

    private function setUserToken ($login, $token) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->model->setUserToken($login, $token);
        $this->model->setUserIP($login, $ip);
    }

    private function authorization ($login, $password) {
        $user_password = $this->model->getPassword($login);

        if (!empty($user_password)) {
            if ($password == $user_password) {
                $token = $this->getToken();
                $this->setUserToken($login, $token);
                echo $token;
                exit(0);
            }
        }

        trigger_error( "Authorization failed", E_USER_ERROR);
    }
}
