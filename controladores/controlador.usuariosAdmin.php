<?php
session_start();
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/UsuarioDto.php';
require '../Utilidades/conexion.php';
require '../Utilidades/enviarCorreo.php'; // Archivo para enviar correos

if (isset($_POST['registro'])) {
    $uDao = new UsuarioDao();
    $uDto = new UsuarioDto();
    $cnn = Conexion::getConexion();

    $documento = $_POST['documento'];
    $email = $_POST['email'];
    $clave = $_POST['clave']; // Se encripta en el DAO
    $token = bin2hex(random_bytes(50)); // Generar token único
    $dominio_valido = '@ctjfr.edu.co';

    // Validar que el correo tenga el dominio correcto
    if (!str_ends_with($email, $dominio_valido)) {
        header("Location: ../registro.php?status=error&message=El correo debe ser institucional (@ctjfr.edu.co)");
        exit();
    }

    // Verificar si el correo ya existe
    $stmt = $cnn->prepare("SELECT COUNT(*) FROM usuario WHERE email = :email OR documento = :documento");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':documento', $documento);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        header("Location: ../registro.php?status=error&message=El correo o el documento ya está registrado");
        exit();
    }

    $foto = "user.png";
    // Asignar valores al DTO
    $uDto->setDocumento($documento);
    $uDto->setRol($_POST['id_rol']);
    $uDto->setEmail($email);
    $uDto->setClave($clave);
    $uDto->setTD($_POST['tipo_doc']);
    $uDto->setNombre1($_POST['nombre1']);
    $uDto->setNombre2($_POST['nombre2']);
    $uDto->setApellido1($_POST['apellido1']);
    $uDto->setApellido2($_POST['apellido2']);
    $uDto->setTelefono($_POST['telefono']);
    $uDto->setDireccion($_POST['direccion']);
    $uDto->setFoto($foto);
    $uDto->setGrado($_POST['grado'] ?? null);
    $uDto->setActivo(0); // Usuario inactivo
    $uDto->setTokenActivacion($token);

    // Registrar usuario en la base de datos
    $mensaje = $uDao->registrarUsuarioDir($uDto);

    // Enviar correo de activación
    $enlace = "http://localhost/goes/activar.php?token=$token";
    $asunto = "¡Activa tu cuenta en GOE!";
    $mesage = "
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Activación de Cuenta</title>
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
        background:rgb(232, 234, 237);
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        }
        .header {
        text-align: center;
        padding: 10px;
        }
        .header h2 {
        color: #1E3A8A;
        margin-bottom: 10px;
        }
        .content {
        text-align: center;
        padding: 20px;
        }
        .content p {
        font-size: 16px;
        color: #333;
        margin: 10px 0;
        }
        .activation-box {
        background: #e5f3ff;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
        text-align: center;
        }
        .button {
        display: inline-block;
        padding: 12px 20px;
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
        img {
        width: 200px;
        margin: 0 auto;
        }
    </style>
    </head>
    <body>
    <div class='container'>
        <img src='https://i.postimg.cc/RV8Bcn4z/goe03.png' alt=''>
        <div class='header'>
        <h2>¡Bienvenido a GOE!</h2>
        </div>

        <div class='content'>
        <p>Gracias por registrarte. Para completar tu registro y activar tu cuenta, haz clic en el botón de abajo.</p>

        <div class='activation-box'>
            <a href='$enlace' class='button'>Activar Cuenta</a>
            <p>O copia y pega este enlace en tu navegador:</p>
            <p><a href='$enlace'>$enlace</a></p>
        </div>

        <p>Si no solicitaste este registro, puedes ignorar este mensaje.</p>

        <div class='footer'>
            <p>Si tienes problemas, contáctanos en <a href='mailto:soporte@goe.com'>soporte@goe.com</a></p>
            <p>© 2025 GOE. Todos los derechos reservados.</p>
        </div>
        </div>
    </div>
    </body>
    </html>
    ";

    // Enviar el correo
    if (enviarCorreo($email, $asunto, $mesage)) {
        header("Location: ../registro.php?status=success&message=Registro exitoso. Revisa tu correo para activar tu cuenta.");
    } else {
        header("Location: ../registro.php?status=error&message=Error al enviar el correo.");
    }
    exit();

}

