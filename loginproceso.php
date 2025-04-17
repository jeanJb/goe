<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header('Pragma: no-cache');
header("Expires:0");

require 'Utilidades/conexion.php';
$cnn = Conexion::getConexion(); // Usa solo una conexión

$email = $_POST['txtemail'];
/* $documento = $_POST['txtdoc']; */
$clave = $_POST['txtpass'];

// Verificar si el usuario existe y está activo
$query = $cnn->prepare("SELECT activo FROM usuario WHERE email = :email LIMIT 1");
$query->bindParam(':email', $email);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_ASSOC);

// Si el usuario no existe
if (!$usuario) {
    header("Location: go.php?status=error&message=El correo no esta registrado en nuestra base de datos, Registrate o intenta nuevamente"); // Error 1: usuario no encontrado
    exit();
}

// Si la cuenta no está activada
if ($usuario['activo'] == 0) {
    header("Location: go.php?status=orange&message=Debes activar tu cuenta desde el correo.");
    exit();
}

// Verificar si el usuario existe en la BD
$stmt = $cnn->prepare("SELECT *, AES_DECRYPT(clave, 'SENA') AS pass FROM usuario WHERE email = :email");
$stmt->bindParam(':email', $email);
//$stmt->bindParam(':documento', $documento);
$stmt->execute();
$valor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$valor){
    // Si no encuentra el documento, redirige con error
    header('Location:go.php?status=error&message=El correo no esta registrado en nuestra base de datos, Registrate o intenta nuevamente'); // Error 1: documento no encontrado
    exit();
} else {
    // Verificamos si la contraseña coincide            
    if ($clave === $valor['pass']) {
        // Generar token de sesión
        $token_sesion = bin2hex(random_bytes(32));

        // Guardar el token en la base de datos
        $stmt = $cnn->prepare("UPDATE usuario SET token_sesion = :token WHERE email = :email");
        $stmt->bindParam(':token', $token_sesion, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Guardar datos en la sesión
        $_SESSION['documento'] = $valor['documento'];
        $_SESSION['id_rol'] = $valor['id_rol'];
        $_SESSION['email'] = $valor['email'];
        $_SESSION['activo'] = $valor['activo'];
        $_SESSION['token_sesion'] = $token_sesion;

        // Redirigir según el rol
        switch ($valor['id_rol']) {
            case 101:
                header('Location: ModEstudiante/home.php');
                exit();
            case 102:
                header('Location: ModDocente/home.php');
                exit();
            case 104:
                header('Location: home.php');
                exit();
            default:
                header('Location: go.php?status=success&message=El rol de tu cuenta no es valido ponte en contacto con coordinacion para solucionar este error.'); // Error 3: Rol no válido
                exit();
        }

    } else {
        // Si la contraseña no coincide
        header('Location: go.php?status=error&message=La contraseña es incorrecta, Intenta nuevamente.'); // Error 2: contraseña incorrecta
        exit();
    }
}
?>