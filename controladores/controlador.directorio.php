<?php
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/DirectorioDto.php';
require '../Utilidades/conexion.php';

//usuario

if(isset($_POST['registro'])){
$uDao = new UsuarioDao();
$uDto = new DirectorioDto();
$uDto->setIDDetalle($_POST['id_detalle']);
$uDto->setDocumento($_POST['documento']);
$uDto->setRh_estudiante($_POST['rh_estudiante']);
$uDto->setEps($_POST['eps']);
$uDto->setFecha_naci($_POST['fecha_naci']);
$uDto->setEnfermedades($_POST['enfermedades']);
$uDto->setNom_acu($_POST['nom_acu']);
$uDto->setTelefono_acu($_POST['telefono_acu']);
$uDto->setDoc_acu($_POST['doc_acu']);
$mensaje = $uDao->registrarUsuario($uDto);

header("Location:../registro.php?mensaje=".$mensaje);

}
else if ($_GET['id']!=NULL){
    $uDao = new UsuarioDao();
    $mensaje = $uDao->eliminarUsuario($_GET['id']);
    header("Location:perfil.php?mensaje=".$mensaje);

}else if (isset($_POST['actualizar'])){
    $uDao = new UsuarioDao();
    $uDto = new DirectorioDto();
    $uDto->setIDDetalle($_POST['id_detalle']);
    $uDto->setDocumento($_POST['documento']);
    $uDto->setRh_estudiante($_POST['rh_estudiante']);
    $uDto->setEps($_POST['eps']);
    $uDto->setFecha_naci($_POST['fecha_naci']);
    $uDto->setEnfermedades($_POST['enfermedades']);
    $uDto->setNom_acu($_POST['nom_acu']);
    $uDto->setTelefono_acu($_POST['telefono_acu']);
    $uDto->setDoc_acu($_POST['doc_acu']);

    $mensaje =$uDao->modificarDirectorio($uDto);
    header("Location:../ModEstudiante/perfil.php?mensaje=".$mensaje);
}


?>