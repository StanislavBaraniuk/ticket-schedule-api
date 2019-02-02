<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/30/19
 * Time: 09:47
 */

class ResponseControl {
    public static function outputGet ($data, $info = ["code" => 302, "message" => "Nothing to output"]) {
        header("Content-Type: application/json; charset=UTF-8");
        if (count($data) > 0) {
            echo json_encode($data);
        } else {
            http_response_code($info["code"]);
            echo $info["message"];
        }
    }

    public static function generateStatus ($code = 302, $message = "Nothing to output") {
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($code);
        echo $message;
    }
}