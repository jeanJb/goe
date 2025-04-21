<?php
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'conexion.php';

// Configuración segura
header('Content-Type: application/json');

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

// Validar sesión y permisos
session_start();
if (!isset($_SESSION['documento']) || !isset($_SESSION['token_sesion']) || $_SESSION['id_rol'] != 104) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Obtener y validar datos del formulario
$destinatario = filter_input(INPUT_POST, 'destinatario', FILTER_SANITIZE_STRING);
$asunto = filter_input(INPUT_POST, 'asunto', FILTER_SANITIZE_STRING);
$mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);
$incluir_acudiente = isset($_POST['incluir_acudiente']);
$id_estudiante = filter_input(INPUT_POST, 'id_estudiante', FILTER_VALIDATE_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_VALIDATE_INT);

// Validaciones adicionales
if (!$asunto || !$mensaje) {
    http_response_code(400);
    echo json_encode(['error' => 'Asunto y mensaje son obligatorios']);
    exit();
}

if ($destinatario === 'estudiante' && !$id_estudiante) {
    http_response_code(400);
    echo json_encode(['error' => 'Debe seleccionar un estudiante']);
    exit();
}

if (($destinatario === 'curso') && !$id_curso) {
    http_response_code(400);
    echo json_encode(['error' => 'Debe seleccionar un curso']);
    exit();
}

$conn = Conexion::getConexion();
$correos = [];

