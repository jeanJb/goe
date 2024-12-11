<?php
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/ObservadorDto.php';
require '../Utilidades/conexion.php';

//usuario
if(isset($_POST['registro'])){
    $uDao = new UsuarioDao();
    $uDto = new ObservadorDto();
    $uDto->setIDObservador($_POST['idobservador']);
    $uDto->setDocumento($_POST['documento']);
    $uDto->setFecha($_POST['fecha']);
    $uDto->setDescripcion_falta($_POST['descripcion_falta']);
    $uDto->setCompromiso($_POST['compromiso']);
    $uDto->setFirma($_POST['firma']);
    $uDto->setSeguimiento($_POST['seguimiento']);
    $uDto->setFalta($_POST['falta']);

    $mensaje = $uDao->registrarObservador($uDto);

    header("Location:../observadores.php?mensaje=".$mensaje);

}
else if ($_GET['id']!=NULL){
    $uDao = new UsuarioDao();
    $mensaje = $uDao->eliminarObservador($_GET['id']);
    header("Location:../observadores.php?mensaje=".$mensaje);

}else if (isset($_POST['actualizar'])){
    $uDao = new UsuarioDao();
    $uDto = new ObservadorDto();
    $uDto->setIDObservador($_POST['idobservador']);
    $uDto->setDocumento($_POST['documento']?? null);
    $uDto->setFecha($_POST['fecha']);
    $uDto->setDescripcion_falta($_POST['descripcion_falta']);
    $uDto->setCompromiso($_POST['compromiso']);
    $uDto->setFirma($_POST['firma']?? null);
    $uDto->setSeguimiento($_POST['seguimiento']);
    $uDto->setFalta($_POST['falta']);

    $mensaje =$uDao->modificarObservador($uDto);
    header("Location:../observadores.php?mensaje=".$mensaje);
}


?>