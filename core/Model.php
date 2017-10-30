<?php

class Model
{

    public function __construct(){
        $conn = Conn::getInstance();
        $conn = $conn->getConnection();
        return $conn;
    }


}