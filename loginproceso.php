<?php
session_start();
/* if(isset($_SESSION['documento'])){
    header('Location: index.php');
    exit();  
    } */

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header('Pragma: no-cache');
    header("Expires:0");

require 'Utilidades/conexion.php';
$cnn= Conexion::getConexion();
$doc= $_POST['txtdoc'];
$clave= $_POST['txtpass'];
$sentencia = $cnn->prepare("SELECT *, AES_DECRYPT(clave, 'SENA') as clave FROM usuario WHERE documento = ?;");
$sentencia->execute([$doc]);
$valor = $sentencia->fetch(PDO::FETCH_OBJ);

if ($valor === FALSE){
    // Si no encuentra el documento, redirige con error
    header('Location:go.php?error=1'); // Error 1: documento no encontrado
} else {
    // Verificamos si la contraseña coincide
    if ($clave === $valor->clave) {
        // Si la contraseña es correcta, iniciar sesión
        $_SESSION['documento'] = $valor->documento;
        $_SESSION['id_rol'] = $valor->id_rol;

        // Redirigimos dependiendo del rol del usuario
        switch ($valor->id_rol) {
            case 101:
                header('Location: ModEstudiante/home.php');
                $_SESSION['documento'] = $valor->documento;
                break;
            case 102:
                header('Location: ModDocente/home.html');
                $_SESSION['documento'] = $valor->documento;
                break;
            case 104:
                header('Location: home.html');
                $_SESSION['documento'] = $valor->documento;
                break;
            default:
                // Si el rol no es reconocido, redirigimos con error
                header('Location: go.php?error=3'); // Error 3: Rol no válido o no existe
                break;
        }
        exit();
    } else {
        // Si la contraseña no coincide
        header('Location: go.php?error=2'); // Error 2: contraseña incorrecta
    }
}

/* if($valor ===FALSE){
    header('Location:go.php?error=1');
}else if($sentencia->rowcount()==1){
    $_SESSION['documento'] =$valor->documento;
    header('Location:home.php');
    exit();
} */


?>