try {
    if ($destinatario === "estudiante") {
        // Obtener datos del estudiante
        $stmt = $conn->prepare("
            SELECT u.email, u.nombre1, u.nombre2, u.apellido1, u.apellido2, u.grado
            FROM usuario u
            WHERE u.documento = :documento
        ");
        $stmt->bindParam(':documento', $id_estudiante, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombreCompleto = trim("{$row['nombre1']} {$row['nombre2']} {$row['apellido1']} {$row['apellido2']}");
            $correos[] = [
                'email' => $row['email'],
                'nombre' => $nombreCompleto,
                'tipo' => 'estudiante'
            ];

            // Obtener acudiente si está marcado
            if ($incluir_acudiente) {
                $stmt_acu = $conn->prepare("
                    SELECT email_acu, nom_acu 
                    FROM directorio 
                    WHERE documento = :documento
                    AND email_acu IS NOT NULL
                    AND email_acu != ''
                ");
                $stmt_acu->bindParam(':documento', $id_estudiante, PDO::PARAM_INT);
                $stmt_acu->execute();
                
                if ($row_acu = $stmt_acu->fetch(PDO::FETCH_ASSOC)) {
                    $correos[] = [
                        'email' => $row_acu['email_acu'],
                        'nombre' => $row_acu['nom_acu'],
                        'tipo' => 'acudiente'
                    ];
                }
            }
        }
    } elseif ($destinatario === "curso") {
        // Obtener todos los estudiantes del curso
        $stmt = $conn->prepare("
            SELECT u.documento, u.email, u.nombre1, u.nombre2, u.apellido1, u.apellido2
            FROM usuario u
            WHERE u.grado = :grado
        ");
        $stmt->bindParam(':grado', $id_curso, PDO::PARAM_INT);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombreCompleto = trim("{$row['nombre1']} {$row['nombre2']} {$row['apellido1']} {$row['apellido2']}");
            $correos[] = [
                'email' => $row['email'],
                'nombre' => $nombreCompleto,
                'tipo' => 'estudiante',
                'documento' => $row['documento']
            ];

            // Si se incluye acudiente, obtenerlo para cada estudiante
            if ($incluir_acudiente) {
                $stmt_acu = $conn->prepare("
                    SELECT email_acu, nom_acu 
                    FROM directorio 
                    WHERE documento = :documento
                    AND email_acu IS NOT NULL
                    AND email_acu != ''
                ");
                $stmt_acu->bindParam(':documento', $row['documento'], PDO::PARAM_INT);
                $stmt_acu->execute();
                
                if ($row_acu = $stmt_acu->fetch(PDO::FETCH_ASSOC)) {
                    $correos[] = [
                        'email' => $row_acu['email_acu'],
                        'nombre' => $row_acu['nom_acu'],
                        'tipo' => 'acudiente'
                    ];
                }
            }
        }
    } elseif ($destinatario === "todos") {
        // Obtener todos los estudiantes
        $stmt = $conn->prepare("
            SELECT u.documento, u.email, u.nombre1, u.nombre2, u.apellido1, u.apellido2
            FROM usuario u
            ORDER BY u.grado, u.apellido1, u.apellido2, u.nombre1
        ");
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombreCompleto = trim("{$row['nombre1']} {$row['nombre2']} {$row['apellido1']} {$row['apellido2']}");
            $correos[] = [
                'email' => $row['email'],
                'nombre' => $nombreCompleto,
                'tipo' => 'estudiante',
                'documento' => $row['documento']
            ];

            // Si se incluye acudiente, obtenerlo para cada estudiante
            if ($incluir_acudiente) {
                $stmt_acu = $conn->prepare("
                    SELECT email_acu, nom_acu 
                    FROM directorio 
                    WHERE documento = :documento
                    AND email_acu IS NOT NULL
                    AND email_acu != ''
                ");
                $stmt_acu->bindParam(':documento', $row['documento'], PDO::PARAM_INT);
                $stmt_acu->execute();
                
                if ($row_acu = $stmt_acu->fetch(PDO::FETCH_ASSOC)) {
                    $correos[] = [
                        'email' => $row_acu['email_acu'],
                        'nombre' => $row_acu['nom_acu'],
                        'tipo' => 'acudiente'
                    ];
                }
            }
        }
    }

    // Cargar plantilla HTML
    $plantilla = file_get_contents('diseno_email.html');
    $anio = date('Y');

    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'sebastiancawolf@gmail.com';
    $mail->Password = 'wnet xftv nspt ergl';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->setFrom('sebastiancawolf@gmail.com', 'Colegio Técnico Jose Felix Restrepo');
    $mail->Subject = $asunto;

    // Contadores para estadísticas
    $enviados = 0;
    $fallidos = 0;
    $errores = [];

    // Enviar a cada destinatario con mensaje personalizado
    foreach ($correos as $dest) {
        try {
            $mail->clearAddresses();
            $mail->addAddress($dest['email'], $dest['nombre']);
            
            // Personalizar mensaje según tipo de destinatario
            $mensajePersonalizado = $mensaje;
            if ($dest['tipo'] === 'acudiente') {
                $mensajePersonalizado = "Estimado acudiente {$dest['nombre']}:\n\n" . $mensaje;
            }

            $cuerpoEmail = str_replace(
                ['{NOMBRE_DESTINATARIO}', '{MENSAJE}', '{AÑO}'],
                [$dest['nombre'], nl2br($mensajePersonalizado), $anio],
                $plantilla
            );
            
            $mail->isHTML(true);
            $mail->Body = $cuerpoEmail;
            $mail->AltBody = strip_tags($mensajePersonalizado);
            
            if ($mail->send()) {
                $enviados++;
            } else {
                $fallidos++;
                $errores[] = "Error enviando a {$dest['email']}: {$mail->ErrorInfo}";
            }
        } catch (Exception $e) {
            $fallidos++;
            $errores[] = "Error enviando a {$dest['email']}: {$e->getMessage()}";
            error_log("Error enviando correo: " . $e->getMessage());
        }
    }

    // Preparar mensaje de resultado
    $mensajeResultado = "Se enviaron {$enviados} correos correctamente.";
    if ($fallidos > 0) {
        $mensajeResultado .= " {$fallidos} correos no pudieron ser enviados.";
    }

    // Redirigir con mensaje de éxito/error
    if ($fallidos === 0) {
        header("Location: ../email.php?exito=" . urlencode($mensajeResultado));
    } else {
        header("Location: ../email.php?exito=" . urlencode($mensajeResultado) . "&error=" . urlencode(implode("; ", $errores)));
    }
    exit();
    
} catch (Exception $e) {
    error_log("Error en generar_email: " . $e->getMessage());
    header("Location: ../email.php?error=" . urlencode("Error al enviar correos: " . $e->getMessage()));
    exit();
}