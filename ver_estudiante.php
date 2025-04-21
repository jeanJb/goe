<?php
require 'Utilidades/conexion.php';
require 'ModeloDAO/UsuarioDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['documento'])) {
    $documento = $_POST['documento'];
    
    $uDao = new UsuarioDao();
    $estudiante = $uDao->VerUser($documento);

    if ($estudiante) {
        echo json_encode([
            "success" => true,
            "nombre" => $estudiante['nombre1'] . " " . $estudiante['apellido1'],
            "email" => $estudiante['email'],
            "tipo_doc" => $estudiante['tipo_doc'],
            "documento" => $estudiante['documento'],
            "telefono" => $estudiante['telefono'],
            "direccion" => $estudiante['direccion'],
            "rh" => $estudiante['rh'],
            "eps" => $estudiante['eps'],
            "fecha_nacimiento" => $estudiante['fecha_nacimiento'],
            "enfermedades" => $estudiante['enfermedades'],
            "nombre_acudiente" => $estudiante['nombre_acudiente'],
            "telefono_acudiente" => $estudiante['telefono_acudiente'],
            "documento_acudiente" => $estudiante['documento_acudiente'],
            "correo_acudiente" => $estudiante['correo_acudiente']
        ]);
    } else {
        echo json_encode(["success" => false]);
    }
}
?>
