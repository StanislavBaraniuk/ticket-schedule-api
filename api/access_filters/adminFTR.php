<?php

Access::_USE_();

$access_lvl = (new DB)->query(SQL::SELECT(["GET" => ['ACCESS'], "WHERE" => ["TOKEN" => explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS))[0]["ACCESS"];

return [!empty($access_lvl) && $access_lvl > 1, "Permission denied", 423];