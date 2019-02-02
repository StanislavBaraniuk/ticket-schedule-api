<?php

Access::_USE_();

/**
 * @return bool
 */

$user_ip = $_SERVER['REMOTE_ADDR'];
$db_user_ip = (new DB)->query(SQL::SELECT(["GET" => ['IP'], "WHERE" => ["TOKEN" => Parser::getBearerToken()]], 0, USERS))[0]["IP"];

return [!empty($db_user_ip) && $user_ip == $db_user_ip, "Need authorization", 401];