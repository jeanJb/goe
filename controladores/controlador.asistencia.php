<?php
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/AsistenciaDto.php';
require '../Utilidades/conexion.php';

// Procesos para gestionar la asistencia
/* if (isset($_POST['registro'])) {
    $aDao = new UsuarioDao();
    $aDto = new AsistenciaDto();

    // Asignación de valores a los campos
    $aDto->setIdAsistencia($_POST['idasistencia']);
    $aDto->setProfesor($_POST['profesor']);
    $aDto->setDocumento($_POST['documento']);
    $aDto->setEstadoAsis($_POST['estado_asis']);
    $aDto->setIdMat($_POST['idmat']);
    $aDto->setFechaAsistencia($_POST['fecha_asistencia']);
    $aDto->setJustificacionInasistencia($_POST['justificacion_inasistencia']);

    $mensaje = $aDao->registrarAsistencia($aDto);

    header("Location:../asistencia.php?mensaje=Registro exitoso" . $mensaje);
}  */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uDao = new UsuarioDao();

    // Obtener datos del formulario
    $profesor = $_POST['profesor'];
    $fecha = $_POST['fecha_asistencia'];
    $materia = $_POST['idmat'];
    $curso = $_POST['grado'];

    // Verifica si los campos clave existen y son arrays
    if (isset($_POST['estado_asistencias']) && isset($_POST['justificacion_inasistencia'])) {
        $estados = $_POST['estado_asistencias'];
        $justificaciones = $_POST['justificacion_inasistencia'];

        foreach ($estados as $documento => $estado) {
            $justificacion = $justificaciones[$documento] ?? 'N/A';

            // Inserta en la base de datos
            $resultado = $uDao->registrarAsistencia([
                'profesor' => $profesor,
                'documento' => $documento,
                'estado' => $estado,
                'materia' => $materia,
                'curso' => $curso,
                'fecha' => $fecha,
                'justificacion' => $justificacion
            ]);

            if (!$resultado) {
                echo "Error al registrar la asistencia para el estudiante con documento $documento";
            }
        }

        header("Location:../asistencia.php?mensaje=Registro exitoso");
        exit();
    } else {
        echo "No se enviaron datos de asistencia.";
    }
} else if (isset($_GET['id'])) {
    $aDao = new AsistenciaDao();
    $mensaje = $aDao->eliminarAsistencia($_GET['id']);
    header("Location:perfil.php?mensaje=" . $mensaje);
} else if (isset($_POST['actualizar'])) {
    $aDao = new AsistenciaDao();
    $aDto = new AsistenciaDto();

    // Asignación de valores para actualizar
    $aDto->setIdAsistencia($_POST['idasistencia']);
    $aDto->setProfesor($_POST['profesor']);
    $aDto->setDocumento($_POST['documento']);
    $aDto->setEstadoAsis($_POST['estado_asis']);
    $aDto->setIdMat($_POST['idmat']);
    $aDto->setFechaAsistencia($_POST['fecha_asistencia']);
    $aDto->setJustificacionInasistencia($_POST['justificacion_inasistencia']);

    $mensaje = $aDao->modificarAsistencia($aDto);
    header("Location:../ModEstudiante/perfil.php?mensaje=" . $mensaje);
}
?>
