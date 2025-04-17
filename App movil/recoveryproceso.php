<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'Utilidades/conexion.php';

$cnn = Conexion::getConexion();
$email = $_POST['txtemail'];

$sentencia = $cnn->prepare("SELECT documento FROM usuario WHERE email = :email");
$sentencia->bindParam(':email', $email);
$sentencia->execute();
$row = $sentencia->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $token = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    $stmt = $cnn->prepare("UPDATE usuario SET reset_token = :token, token_expiration = :expira WHERE email = :email");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expira', $expira);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
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
        $mail->addAddress($email); // Destinatario
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Subject = '🔑 Recuperación de contraseña - GOE';

        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Restablecer Contraseña</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #EAF0F6;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 100%;
                    max-width: 600px;
                    margin: 20px auto;
                    background: rgb(232, 234, 237);
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    text-align: center;
                }
                .header {
                    background: #1E3A8A;
                    color: white;
                    padding: 15px;
                    font-size: 22px;
                    font-weight: bold;
                    border-radius: 8px 8px 0 0;
                }
                .logo {
                    margin: 20px 0;
                }
                .content {
                    padding: 20px;
                    color: #333;
                    font-size: 16px;
                    text-align: center;
                }
                .content p {
                    margin: 12px 0;
                }
                .warning {
                    font-size: 14px;
                    color: #D97706;
                    font-weight: bold;
                }
                .reset-box {
                    background: #e5f3ff;
                    padding: 20px;
                    border-radius: 8px;
                    margin: 20px 0;
                    text-align: center;
                }
                .button {
                    display: inline-block;
                    padding: 12px 25px;
                    background: #2563EB;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    font-size: 16px;
                    font-weight: bold;
                    margin: 10px 0;
                }
                .button:hover {
                    background: #1E40AF;
                }
                .footer {
                    font-size: 12px;
                    color: #6B7280;
                    margin-top: 20px;
                }
                .footer a {
                    color: #2563EB;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    🔒 Restablecimiento de Contraseña
                </div>

                <!-- Espacio para el logo del aplicativo -->
                <div class='logo'>
                    <img src='https://i.postimg.cc/tJtrPMzR/goe03.png' alt='Logo de GOE' width='120'>
                </div>

                <div class='content'>
                    <p><b>Hola, esperamos que estés teniendo un excelente día.</b></p>
                    <p>Recibimos una solicitud para restablecer tu contraseña en la plataforma <b>GOE</b>. Si no hiciste esta solicitud, <b>ignora este mensaje</b> y tu cuenta seguirá protegida.</p>

                    <div class='reset-box'>
                        <p>Para restablecer tu contraseña, haz clic en el siguiente botón:</p>
                        <a href='http://localhost/goes/Utilidades/change_password.php?token=$token' class='button'>Restablecer Contraseña</a>
                        <p class='warning'>Este enlace expirará en 1 hora.</p>
                    </div>

                    <p>Si tienes problemas con el botón, copia y pega este enlace en tu navegador:</p>
                    <p><a href='http://localhost/goes/Utilidades/change_password.php?token=$token'>http://localhost/goes/Utilidades/change_password.php?token=$token</a></p>

                    <p>Para mayor seguridad, recuerda nunca compartir tu contraseña con nadie.</p>

                    <div class='footer'>
                        <p>Si necesitas ayuda, contáctanos en <a href='mailto:soporte@goe.com'>soporte@goe.com</a></p>
                        <p>© 2025 GOE - Plataforma Escolar. Todos los derechos reservados.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
        $mail->AltBody = "Haz clic en el siguiente enlace para restablecer tu contraseña: 
        http://localhost/goes/Utilidades/change_password.php?token=$token";
        
        $mail->send();
        header('Location: go.php?status=success&message=Se ha enviado un correo con instrucciones para restablecer tu contraseña.');
        exit();
    } catch (Exception $e) {
        header('Location: go.php?status=error&message=No se pudo enviar el correo. Error: ' . urlencode($mail->ErrorInfo));
        exit();
    }
} else {
    header('Location: go.php?status=orange&message=No se encontró una cuenta con ese correo electrónico.');
    exit();
}
?>