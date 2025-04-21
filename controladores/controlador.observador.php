<?php
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/ObservadorDto.php';
require '../Utilidades/conexion.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$cnn = Conexion::getConexion();

if (isset($_POST['registro'])) {
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

    $trimestre= $_POST['trimestre'];
    $mensaje = $uDao->registrarObservador($uDto, $trimestre);

    // Obtener datos del estudiante y acudiente
    $sentencia = $cnn->prepare("SELECT usuario.*, directorio.email_acu FROM usuario
    INNER JOIN directorio ON usuario.documento=directorio.documento 
    WHERE usuario.documento = :documento");
    $documento = $_POST['documento'];
    $sentencia->bindParam(':documento', $documento);
    $sentencia->execute();
    $row = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($row) { // Si hay datos en la consulta
        $email_estudiante = $row['email'];
        $email_acudiente = $row['email_acu'];
        $nombre_estudiante = $row['nombre1'].' '.$row['nombre2'].' '.$row['apellido1'].' '.$row['apellido2'];
        $curso = $row['grado'];

        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sebastiancawolf@gmail.com';
            $mail->Password = 'wnet xftv nspt ergl';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('sebastiancawolf@gmail.com', 'GOE - Plataforma Escolar'); // Cambia esto a un correo válido
            $mail->addAddress($email_estudiante);
            if (!empty($email_acudiente)) {
                $mail->addAddress($email_acudiente);
            }

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Subject = 'Nueva Observación Registrada';
            $mail->Body = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Notificación de Observación</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        background: rgb(232, 234, 237);
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        padding: 15px;
                        background: #00438b;
                        color: #ffffff;
                        font-size: 22px;
                        font-weight: bold;
                        border-radius: 8px 8px 0 0;
                    }
                    .logo img {
                        max-width: 100px;
                        height: auto;
                    }
                    .content {
                        text-align: center;
                        padding: 20px;
                        color: #333;
                    }
                    .button {
                        display: inline-block;
                        padding: 12px 20px;
                        margin: 20px 0;
                        background: #0056b3;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                    }
                    .button:hover {
                        background: #003d82;
                    }
                    .footer {
                        text-align: center;
                        padding: 15px;
                        font-size: 12px;
                        color: #777;
                        border-top: 1px solid #ddd;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <div class='logo'>
                            <img src='https://i.postimg.cc/RV8Bcn4z/goe03.png' alt='GOE'>
                        </div>
                        🚨 Alerta de Observación
                    </div>
                    <div class='content'>
                        <p>Estimado(a) acudiente y estudiante,</p>
                        <p>Le informamos que se ha registrado una nueva observación para:</p>
                        <p><b>Estudiante:</b> $nombre_estudiante</p>
                        <p><b>Curso:</b> $curso</p>
                        <p>Para más información, por favor acceda a su cuenta en la plataforma.</p>
                        <a href='http://localhost/goes/index.html' class='button'>Ir a la Plataforma</a>
                    </div>
                    <div class='footer'>
                        &copy; 2025 GOE - Plataforma Escolar. Todos los derechos reservados.
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar el correo: {$mail->ErrorInfo}");
        }
    }
    
    header("Location:../observadores.php?mensaje=" . $mensaje);
} else if (isset($_GET['id'])) {
    $uDao = new UsuarioDao();
    $mensaje = $uDao->eliminarObservador($_GET['id']);
    header("Location:../observadores.php?mensaje=" . $mensaje);
} else if (isset($_POST['actualizar'])) {
    $uDao = new UsuarioDao();
    $uDto = new ObservadorDto();
    
    $uDto->setIDObservador($_POST['idobservador']);
    $uDto->setDocumento($_POST['documento']);
    $uDto->setFecha($_POST['fecha']);
    $uDto->setDescripcion_falta($_POST['descripcion_falta']);
    $uDto->setCompromiso($_POST['compromiso']);
    $uDto->setFirma($_POST['firma'] ?? null);
    $uDto->setSeguimiento($_POST['seguimiento']);
    $uDto->setFalta($_POST['falta']);

    $mensaje = $uDao->modificarObservador($uDto);
    header("Location:../observadores.php?mensaje=" . $mensaje);
}
?>
