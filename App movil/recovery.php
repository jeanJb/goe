<?php
session_start();
/*if(isset($_SESSION['documento'])){
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
    <link rel="icon" href="IMG/logos/goe03.png" type="image/png">
    <title>GOE</title>
</head>
<body>
    <nav>
        <form action="recoveryproceso.php" method="POST">
            <div class="log">
                <img src="IMG//user.png" alt="">
                <h1>Recuperar Contraseña</h1>
                <br>
                <h3>Documento</h3>
                <input type="text" name="txtemail" id="" required>
                <br>   
                <br>    
                <button class="button width-button" type="submit">Recuperar</button>
                <br>
                <br>
                <a href="go.php">Iniciar Sesion</a>
            </div>
        </form>
    </nav>
    <div class="img">
        <div class="ribbon"></div>
    </div>
</body>
</html>