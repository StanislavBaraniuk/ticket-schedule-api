<?php

Access::_USE_();


$db = new DB();
$ret = $db->query(SQL::SELECT(["GET" => ["VALUE"], "WHERE" => ["TITLE" => "is_active"]], 0, SETTING));

http_response_code(503);

return [$ret[0]["VALUE"], "Site is offline"];