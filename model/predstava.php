<?php

class Predstava{
    public $id;
    public $naziv;

    public function __construct($id=null,$naziv=null)
    {
        $this->id = $id;
        $this->naziv=$naziv;
    }

    public static function getById($id, mysqli $conn){
        $query = "SELECT * FROM predstava WHERE id=$id";
        
        return $conn->query($query);

    }

    public static function getAll(mysqli $conn) {
        
        $query = "SELECT * FROM predstava";
        return $conn->query($query);
        
    }
    public static function getByName($ime, mysqli $conn) {
        $query = "SELECT * FROM predstava WHERE naziv='$ime'";
        return $conn->query($query);
    }

}
?>