<?php

require "dbBroker.php";
require "model/user.php";

session_start();
if(isset($_POST['username']) && isset($_POST['password'])){
    $uname = $_POST['username'];
    $upass = $_POST['password'];

    // $conn = new mysqli() /// pregazena konekcija iz dbBrokera;
    $korisnik = new User(1, $uname, $upass);
    // $odg = $korisnik->logInUser($uname, $upass, $conn);
    $odg = User::logInUser($korisnik, $conn); //pristup statickim funkcijama preko klase

    
    if($odg->num_rows==1){
      
        $_SESSION['user_id'] = $korisnik->id;
        header('Location: home.php');
        exit();
    }else{
        
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>POZORISTE NA TERAZIJAMA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="login-form">
        <div class="main-div">
            <form method="POST" action="#">
                <div class="container">
                    <label class="username">KORISNICKO IME</label>
                    <input type="text" name="username" class="form-control"  required>
                    <br>
                    <label for="password">LOZINKA</label>
                    <input type="password" name="password" class="form-control" required>
                    <button type="submit" class="btn btn-primary" name="submit">PRIJAVI SE</button>
                </div>

            </form>
        </div>

        
    </div>
    <!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>