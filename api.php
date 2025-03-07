<?php
session_start();
require('fpdpf/fpdf.php'); // Asegúrate de que la ruta sea correcta

// Solo se permite el método POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Verificar parámetros
if (!isset($_POST['student_ti'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el parámetro student_ti']);
    exit;
}

$studentTI = $_POST['student_ti'];

// Conexión a la base de datos
$host = 'localhost';
$db = 'goe';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al conectar con la base de datos']);
    exit;
}

// Consulta SQL corregida
$sql = "SELECT 
            u.nombre1, u.nombre2, u.apellido1, u.apellido2, u.documento, u.email,
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
$studentData = $stmt->fetchAll();

// Si no se encuentran datos, devolver error
if (!$studentData) {
    http_response_code(404);
    echo json_encode(['error' => 'No se encontraron datos para el estudiante']);
    exit;
}

// Crear la clase para el PDF
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode('Reporte del Estudiante'), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Generar el PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Verificar si hay datos antes de acceder a ellos
$firstRow = $studentData[0] ?? [];

$pdf->Cell(0, 10, utf8_decode('Nombre: ' . ($firstRow['nombre1'] ?? '') . ' ' . ($firstRow['nombre2'] ?? '')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Apellido: ' . ($firstRow['apellido1'] ?? '') . ' ' . ($firstRow['apellido2'] ?? '')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Documento: ' . ($firstRow['documento'] ?? '')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Email: ' . ($firstRow['email'] ?? '')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Curso: ' . ($firstRow['curso'] ?? '')), 0, 1);

// Materias
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Materias:'), 0, 1);
$pdf->SetFont('Arial', '', 12);

$materias = array_unique(array_column($studentData, 'materia'));
foreach ($materias as $materia) {
    if ($materia) {
        $pdf->Cell(0, 10, utf8_decode('- ' . $materia), 0, 1);
    }
}

// Asistencias
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Asistencias:'), 0, 1);
$pdf->SetFont('Arial', '', 12);

foreach ($studentData as $row) {
    if ($row['fecha_asistencia']) {
        $pdf->Cell(0, 10, utf8_decode('Fecha: ' . $row['fecha_asistencia'] . ' - Estado: ' . $row['estado_asis']), 0, 1);
        if ($row['justificacion_inasistencia']) {
            $pdf->Cell(0, 10, utf8_decode('Justificación: ' . $row['justificacion_inasistencia']), 0, 1);
        }
    }
}

// Observaciones
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Observaciones:'), 0, 1);
$pdf->SetFont('Arial', '', 12);

foreach ($studentData as $row) {
    if ($row['observacion_fecha']) {
        $pdf->Cell(0, 10, utf8_decode('Fecha: ' . $row['observacion_fecha']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Falta: ' . $row['descripcion_falta']), 0, 1);
        if ($row['compromiso']) {
            $pdf->Cell(0, 10, utf8_decode('Compromiso: ' . $row['compromiso']), 0, 1);
        }
        if ($row['seguimiento']) {
            $pdf->Cell(0, 10, utf8_decode('Seguimiento: ' . $row['seguimiento']), 0, 1);
        }
        $pdf->Ln(5);
    }
}

$pdf->Output('I', 'reporte_estudiante.pdf');
exit;
?>

   
