<?php

include_once("Config.php");

class Conn
{
    private $connection;

    private static $instance = null;

    public static function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
    private function __clone() {}

    private function __construct() {
        $dsn =  Config::$db_type .
            ':host=' . Config::$db_host .
            ';dbname=' . Config::$db_name.
            ';port=' . Config::$db_port .
            ';charset=utf8';
        $user = Config::$db_user;
        $password = Config::$db_password;

        $this->connection = new PDO($dsn, $user, $password);
    }

    public function getConnection() {
        return $this->connection;
    }

}