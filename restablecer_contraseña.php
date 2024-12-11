<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Conectar a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'goe2');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si el token existe en la base de datos
    $sql = "SELECT documento FROM usuario WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El token es válido, mostrar el formulario de restablecimiento
        echo '
        <form action="restablecer_contraseña.php" method="post">
            <label for="nueva_clave">Nueva Contraseña:</label>
            <input type="password" name="nueva_clave" required>
            <input type="submit" value="Restablecer Contraseña">
        </form>
        ';
    } else {
        echo "El enlace de recuperación no es válido o ha expirado.";
    }

    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nueva_clave'])) {
    $nueva_clave = $_POST['nueva_clave'];

    // Recuperar el documento del usuario
    $token = $_GET['token'];
    $conn = new mysqli('localhost', 'root', '', 'goe2');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener el documento del usuario basado en el token
    $sql = "SELECT documento FROM usuario WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $documento = $user['documento'];

    // Encriptar la nueva contraseña
    $nueva_clave_encriptada = password_hash($nueva_clave, PASSWORD_DEFAULT);

    // Actualizar la contraseña en la base de datos
    $sql = "UPDATE usuario SET clave = ?, token = NULL WHERE documento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $nueva_clave_encriptada, $documento);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Tu contraseña ha sido actualizada exitosamente.";
    } else {
        echo "Hubo un error al actualizar la contraseña.";
    }

    $conn->close();
}
?>
