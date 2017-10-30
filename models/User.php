<?php

class User extends Model
{
    private $name;
    private $email;
    private $territory;

    public function __construct($name,$email,$territory){
        $this->conn = parent::__construct();
        $this->name = $name;
        $this->email = $email;
        $this->territory = $territory;
    }

    public function save(){
        $sql = "SELECT id, name, email, territory FROM users WHERE email ='{$this->getEmail()}'";
        $stmt = $this->conn->query($sql);
        if ($stmt->fetchColumn() == 0) {
            $stmt = $this->conn->prepare("INSERT INTO users (name, email, territory) VALUES (:name, :email, :territory)");
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':territory',$this->territory);
            $stmt->execute();
            return (['name'=>$this->getName()]);
        } else {
            return (['email'=>$this->getEmail()]);
        }
    }

    public function getEmail(){
        return $this->email;
    }

    public function getName(){
        return $this->name;
    }
}