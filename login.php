
<?php
session_start();
if(isset($_SESSION['nombre'])){
    header('Location.home.html');

}

header("Cache-Control: no-cache, no-store, must-revalidate");
header('Pragma:no-cache');
header("Expires:0");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Login</title>
    <style>
        
        *{
    text-decoration: none;
    list-style: none;
    padding: 50;
    margin: 0;
    border: 0;
    box-sizing: border-box;
    color: white;
}

    label {
        font-size: 17px;
    }

    .fondoform {
        background-color: #454545;
        background-image: url('img/mora.png '); /* Ruta de la imagen */
            background-size: cover; /* Ajusta la imagen al tamaño del contenedor */
            background-position: center; /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            padding: 20px; /* Espacio interno */
            border-radius: 15px; /* Bordes redondeados */
        }
    
.container{
        heigth: 100%;
        width: 100%;}
    .bg {
        height: 50%
        width: 50%;
        background-position: center;
       
            background-image: url('img/morado.png'); /* Cambia la ruta a la imagen que quieres usar */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
    }
    
input[type="text"], input[type="password"]{
    width: 54%;
    height: 2.8vh;
    border-radius: 10px;
    border:  1px solid white;
    background-color: #222831;
    backdrop-filter: blur(200px);
}


.button{
    width: auto;
    padding: 10px 100px;
    border-radius: 10px;
    background-color: #892CDC;
    margin: 1% 1%;
}

form .log img{
    width: 25%;
}




</style>  

   

</head>

     


            
                        
                    
                       
                        
                        

<body class="bg"> 
     
    <div class="container">
        <center>
            <div class="row justify-content-around mt-5" >
                <div class="col-md-4 mt-5 fondoform pb-3 rounded" >
               
                <form action="loginproceso.php" method="POST">
                    
        <form action="">
            <div class="log">
                <img src="IMG//user.svg" alt="">
                <h1>INICIAR SESION</h1>
                <br>
                
            <form action="loginproceso.php" method="POST">
                        
                    
                       
                        
                        <div class=" form-group mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-file-earmark-person-fill" viewBox="0 0 16 16">
  <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0m2 5.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-.245S4 12 8 12s5 1.755 5 1.755"/>
</svg>
                            <label for="" class="font-weight-bold text-white">Usuario</label>
                            <input type="text" name="txtUsu" class="form-control">
                        </div>
            
                        <div class=" form-group mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-file-earmark-lock2-fill" viewBox="0 0 16 16">
  <path d="M7 7a1 1 0 0 1 2 0v1H7z"/>
  <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M10 7v1.076c.54.166 1 .597 1 1.224v2.4c0 .816-.781 1.3-1.5 1.3h-3c-.719 0-1.5-.484-1.5-1.3V9.3c0-.627.46-1.058 1-1.224V7a2 2 0 1 1 4 0"/>
</svg>
                            <label for="" class="font-weight-bold text-white">Contraseña</label>
                            <input type="password" name="txtPass" class="form-control">
                        </div>
                       <button class="button" type="submit" value="Iniciar Sesión">INICIAR</button>
                     </form>
                </div>
            </div>
        </center>
    </div>

    <script> 
    Swal.fire({
        icon: 'error',
        title: 'Inicio no válido',
        text: 'Usuario o contraseña son incorrectos, intenta nuevamente',
        background: '#c7dbff',
        confirmButtonColor: '#000000',
         confirmButtonText: 'Volver a intentar',
        customClass: {
            title:'swal-title',
            popup: 'swal-popup',
        }
    });
    </script>


<script src="js/bootstrap.min.js"></script>
</body>

</html>
        