// Eliminación de usuario
else if (!empty($_GET['id'])) {
    $uDao = new UsuarioDao();
    $mensaje = $uDao->eliminarUsuario($_GET['id']);
    header("Location: ../perfil.php?mensaje=" . $mensaje);
    exit();
}

// Actualización de usuario
else if (isset($_POST['actualizar'])) {
    $uDao = new UsuarioDao();
    $uDto = new UsuarioDto();
    
    $documento = $_POST['documento'];
    $email = $_POST['email'];
    $new_password = $_POST['clave']; // Nueva contraseña ingresada
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Validar dominio del correo en la actualización
    if (!str_ends_with($email, $dominio_valido)) {
        header("Location: ../perfil.php?status=error&message=El correo debe ser institucional (@ctjfr.edu.co)");
        exit();
    }

    // Obtener la contraseña actual desencriptada desde la BD
    $cnn = Conexion::getConexion();
    $stmt = $cnn->prepare("SELECT AES_DECRYPT(clave, 'SENA') AS clave_actual FROM usuario WHERE documento = :documento");
    $stmt->bindParam(':documento', $documento);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        header("Location: ../perfil.php?status=orange&message=Usuario no encontrado.");
        exit();
    }

    $clave_actual = $row['clave_actual']; // Contraseña actual desencriptada

    // Verificar si la nueva contraseña es diferente a la actual
    if ($new_password !== $clave_actual) {
        // Si la contraseña cambia, actualizar y cerrar sesión
        $cerrarSesion = true;
    } else {
        // Si la contraseña no cambia, mantener sesión activa
        $cerrarSesion = false;
    }

    // Manejo de la imagen de perfil
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $uploadDir = "../uploads/";
        $move = "uploads/";
        $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $newurl = $move . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                $foto = $fileName;
            } else {
                header("Location: ../perfil.php?status=error&message=Error al subir la imagen.");
                exit();
            }
        } else {
            header("Location: ../perfil.php?status=error&message=Formato de imagen no permitido.");
            exit();
        }
    } else {
        $cnn = Conexion::getConexion();
        $stmt = $cnn->prepare("SELECT foto FROM usuario WHERE documento = :documento");
        $stmt->bindParam(':documento', $documento);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $foto = $row['foto']; // Mantener la foto existente // Conservar la imagen actual si no se sube una nueva
    }

    // Asignar valores al DTO
    $uDto->setDocumento($documento);
    $uDto->setRol($_POST['id_rol']);
    $uDto->setEmail($email);
    $uDto->setClave($new_password);
    $uDto->setTD($_POST['tipo_doc']);
    $uDto->setNombre1($_POST['nombre1']);
    $uDto->setNombre2($_POST['nombre2']);
    $uDto->setApellido1($_POST['apellido1']);
    $uDto->setApellido2($_POST['apellido2']);
    $uDto->setTelefono($telefono);
    $uDto->setDireccion($direccion);
    $uDto->setFoto($foto);
    $uDto->setGrado($_POST['grado']);

    // Guardar cambios en la BD
    $mensaje = $uDao->modificarUsuario($uDto);

    if ($cerrarSesion) {
        session_unset();
        session_destroy();
        header("Location: ../go.php?status=success&message=Contraseña cambiada correctamente. Inicia sesión nuevamente.");
        exit();
    } else {
        header("Location: ../perfil.php?mensaje=" . $mensaje);
        exit();
    }
}

if (isset($_POST['eliminar_foto'])) {
    $documento = $_POST['documento'];

    // Obtener la foto actual
    $cnn = Conexion::getConexion();
    $stmt = $cnn->prepare("SELECT foto FROM usuario WHERE documento = :documento");
    $stmt->bindParam(':documento', $documento);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['foto']) && $row['foto'] != 'user.png') {
        $fotoPath = "../uploads/" . $row['foto'];
        
        // Eliminar el archivo si existe
        if (file_exists($fotoPath)) {
            unlink($fotoPath);
        }
    }

    // Actualizar la base de datos para dejar la imagen vacía o con una por defecto
    $stmt = $cnn->prepare("UPDATE usuario SET foto = 'user.png' WHERE documento = :documento");
    $stmt->bindParam(':documento', $documento);
    
    if ($stmt->execute()) {
        header("Location: ../perfil.php?status=success&message=Foto eliminada correctamente.");
    } else {
        header("Location: ../perfil.php?status=error&message=No se pudo eliminar la foto.");
    }
    exit();
}
?>
