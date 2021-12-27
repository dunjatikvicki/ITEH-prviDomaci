<?php

require "../dbBroker.php";
require "../model/predstava.php";
require "../model/repertoar.php";


if(isset($_POST['predstava']) && isset($_POST['reditelj']) 
&& isset($_POST['vrsta']) && isset($_POST['datum']) && isset($_POST['cena'])){

    
    
    $predstava=Predstava::getByName($_POST['predstava'],$conn)->fetch_array();
    $repertoar = new Repertoar(null,$predstava['id'],$_POST['reditelj'],$_POST['vrsta'],$_POST['datum'],$_POST['cena'] );
    
    $status = Repertoar::add($repertoar, $conn);

    if($status){
        echo "Success";
    }else{
        echo $status;
        echo "Failed";
    }
}
?>