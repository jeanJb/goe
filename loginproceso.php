<?php
session_start();
if(isset($_SESSION['nombre'])){
    header('Location: home.html');
    exit();  
    }

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header('Pragma: no-cache');
    header("Expires:0");

require 'Utilidades/conexion.php';
$cnn= Conexion::getConexion();
$usu= $_POST['txtUsu'];
$contra= $_POST['txtPass'];
$sentencia= $cnn->prepare("SELECT * FROM t_usuario WHERE nombres =? and password_usu =?;");
$sentencia->execute([$usu, $contra]);
$valor= $sentencia->fetch(PDO::FETCH_OBJ);

if($valor ===FALSE){
    header('Location:login.php?error=1');
}else if($sentencia->rowcount()==1){
    $_SESSION['nombre'] =$valor->nombres;
    header('Location:home.html');
    exit();
}


?>
