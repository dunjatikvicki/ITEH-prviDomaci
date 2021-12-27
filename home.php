<?php

require "dbBroker.php";
require "model/predstava.php";
require "model/repertoar.php";
require "model/user.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$podaci = Repertoar::getAll($conn);
if (!$podaci) {
    echo "Nastala je greška pri preuzimanju podataka";
    die();
}
if ($podaci->num_rows == 0) {
    echo "Nema unetih ocena.";
    die();
} else {

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/home.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>Pocetna</title>
</head>
<body>
<div class="jumbotron" style="color:rgb(201, 0, 0) ;">
        <h1>POZORIŠTE NA TERAZIJAMA</h1>
    </div>

    <div class="row" style="background-color: rgba(225, 225, 208, 0.5);">
        
        <div class="col-md-4">
            <button id="btn-dodaj" type="button" class="btn btn-success" style="background-color: rgb(189, 162, 96); border: 1px red;" data-toggle="modal" data-target="#myModal"> ZAKAZI PREDSTAVU</button>

        </div>
        <div class="col-md-4">
            <button id="btn-pretraga" class="btn btn-warning btn-block" style="background-color:  rgb(189, 162, 96); border: 1px red;"> PRETRAZI PREDSTAVU</button>
            <input type="text" id="pretraga-input" onkeyup="funkcijaZaPretragu()" placeholder="Pretrazi predstave po vrsti" hidden>
        </div>
    </div>

    <div id="pregled" class="panel panel-success" style="margin-top: 1%;">

        <div class="panel-body">
            <table id="myTable" class="table " style="color: white; text-align: center;">
                <thead class="thead" style="text-align: center;">
                    <tr>
                        <th scope="col">PREDSTAVA</th>
                        <th scope="col">REDITELJ</th>
                        <th scope="col">VRSTA PREDSTAVE</th>
                        <th scope="col">DATUM PREMIJERE</th>
                        <th scope="col">CENA KARTE</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    while ($red = $podaci->fetch_array()) {
                        $predstava=Predstava::getById($red["predstavaId"],$conn)->fetch_array();
                ?>
                        <tr>
                            <td data-id="id" data-target="predstava"><?php echo $predstava["naziv"] ?></td>
                            <td data-target="reditelj"><?php echo $red["reditelj"] ?></td>
                            <td data-target="vrstaPredstave"><?php echo $red["vrstaPredstave"] ?></td>
                            <td data-target="datumPremijere"><?php echo $red["datum"] ?></td>
                            <td data-target="cena"><?php echo $red["cena"] ?></td>
                            <td>
                            <button id="<?php echo $red["id"] ?>" class="btn btn-warning editBtn" data-toggle="modal" data-target="#izmeniModal">Izmeni</button>
                            <button id="<?php echo $red["id"] ?>" formmethod="post" name="obrisi" class="btn btn-danger deleteBtn" >Obrisi</button>
                            </td>

                        </tr>
                <?php
                    }
                } 
                ?>

                </tbody>
                </table>
                <div class="col" style="text-align: center;">
                    <button id="btn-sortiraj" class="btn" onclick="sortiranje()">Sortiraj</button>
            </div>
        </div>
    </div>
    

    <!-- -----------------MODAL DODAJ---------------------------------- -->

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style="width: 565px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container prijava-form">
                        <form action="#" method="post" id="dodajForm">
                            <h2 style="color: black; text-align: center; width: 500px;">Zakazi predstavu</h2>
                            <div class="row" style="color: black;">
                                <div class="col-md-8">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Predstava</label>
                                            <select name="predstava" style="border: 1px solid black" class="form-control">
                                                <?php
                                                    $predstave=Predstava::getAll($conn);
                                                    while($pred=$predstave->fetch_array()) {
                                                ?>
                                                    <option><?php echo $pred["naziv"]; ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Reditelj</label>
                                            <input type="text" style="border: 1px solid black" name="reditelj" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Vrsta predstave</label>
                                            <input type="text" style="border: 1px solid black;" name="vrsta" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Datum premijere</label>
                                            <input type="date" style="border: 1px solid black;" name="datum" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Cena karte</label>

                                            <input type="number" min="1" value="" style="border: 1px solid black;" name="cena" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for=""></label>
                                            <input type="hidden" id="izmenaId" />
                                            <button id="btnDodaj" type="submit" class="btn btn-success btn-block" style="background-color: orange; border: 1px solid black;">Zakazi</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ----------------------MODAL IZMENI------------------------ -->
    
    <div  id="izmeniModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style="width: 565px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container prijava-form">
                        <form action="#" id="form_izmena" method="post" >
                            <h2 style="color: black; text-align: center; width: 400px;">Izmena ocene</h2>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Predstava</label>
                                            <select id="Predstava" name="predstava" style="border: 1px solid black" class="form-control">
                                                <?php
                                                    $predstave=Predstava::getAll($conn);
                                                    while($pred=$predstave->fetch_array()) {
                                                ?>
                                                    <option><?php echo $pred["naziv"]; ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Reditelj</label>
                                            <input type="text" id="Reditelj" style="border: 1px solid black" name="reditelj" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Vrsta predstave</label>
                                            <input type="text" id="Vrsta" style="border: 1px solid black;" name="vrsta" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Datum premijere</label>
                                            <input type="date" id="Datum" style="border: 1px solid black;" name="datum" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Cena karte</label>

                                            <input type="number" min="1" id="Cena"   style="border: 1px solid black;" name="cena" class="form-control" />
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for=""></label>
                                            <button id="btnIzmeni" name="action" type="submit" class="btn btn-success btn-block" style="background-color: orange; border: 1px solid black; font-size: large;">Izmeni</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/main.js"></script>

    <script type="text/javascript">

        $("#btnIzmeni").click(function() {
            event.preventDefault();
            var Id = +$('#izmenaId').val();
            $.ajax({
                url: 'handler/update.php',
                type:'post',
                data: $("#form_izmena").serialize()+'&action=update_repertoar&id=' + Id});
                Swal.fire( {
                            title: 'Izmenjen!',
                            text: 'Repertoar je uspešno izmenjen.',
                            confirmButtonColor: 'rgb(255, 142, 37)',
                            icon: 'success',
                            confirmButtonText: 'OK',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(true);
                    }
                })
        });

        $("body").on("click",".editBtn", function(e) {
            var Id=+this.id;
            var predstava = $(this).closest('tr').children('td[data-target=predstava]').text();
            var reditelj = $(this).closest('tr').children('td[data-target=reditelj]').text();
            var vrstaPredstave = $(this).closest('tr').children('td[data-target=vrstaPredstave]').text();
            var datum = $(this).closest('tr').children('td[data-target=datumPremijere]').text();
            var cena = $(this).closest('tr').children('td[data-target=cena]').text();
                    
            $('#Predstava').val(predstava);
            $('#Reditelj').val(reditelj);
            $('#Vrsta').val(vrstaPredstave);
            $('#Cena').val(cena);
            $('#Datum').val(datum);
            $('#izmenaId').val(Id);
        });


        $(".deleteBtn").click( function (e) {
    e.preventDefault();
          var element = e.target;
          del_id = +e.target.id;
        Swal.fire({
            title: 'Da li ste sigurni?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'rgb(255, 142, 37)',
            cancelButtonColor: 'rgb(255, 47, 75)',
            confirmButtonText: 'Obriši',
            cancelButtonText: 'Otkaži'
        }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: 'handler/delete.php',
                method: 'post',
                data: {
                  'id': del_id
                },
                success: function(response) {
                  Swal.fire( {

                    title: 'Obrisana!',
                    text: 'Ocena je uspešno obrisana.',
                    confirmButtonColor: 'rgb(255, 142, 37)',
                    icon: 'success',
                  })
                  element.closest('tr').remove();
                }
              });
            }
        })
    });
        function sortiranje() {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("myTable");
            switching = true;

            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[1];
                    y = rows[i + 1].getElementsByTagName("TD")[1];
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }


        function funkcijaZaPretragu() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("pretraga-input");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

    </script>

</body>
</html>