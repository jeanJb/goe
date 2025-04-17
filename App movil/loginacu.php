<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
session_start();
/* if(isset($_SESSION['documento'])){
    header('Location: index.php');
    exit();  
    } */

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header('Pragma: no-cache');
    header("Expires:0");

require 'Utilidades/conexion.php';
$cnn= Conexion::getConexion();
$estu= $_POST['txtdoc'];
$acu= $_POST['txtacu'];
$sentencia = $cnn->prepare("SELECT * FROM directorio WHERE documento = ?;");
$sentencia->execute([$estu]);
$valor = $sentencia->fetch(PDO::FETCH_OBJ);

if ($valor === FALSE) {
    // Si no encuentra el documento, muestra la alerta y redirige
    /* echo "<script> 
    Swal.fire({
        icon: 'error',
        title: 'Inicio no válido',
        text: 'El documento del estudiante es incorrecto, intenta nuevamente',
        background: '#f7f7f7',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Volver a intentar',
        customClass: {
            title: 'swal-title',
            popup: 'swal-popup',
        }
    }).then(() => {
        window.location.href = 'acudientes.php?error=1';
    });
    </script>"; */
    header('Location: acudientes.php?error=3');
    exit();
} else {
    // Verificamos si la contraseña coincide
    if ($acu === $valor->doc_acu) {
        // Si la contraseña es correcta, iniciar sesión
        $_SESSION['documento'] = $valor->documento;
        $_SESSION['doc_acu'] = $valor->doc_acu;
        header('Location: ModAcudiente/home.php');
        exit();
    } else {
        // Si la contraseña no coincide
        /* echo "<script> 
        Swal.fire({
            icon: 'error',
            title: 'Inicio no válido',
            text: 'La contraseña es incorrecta, intenta nuevamente',
            background: '#f7f7f7',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Volver a intentar',
            customClass: {
                title: 'swal-title',
                popup: 'swal-popup',
            }
        }).then(() => {
            window.location.href = 'acudientes.php?error=2';
        });
        </script>"; */
        header('Location: acudientes.php?error=2');
        exit();
    }
}


?>