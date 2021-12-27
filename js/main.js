$('#dodajForm').submit(function(){
    
    event.preventDefault();
    console.log("Dodavanje");
    
    const $form =$(this);
    
    const $input = $form.find('input, select, button, textarea');
    
    const serijalizacija = $form.serialize();
    console.log(serijalizacija);
    
    $input.prop('disabled', true);

    req = $.ajax({
        url: 'handler/add.php',
        type:'post',
        data: serijalizacija
    });
    
    req.done(function(res, textStatus, jqXHR){
        
        if(res=="Success"){
            Swal.fire( {

                title: 'Zakazana!',
                text: 'Predstava je uspešno zakazana.',
                confirmButtonColor: 'rgb(255, 142, 37)',
                icon: 'success',
              }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(true);
                }
                }
            )
        }else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Predstava nije dodata.',
              })
        }
        
    });

    req.fail(function(jqXHR, textStatus, errorThrown){
        console.error('Sledeca greska se desila: '+textStatus, errorThrown)
    });
});

$('#btn-pretraga').click(function () {

    var para = document.querySelector('#pretraga-input');
    
    var style = window.getComputedStyle(para);
    
    if (!(style.display === 'inline-block') || ($('#pretraga-input').css("visibility") ==  "hidden")) {
        
        $('#pretraga-input').show();
        document.querySelector("#pretraga-input").style.visibility = "";
    } else {
       document.querySelector("#pretraga-input").style.visibility = "hidden";
    }
});