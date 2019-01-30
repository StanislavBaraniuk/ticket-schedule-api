<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 00:32
 */

class UserController extends Controller
{
    private $user_data;

    function __construct()
    {
        $this->model = $this->getModel(__CLASS__);
        $this->user_data = Parser::json();
    }

    function loginAction () {
        $this->authorization($this->user_data['login'],$this->user_data['password']);
    }

    function logoutAction () {
        Access::_RUN_(["authorization"]);
        $this->model->logout();
    }

    function setPasswordAction () {
        Access::_RUN_(["authorization"]);
        $this->model->setPassword($this->user_data['password']);
    }

    function setFIOAction () {
        Access::_RUN_(["authorization"]);
        $this->model->setFIO($this->user_data['first_name'], $this->user_data['last_name']);
    }

    function setPhoneAction () {
        Access::_RUN_(["authorization"]);
        $this->model->setPhone($this->user_data['phone']);
    }

    function setEmailAction () {
        Access::_RUN_(["authorization"]);
        $this->model->setEmail($this->user_data['email']);
    }

    function setSexAction () {
        Access::_RUN_(["authorization"]);
        $this->model->setSex($this->user_data['sex']);
    }

    function getMenuAction () {
        Access::_RUN_(["authorization"]);
        print_r( $this->model->getMenu() );
    }

    public function forgetSendAction () {
        $token = $this->generateToken();
        $this->model->sendEmailWithTemporaryToken($this->user_data["email"], $token);
        $this->model->addTemporaryToken($this->user_data["email"], $token, "password");
    }

    public function forgetNewPasswordAction($token) {
        $email = $this->model->getTemporaryTokenUser($token);
        if (!empty($email) && !empty($_POST["password"])) {
            $this->model->setFPassword($email, $_POST['password']);
            $this->model->deleteTemporaryToken($token);
            header("Location: ".FRONTEND_LINK);
        } else if (!empty($email)) {
            Component::show("newPassword");
        } else {
            echo "
            <div style='position: fixed; top: -15px; height: 100vh; width: 100vw; background-color: whitesmoke; padding: 20px; font-size: 20px'>
                <p>Link <a href='http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."'>".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."</a> unavailable already</p>
                <a href='https://ticket-schedule.herokuapp.com'>Go to home page</a>
            </div>
            ";
        }
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

        ResponseControl::generateError(401, "Authorization failed");
    }


}
