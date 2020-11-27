<?php
use Yaf\Application;
class tagModel{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function sysList(){
        $result = $this->db
            ->field("id,name")
            ->table("ny_tag")
            ->where("type = 1")
            ->select();
        return $result;
    }

    public function mercList(){
        $result = $this->db
            ->field("id,name")
            ->table("ny_tag")
            ->where("type = 2")
            ->select();
        return $result;
    }

    public function shopList(){
        $result = $this->db
            ->field("id,name")
            ->table("ny_tag")
            ->where("type = 3")
            ->select();
        return $result;
    }
}
