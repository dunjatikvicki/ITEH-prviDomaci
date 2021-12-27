<?php
    class Repertoar{
        public $id;   
        public $predstavaId;
        public $reditelj;  
        public $vrstaPredstave;   
        public $datum;
        public $cena;
        
        public function __construct($id=null, $predstavaId=null, $reditelj=null,$vrstaPredstave=null, $datum=null, $cena=null)
        {
            $this->id = $id;
            $this->predstavaId = $predstavaId;
            $this->reditelj=$reditelj;
            $this->vrstaPredstave=$vrstaPredstave;
            $this->datum = $datum;
            $this->cena = $cena;
        }

        public static function getAll(mysqli $conn)
        {
            $query = "SELECT * FROM repertoar";
            return $conn->query($query);
        }

        public function deleteById(mysqli $conn)
        {
            $query = "DELETE FROM repertoar WHERE id=$this->id";
            return $conn->query($query);
        }
        public static function add(Repertoar $repertoar, mysqli $conn)
        {
            $query = "INSERT INTO repertoar(predstavaId, reditelj, vrstaPredstave, datum, cena) VALUES('$repertoar->predstavaId','$repertoar->reditelj','$repertoar->vrstaPredstave','$repertoar->datum','$repertoar->cena')";
            return $conn->query($query);
        }


    }
?>