<?php
    require "../dbBroker.php";
    require "../model/predstava.php";
    

    if(isset($_POST['action']) && $_POST['action'] == 'update_repertoar') {

        $predstava=Predstava::getByName($_POST['predstava'],$conn)->fetch_array();
        
        $predstava_id=$predstava['id'];
        $reditelj=$_POST['reditelj'];
        $id=$_POST['id'];
        $vrstaPredstave=$_POST['vrsta'];
        $cena=$_POST['cena'];
        $datum=$_POST['datum'];

        $query = "UPDATE repertoar 
            SET predstavaId = '$predstava_id',
                reditelj = '$reditelj',
                vrstaPredstave = '$vrstaPredstave',
                datum = '$datum',
                cena = '$cena' 
            WHERE id = '$id'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
    }
?>