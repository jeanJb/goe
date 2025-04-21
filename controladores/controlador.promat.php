<?php
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/PromatDto.php';
require '../Utilidades/conexion.php';

// Crear instancia de UsuarioDao
$uDao = new UsuarioDao();

// Registrar asignación
if (isset($_POST['registro'])) {
    $uDto = new PromatDto();
    $uDto->setIDRelacion($_POST['id_relacion'] ?? null);
    $uDto->setDocumento($_POST['documento'] ?? null);
    $uDto->setIDMat($_POST['idMat'] ?? null);

    $mensaje = $uDao->registrarPromat($uDto);
    header("Location: ../pro_mat.php?mensaje=" . urlencode($mensaje));
    exit();
}

// Eliminar asignación
if (isset($_GET['id'])) {
    $mensaje = $uDao->eliminarPromat($_GET['id']);
    header("Location: ../pro_mat.php?mensaje=" . urlencode($mensaje));
    exit();
}

// Eliminar materia
if (isset($_GET['name'])) {
    $mensaje = $uDao->eliminarMateria($_GET['name']);
    header("Location: ../pro_mat.php?mensaje=" . urlencode($mensaje));
    exit();
}

// Modificar asignación
if (isset($_POST['asignatura'])) {
    $uDto = new PromatDto();
    $uDto->setIDRelacion($_POST['id_relacion'] ?? null);
    $uDto->setDocumento($_POST['documento'] ?? null);
    $uDto->setIDMat($_POST['idMat'] ?? null);

    $mensaje = $uDao->modificarObservador($uDto);
    header("Location: ../observadores.php?mensaje=" . urlencode($mensaje));
    exit();
}

// Si no se cumple ninguna condición
header("Location: ../pro_mat.php?mensaje=Acción no válida");
exit();