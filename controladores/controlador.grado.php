<?php
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/UsuarioDto.php';
require '../Utilidades/conexion.php';

/* var_dump($_POST);
die(); */
//usuario

/* if(isset($_POST['registro'])){
$uDao = new UsuarioDao();
$uDto = new UsuarioDto();
$uDto->setDocumento($_POST['documento']);
$uDto->setRol($_POST['id_rol']);
$uDto->setEmail($_POST['email']);
$uDto->setClave($_POST['clave']);
$uDto->setTD($_POST['tipo_doc']);
$uDto->setNombre1($_POST['nombre1']);
$uDto->setNombre2($_POST['nombre2']);
$uDto->setApellido1($_POST['apellido1']);
$uDto->setApellido2($_POST['apellido2']);
$uDto->setTelefono($_POST['telefono']);
$uDto->setDireccion($_POST['direccion']);
$uDto->setFoto($_POST['foto'] ?? null);
$uDto->setGrado($_POST['grado'] ?? null);
$mensaje = $uDao->registrarUsuario($uDto);

header("Location:../intro.php?mensaje=".$mensaje);

}
else if ($_GET['id']!=NULL){
    $uDao = new UsuarioDao();
    $mensaje = $uDao->eliminarUsuario($_GET['id']);
    header("Location:perfil.php?mensaje=".$mensaje);

}else  */if (isset($_POST['actualizar'])){
    $uDao = new UsuarioDao();
    $uDto = new UsuarioDto();
    
    $uDto->setDocumento($_POST['documento']);
    $uDto->setGrado($_POST['grado']);

    $mensaje =$uDao->modificarGrado($uDto);
    header("Location:../curso_doc.php?mensaje=".$mensaje);
} elseif (isset($_POST['update'])){
    $uDao = new UsuarioDao();
    
    $grado=$_POST['grado'];
    $salon=$_POST['salon'];

    $mensaje =$uDao->modificarCurso($grado, $salon);
    header("Location:../curso_doc.php?mensaje=".$mensaje);
}

?>