<?php

session_start();
header("Content-Type: application/json");

require_once('fpdpf/fpdf.php');

$host    = 'localhost';
$db      = 'goe';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al conectar con la base de datos']);
    exit;
}

function getInputData() {
    $input = file_get_contents("php://input");
    return json_decode($input, true);
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'pdf') {
            $studentTI = isset($_GET['student_ti']) ? trim($_GET['student_ti']) : '';
            if (!$studentTI) {
                http_response_code(400);
                echo json_encode(['error' => 'Parámetro student_ti es requerido para generar el PDF']);
                exit;
            }

            $sql = "SELECT 
                        u.documento, u.nombre1, u.nombre2, u.apellido1, u.apellido2, u.email,
                        c.salon AS curso,
                        m.nomb_mat AS materia,
                        a.fecha_asistencia, a.estado_asis, a.justificacion_inasistencia,
                        o.fecha AS observacion_fecha, o.descripcion_falta, o.compromiso, o.seguimiento
                    FROM usuario u
                    LEFT JOIN curso c ON u.grado = c.grado
                    LEFT JOIN asistencia a ON u.documento = a.documento
                    LEFT JOIN curso_materia cm ON c.grado = cm.grado
                    LEFT JOIN materia m ON cm.idmat = m.idmat
                    LEFT JOIN observador o ON u.documento = o.documento
                    WHERE u.documento = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$studentTI]);
            $records = $stmt->fetchAll();

            if (!$records) {
                http_response_code(404);
                echo json_encode(['error' => 'No se encontró información para el estudiante']);
                exit;
            }

            class PDF extends FPDF {
                function Header() {
                    $this->SetFont('Arial', 'B', 15);
                    $this->Cell(0, 10, utf8_decode('Reporte del Estudiante'), 0, 1, 'C');
                    $this->Ln(5);
                }

                function Footer() {
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
                }
            }

            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 12);

            $firstRow = $records[0];

            $pdf->Cell(0, 10, utf8_decode('Documento: ' . $firstRow['documento']), 0, 1);
            $pdf->Cell(0, 10, utf8_decode('Nombre: ' . $firstRow['nombre1'] . ' ' . $firstRow['nombre2']), 0, 1);
            $pdf->Cell(0, 10, utf8_decode('Apellido: ' . $firstRow['apellido1'] . ' ' . $firstRow['apellido2']), 0, 1);
            $pdf->Cell(0, 10, utf8_decode('Email: ' . $firstRow['email']), 0, 1);
            $pdf->Cell(0, 10, utf8_decode('Curso: ' . $firstRow['curso']), 0, 1);
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode('Detalle de Registros:'), 0, 1);
            $pdf->SetFont('Arial', '', 12);

            foreach ($records as $row) {
                $pdf->Cell(0, 10, utf8_decode('Materia: ' . $row['materia']), 0, 1);
                $pdf->Cell(0, 10, utf8_decode('Fecha Asistencia: ' . $row['fecha_asistencia'] . ' - Estado: ' . $row['estado_asis']), 0, 1);
                if ($row['justificacion_inasistencia']) {
                    $pdf->Cell(0, 10, utf8_decode('Justificación: ' . $row['justificacion_inasistencia']), 0, 1);
                }
                if ($row['observacion_fecha']) {
                    $pdf->Cell(0, 10, utf8_decode('Obs. Fecha: ' . $row['observacion_fecha']), 0, 1);
                    $pdf->Cell(0, 10, utf8_decode('Falta: ' . $row['descripcion_falta']), 0, 1);
                    if ($row['compromiso']) {
                        $pdf->Cell(0, 10, utf8_decode('Compromiso: ' . $row['compromiso']), 0, 1);
                    }
                    if ($row['seguimiento']) {
                        $pdf->Cell(0, 10, utf8_decode('Seguimiento: ' . $row['seguimiento']), 0, 1);
                    }
                }
                $pdf->Ln(3);
            }

            header('Content-Type: application/pdf');
            $pdf->Output('I', 'reporte_estudiante.pdf');
            exit;
        } else {
            if (!isset($_GET['student_ti'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Parámetro student_ti requerido']);
                exit;
            }
            $studentTI = trim($_GET['student_ti']);

            $sql = "SELECT 
                        u.documento, u.nombre1, u.nombre2, u.apellido1, u.apellido2, u.email,
                        c.salon AS curso,
                        m.nomb_mat AS materia,
                        a.fecha_asistencia, a.estado_asis, a.justificacion_inasistencia,
                        o.fecha AS observacion_fecha, o.descripcion_falta, o.compromiso, o.seguimiento
                    FROM usuario u
                    LEFT JOIN curso c ON u.grado = c.grado
                    LEFT JOIN asistencia a ON u.documento = a.documento
                    LEFT JOIN curso_materia cm ON c.grado = cm.grado
                    LEFT JOIN materia m ON cm.idmat = m.idmat
                    LEFT JOIN observador o ON u.documento = o.documento
                    WHERE u.documento = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$studentTI]);
            $data = $stmt->fetchAll();

            if (!$data) {
                http_response_code(404);
                echo json_encode(['error' => 'No se encontraron registros']);
            } else {
                echo json_encode($data);
            }
        }
        break;

    case 'POST':
        $dataIn = getInputData();
        if (!$dataIn || empty($dataIn['documento']) || empty($dataIn['campo']) || !array_key_exists('valor', $dataIn)) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos para inserción']);
            exit;
        }
        $documento = trim($dataIn['documento']);
        $campo = trim($dataIn['campo']);
        $valor = trim($dataIn['valor']);

        $allowedFields = ['nombre1', 'nombre2', 'apellido1', 'apellido2', 'email', 'grado'];
        if (!in_array($campo, $allowedFields)) {
            http_response_code(400);
            echo json_encode(['error' => 'Campo no permitido']);
            exit;
        }

        $sql = "UPDATE usuario SET $campo = :valor WHERE documento = :documento";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute(['valor' => $valor, 'documento' => $documento]);
            echo json_encode(['success' => 'Campo actualizado correctamente']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el campo', 'details' => $e->getMessage()]);
        }
        break;

    case 'PUT':
        $dataIn = getInputData();
        if (!$dataIn || !isset($dataIn['documento'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos para actualización']);
            exit;
        }
        $documento = trim($dataIn['documento']);
        $nombre1 = isset($dataIn['nombre1']) ? trim($dataIn['nombre1']) : null;

        $sql = "UPDATE usuario SET nombre1 = ? WHERE documento = ?";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$nombre1, $documento]);
            echo json_encode(['success' => 'Registro actualizado correctamente']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el registro', 'details' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        $dataIn = getInputData();
        if (!$dataIn || !isset($dataIn['documento'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos para eliminación']);
            exit;
        }
        $documento = trim($dataIn['documento']);

        $sql = "DELETE FROM usuario WHERE documento = ?";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$documento]);
            echo json_encode(['success' => 'Registro eliminado correctamente']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar el registro', 'details' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
?>