<?php

Access::_USE_();

/**
 * @return bool
 */

$response_ip = $_SERVER['REMOTE_ADDR'];
$user_ip = (new DB)->query(SQL::SELECT(["GET" => ['IP'], "WHERE" => ["TOKEN" => explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS))[0]["IP"];


return [!empty($user_ip) && $user_ip == $response_ip, "Permission denied"];