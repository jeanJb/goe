<?php
session_start();
require 'conexion.php';
$cnn = Conexion::getConexion();

if (!isset($_GET['token'])) {
    header('Location: ../go.php?status=error&message=Token no válido.');
    exit();
}

$token = $_GET['token'];
$stmt = $cnn->prepare("SELECT documento, AES_DECRYPT(clave, 'SENA') AS clave_actual FROM usuario WHERE reset_token = :token AND token_expiration > NOW()");
$stmt->bindParam(':token', $token);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    header('Location: ../go.php?status=error&message=Token no válido o expirado.');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error_message = 'Las contraseñas no coinciden. Por favor, inténtalo de nuevo.';
    } else {
        /* $new_password = password_hash($new_password, PASSWORD_DEFAULT); */
        $stmt = $cnn->prepare("UPDATE usuario SET clave = AES_ENCRYPT(:clave, 'SENA'), reset_token = NULL, token_expiration = NULL, token_sesion = NULL WHERE documento = :id");
        $stmt->bindParam(':clave', $new_password);
        $stmt->bindParam(':id', $row['documento']);
        $stmt->execute();

        // Cerrar sesión si el usuario está logueado
        session_unset();
        session_destroy();

        header('Location: ../go.php?status=success&message=Contraseña restablecida correctamente.');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - GOE</title>
    <link rel="icon" href="../IMG/logos/goe03.png" type="image/png">
    <link rel="stylesheet" href="../STYLES/recovery.css">
    <script>
            function verify() {
                var newPassword = document.querySelector('input[name="new_password"]').value;
                var confirmPassword = document.querySelector('input[name="confirm_password"]').value;
                var clave_actual = document.querySelector('input[name="clave_actual"]').value;

                if (newPassword !== confirmPassword) {
                    alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');
                    document.querySelector('input[name="new_password"]').value = '';
                    document.querySelector('input[name="confirm_password"]').value = '';
                    document.querySelector('input[name="clave_actual"]').value = '';
                    event.preventDefault();
                }

                if (newPassword === clave_actual) {
                    alert('La nueva contraseña no puede ser igual a la actual. Por favor, inténtalo de nuevo.');
                    document.querySelector('input[name="new_password"]').value = '';
                    document.querySelector('input[name="confirm_password"]').value = '';
                    document.querySelector('input[name="clave_actual"]').value = '';
                    event.preventDefault();
                }
            }

        document.querySelector('form').addEventListener('submit', function(event) {
            var newPassword = document.querySelector('input[name="new_password"]').value;
            var confirmPassword = document.querySelector('input[name="confirm_password"]').value;

            if (newPassword !== confirmPassword) {
                alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');
                event.preventDefault();
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>
        <p>Por favor, ingresa tu nueva contraseña.</p>
        <form action="change_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <input type="hidden" name="clave_actual" value="<?php echo $row['clave_actual']; ?>">
            <input type="password" name="new_password" placeholder="Nueva contraseña" required>
            <br>
            <br>
            <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
            <br>
            <br>
            <button type="submit" class="button" onclick="verify()">Restablecer Contraseña</button>
        </form>
        <br>
        <a href="../go.php">⬅︎ Volver al inicio</a>
    </div>
</body>
</html>