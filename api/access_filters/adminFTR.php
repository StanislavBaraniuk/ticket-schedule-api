<?php

Access::_USE_();

$access_lvl = (new DB)->query(SQL::SELECT(["GET" => ['ACCESS'], "WHERE" => ["TOKEN" => explode(" ",apache_request_headers()["Authorization"])[1]]], 0, USERS))[0]["ACCESS"];

http_response_code(423);

return [!empty($access_lvl) && $access_lvl == 2, "Permission denied"];