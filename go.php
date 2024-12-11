<?php
/* session_start();
if(isset($_SESSION['documento'])){
    header('Location:index.html');

} */

header("Cache-Control: no-cache, no-store, must-revalidate");
header('Pragma:no-cache');
header("Expires:0");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="STYLES/index.css">
    <script src="JS/validaciones.js"></script>
    <title>Document</title>
</head>
<body>
    <nav>
        <a href="index.html" class="atras">ATRAS</a>
        <form action="loginproceso.php" method="POST">
            <div class="log">
                <img src="IMG//user.svg" alt="">
                <h1>INICIAR SESION</h1>
                <br>
                <h3>Documento</h3>
                <input type="text" name="txtdoc" id="" required>
                <br>
                <h3>Contraseña</h3>
                <input type="password" name="txtpass" id="" required>
                <br>    
                <br>    
                <button class="button" type="submit">INICIAR</button>
                
                <br>
                <br>
                <a href="recuperar.php">Recuperar Contraseña</a>
            </div>
        </form>
    </nav>
    <div class="img">
        <div class="ribbon"></div>
    </div>
    <?php
    if (isset($_GET['error']) ){
    echo "<script> 
    Swal.fire({
        icon: 'error',
        title: 'Inicio no válido',
        text: 'Usuario o contraseña son incorrectos, intenta nuevamente',
        background: '#f7f7f7',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Volver a intentar',
        customClass: {
            title:'swal-title',
            popup: 'swal-popup',
        }
    });
    </script>";
    }

    ?>
</body>
</html>