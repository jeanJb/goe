<?php
session_start();
if(isset($_SESSION['nombre'])){
    header('Location: index.php');
    exit();  
    }

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header('Pragma: no-cache');
    header("Expires:0");

require 'Utilidades/conexion.php';
$cnn= Conexion::getConexion();
$email= $_POST['txtemail'];
$clave= $_POST['txtpass'];
$sentencia= $cnn->prepare("SELECT * FROM usuario WHERE email =? and clave =?;");
$sentencia->execute([$email, $clave]);
$valor= $sentencia->fetch(PDO::FETCH_OBJ);

if($valor ===FALSE){
    header('Location:go.php?error=1');
}else if($sentencia->rowcount()==1){
    $_SESSION['email'] =$valor->email;
    header('Location:home.php');
    exit();
}


?>