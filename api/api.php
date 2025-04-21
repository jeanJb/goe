<?php
session_start();
require('fpdpf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

if (!isset($_POST['student_ti'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el parámetro student_ti']);
    exit;
}

$studentTI = $_POST['student_ti'];

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

// Consulta optimizada para evitar datos duplicados
$sql = "SELECT 
            u.nombre1, u.nombre2, u.apellido1, u.apellido2, u.documento, u.email,
            c.grado AS curso,
            GROUP_CONCAT(DISTINCT m.nomb_mat ORDER BY m.nomb_mat SEPARATOR ', ') AS materias,
            a.fecha_asistencia, a.estado_asis, a.justificacion_inasistencia,
            o.fecha AS observacion_fecha, o.descripcion_falta, o.compromiso, o.seguimiento,
            u.foto
        FROM usuario u
        LEFT JOIN curso c ON u.grado = c.grado
        LEFT JOIN asistencia a ON u.documento = a.documento
        LEFT JOIN curso_materia cm ON c.grado = cm.grado
        LEFT JOIN materia m ON cm.idmat = m.idmat
        LEFT JOIN observador o ON u.documento = o.documento
        WHERE u.documento = ?
        GROUP BY u.documento, a.fecha_asistencia, o.fecha";

$stmt = $pdo->prepare($sql);
$stmt->execute([$studentTI]);
$studentData = $stmt->fetchAll();

if (!$studentData) {
    http_response_code(404);
    echo json_encode(['error' => 'No se encontraron datos para el estudiante']);
    exit;
}

// Clase personalizada para el PDF
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->SetFillColor(100, 100, 255);
        $this->Cell(0, 10, utf8_decode('Reporte del Estudiante'), 0, 1, 'C', true);
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function ChapterTitle($title)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, utf8_decode($title), 0, 1, 'L', true);
        $this->Ln(4);
    }

    function ChapterBody($body)
    {
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 6, utf8_decode($body));
        $this->Ln();
    }

    function Table($header, $data)
    {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 220, 255);
        foreach ($header as $col) {
            $this->Cell(50, 6, utf8_decode($col), 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFont('Arial', '', 10);
        foreach ($data as $row) {
            foreach ($row as $cell) {
                $this->Cell(50, 6, utf8_decode($cell), 1);
            }
            $this->Ln();
        }
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$firstRow = $studentData[0];

// Validar la imagen
$fotoPath = '../Uploads/' . ($firstRow['foto'] ?? 'user.png');
if (!file_exists($fotoPath) || !exif_imagetype($fotoPath)) {
    $fotoPath = 'fotos/default.jpg'; // Imagen por defecto
}
$pdf->Image($fotoPath, 10, 40, 30, 30);
$pdf->Ln(40);

// Datos del estudiante
$pdf->ChapterTitle('Datos del Estudiante');
$pdf->ChapterBody(
    "Nombre: " . trim($firstRow['nombre1'] . ' ' . $firstRow['nombre2']) . "\n" .
    "Apellido: " . trim($firstRow['apellido1'] . ' ' . $firstRow['apellido2']) . "\n" .
    "Documento: " . $firstRow['documento'] . "\n" .
    "Email: " . $firstRow['email'] . "\n" .
    "Curso: " . $firstRow['curso']
);

// Materias
$pdf->ChapterTitle('Materias');
$pdf->ChapterBody($firstRow['materias'] ?: 'No se encontraron materias');

// Tabla de Asistencias
$pdf->ChapterTitle('Asistencias y Observaciones');
$tableData = [];
foreach ($studentData as $row) {
    $tableData[] = [
        $row['fecha_asistencia'] ?: '-',
        $row['estado_asis'] ?: '-',
        $row['justificacion_inasistencia'] ?: '-',
        ($row['observacion_fecha'] ? $row['observacion_fecha'] . "\nFalta: " . $row['descripcion_falta'] . "\nCompromiso: " . $row['compromiso'] . "\nSeguimiento: " . $row['seguimiento'] : '-')
    ];
}
$pdf->Table(['Fecha', 'Estado', 'Justificación', 'Observación'], $tableData);

$pdf->Output('I', 'reporte_estudiante.pdf');
exit;
?>
