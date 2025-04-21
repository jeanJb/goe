<?php
require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/UsuarioDto.php';
require '../Utilidades/conexion.php';

/* var_dump($_POST);
die(); */
//usuario

if(isset($_POST['registro'])){
$uDao = new UsuarioDao();
$uDto = new UsuarioDto();
$uDto->setDocumento($_POST['documento']);
$uDto->setRol($_POST['id_rol']);
$uDto->setEmail($_POST['email']);
$uDto->setClave($_POST['clave']);
$uDto->setTD($_POST['tipo_doc']);
$uDto->setNombre1($_POST['nombre1']);
$uDto->setNombre2($_POST['nombre2']);
$uDto->setApellido1($_POST['apellido1']);
$uDto->setApellido2($_POST['apellido2']);
$uDto->setTelefono($_POST['telefono']);
$uDto->setDireccion($_POST['direccion']);
$uDto->setFoto($_POST['foto'] ?? null);
$uDto->setGrado($_POST['grado'] ?? null);
$mensaje = $uDao->registrarUsuarioDir($uDto);

header("Location: ../go.php?mensaje=".$mensaje);

}
else if ($_GET['id']!=NULL){
    $uDao = new UsuarioDao();
    $mensaje = $uDao->eliminarUsuario($_GET['id']);
    header("Location:../perfil.php?mensaje=".$mensaje);

}else if (isset($_POST['actualizar'])) {
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
        header("Location: ../ModDocente/perfil.php?status=orange&message=Usuario no encontrado.");
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
        $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileName = basename($_FILES["foto"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                $foto = $fileName;
            } else {
                header("Location: ../ModDocente/perfil.php?status=error&message=Error al subir la imagen.");
                exit();
            }
        } else {
            header("Location: ../ModDocente/perfil.php?status=error&message=Formato de imagen no permitido.");
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
        header("Location: ../ModDocente/perfil.php?mensaje=" . $mensaje);
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
        header("Location: ../ModDocente/perfil.php?status=success&message=Foto eliminada correctamente.");
    } else {
        header("Location: ../ModDocente/perfil.php?status=error&message=No se pudo eliminar la foto.");
    }
    exit();
}
?>