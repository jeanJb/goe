<?php
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/UsuarioDto.php';
require '../Utilidades/conexion.php';

if (isset($_POST['registro'])) {
    $uDao = new UsuarioDao();
    $uDto = new UsuarioDto();
    $uDto->setIdobservador($_POST['idobservador'] ?? null);
    $uDto->setDocumento($_POST['documento'] ?? null);
    $uDto->setFecha($_POST['fecha'] ?? null);
    $uDto->setDescripcionFalta($_POST['descripcion_falta'] ?? null);
    $uDto->setCompromiso($_POST['compromiso'] ?? null);
    $uDto->setFirma($_POST['firma'] ?? null);
    $uDto->setSeguimiento($_POST['seguimiento'] ?? null);
    $uDto->setFalta($_POST['falta'] ?? null);
    $mensaje = $uDao->registrarUsuario($uDto);

    header("Location:../registro.php?mensaje=" . urlencode($mensaje));
    exit();
} elseif (isset($_GET['id'])) {
    $uDao = new UsuarioDao();
    $mensaje = $uDao->eliminarUsuario($_GET['id']);
    header("Location:../listado.php?mensaje=" . urlencode($mensaje));
    exit();
} elseif (isset($_POST['modificar'])) {
    $uDao = new UsuarioDao();
    $uDto = new UsuarioDto();
    $uDto->setIdobservador($_POST['idobservador'] ?? null);
    $uDto->setDocumento($_POST['documento'] ?? null);
    $uDto->setFecha($_POST['fecha'] ?? null);
    $uDto->setDescripcionFalta($_POST['descripcion_falta'] ?? null);
    $uDto->setCompromiso($_POST['compromiso'] ?? null);
    $uDto->setFirma($_POST['firma'] ?? null);
    $uDto->setSeguimiento($_POST['seguimiento'] ?? null);
    $uDto->setFalta($_POST['falta'] ?? null);

    $mensaje = $uDao->modificarUsuario($uDto);
    header("Location:../listado.php?mensaje=" . urlencode($mensaje));
    exit();
}
?>
