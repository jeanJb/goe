<!-- Formulario de recuperación de contraseña -->
<form action="recuperar_contraseña.php" method="post">
    <label for="email">Correo Electrónico:</label>
    <input type="email" name="email" required>
    <input type="submit" value="Recuperar Contraseña">
</form>




<?php
// Recuperar el correo electrónico del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Conectar a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'goe');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si el correo existe en la base de datos
    $sql = "SELECT documento, nombre1, email FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El correo existe, generar un enlace para restablecer la contraseña
        $user = $result->fetch_assoc();
        $documento = $user['documento'];
        $nombre = $user['nombre1'];

        // Generar un token único para el enlace de recuperación
        $token = bin2hex(random_bytes(50)); // Genera un token aleatorio

        // Guardar el token en la base de datos
        $sql = "UPDATE usuario SET token = ? WHERE documento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $token, $documento);
        $stmt->execute();

        // Enviar un correo electrónico con el enlace de recuperación
        $link = "http://tusitio.com/restablecer_contraseña.php?token=" . $token;
        $subject = "Recuperación de Contraseña";
        $message = "Hola $nombre,\n\nHaz clic en el siguiente enlace para restablecer tu contraseña: $link";
        $headers = "From: no-reply@tusitio.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "Te hemos enviado un enlace para restablecer tu contraseña. Revisa tu correo.";
        } else {
            echo "Hubo un error al enviar el correo.";
        }
    } else {
        echo "No encontramos un usuario con ese correo.";
    }

    $conn->close();
}
?>
