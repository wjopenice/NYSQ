<?php

use Yaf\Application;
class messageModel
{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function getList($data)
    {
        $result = $this->db
            ->field("id,content,is_see")
            ->table("ny_message")
            ->where("user_id = ".$data['user_id'])
            ->order()
            ->select();
        return $result;
    }
}
