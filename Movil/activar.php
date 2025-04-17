<?php
require 'Utilidades/conexion.php';

if (!isset($_GET['token'])) {
    die("Token inválido.");
}

$token = $_GET['token'];
$cnn = Conexion::getConexion();

// Buscar el usuario con ese token
$stmt = $cnn->prepare("SELECT documento FROM usuario WHERE token_activacion = :token LIMIT 1");
$stmt->bindParam(':token', $token);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Token inválido o expirado. Por favor, solicita un nuevo enlace de activación");
}

// Activar cuenta
$stmt = $cnn->prepare("UPDATE usuario SET activo = 1, token_activacion = NULL WHERE documento = :documento");
$stmt->bindParam(':documento', $usuario['documento']);
$stmt->execute();

header("Location: go.php?status=success&message=Cuenta activada con éxito. Ahora puedes iniciar sesión.");
exit();
?>
