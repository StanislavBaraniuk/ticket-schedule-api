<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 1/15/19
 * Time: 14:36
 */

class SiteModel extends Model
{
    function on () {
        $this->query(SQL::UPDATE(["SET" => ["VALUE" => 1], "WHERE" => ["TITLE" => "is_active"]], 0, SETTING));
    }

    function off () {
        $this->query(SQL::UPDATE(["SET" => ["VALUE" => 0], "WHERE" => ["TITLE" => "is_active"]], 0, SETTING));
    }
}