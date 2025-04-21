<?php
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/AsistenciaDto.php';
require '../Utilidades/conexion.php';

$uDao = new UsuarioDao();

// Establecer cabecera JSON para respuestas
function enviarRespuestaJson($datos) {
    header('Content-Type: application/json');
    echo json_encode($datos);
    exit();
}

/* CONSULTAR ASISTENCIAS */
if (!empty($_GET['idlistado'])) {
    enviarRespuestaJson($uDao->listadoAsis($_GET['idlistado']));
}

/* ELIMINAR ASISTENCIA */
if (!empty($_GET['id'])) {
    $mensaje = $uDao->eliminarAsistencia($_GET['id']);
    header("Location: ../asistencias.php?mensaje=" . urlencode($mensaje));
    exit();
}

/* REGISTRO DE ASISTENCIAS MÚLTIPLES */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registro de asistencias múltiples
    if (!empty($_POST['estado_asistencias']) && !empty($_POST['fecha_asistencia']) && isset($_POST['profesor'], $_POST['idmat'], $_POST['grado'])) {
        $asistencias = array_map(function ($documento, $estado) {
            return [
                'profesor'      => $_POST['profesor'],
                'documento'     => $documento,
                'estado'        => $estado,
                'materia'       => $_POST['idmat'],
                'curso'         => $_POST['grado'],
                'fecha'         => $_POST['fecha_asistencia'],
                'justificacion' => $_POST['justificacion_inasistencia'][$documento] ?? 'N/A'
            ];
        }, array_keys($_POST['estado_asistencias']), $_POST['estado_asistencias']);

        $trimestre = $_POST['trimestre'];
        $mensaje = $uDao->registrarAsistencias($trimestre, $asistencias) ? "Registro exitoso" : "Error al registrar asistencias";
        header("Location: ../asistencias.php?mensaje=" . urlencode($mensaje));
        exit();
    }

    // Actualización de asistencia
    if (isset($_POST['update'])) {
        $id = $_POST['idasistencia'];
        $estado = $_POST['estado_asis'];
        $justificacion = $_POST['justificacion_inasistencia'];
    
        $resultado = $uDao->actualizarAsistencia($id, $estado, $justificacion);
    
        header('Content-Type: application/json');
        echo json_encode(["success" => $resultado]);
        exit();
    }
    
}

// Mensaje por defecto si no se cumplen las condiciones anteriores
echo "No se enviaron datos válidos.";
?>