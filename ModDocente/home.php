<?php
session_start();

if (!isset($_SESSION['documento']) || !isset($_SESSION['token_sesion']) || $_SESSION['id_rol'] != 102) {
    header("Location:../index.html");
}

// Obtener el token de la base de datos
require '../Utilidades/conexion.php';
$cnn = Conexion::getConexion();
$stmt = $cnn->prepare("SELECT token_sesion FROM usuario WHERE documento = :documento");
$stmt->bindParam(':documento', $_SESSION['documento'], PDO::PARAM_INT);
$stmt->execute();
$valor = $stmt->fetch(PDO::FETCH_ASSOC);

// Si el token en la BD no coincide con el de la sesión, cerrar sesión
if (!$valor || $valor['token_sesion'] !== $_SESSION['token_sesion']) {
    session_unset();
    session_destroy();
    header("Location: ../go.php?error=Tu sesión ha expirado. Inicia sesión nuevamente.");
    exit();
}

require '../ModeloDAO/UsuarioDao.php';
require '../ModeloDTO/UsuarioDto.php';

$uDao = new UsuarioDao();

$u = $uDao->user($_SESSION['documento']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logos/goe03.png" type="image/png">
    <title>GOE</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../STYLES/diseno.css">
    <script>
        if (localStorage.getItem('dark-mode') === 'enabled') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body>

    <nav class="sidebar close">
        <img src="../IMG/logos/goe03.png" alt="" class="imag">
        <header>
            <div class="text logo">
                <span class="name">GOE</span>
                <span class="eslogan"></span>
            </div>
            <i class="bx bx-menu toggle"></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <!-- <li class="search-box">
                    <i class="bx bx-search icon"></i>
                    <input type="text" name="" id="" placeholder="Buscar...">
                </li> -->

                <ul class="menu-liks">
                    <li class="nav-link house enfoque">
                        <a href="home.php">
                            <i class="bx bx-home-alt icon"></i>
                            <span class="text nav-text">Home</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="observadores.php">
                            <i class="bx bx-book icon"></i>
                            <span class="text nav-text">Observadores</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="asistencias.php">
                            <i class="bx bx-calendar-week icon"></i>
                            <span class="text nav-text">Asistencias</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="perfil.php">
                        <i class="bx bx-user-circle icon"></i>
                        <span class="text nav-text">Perfil</span>
                    </a>
                </li>

                <li class="">
                    <a href="exit.php">
                        <i class="bx bx-log-out icon"></i>
                        <span class="text nav-text">Cerrar Sesión</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class="bx bx-moon icon moon"></i>
                        <i class="bx bx-sun icon sun"></i>
                    </div>
                    <span class="mode-text text">Ligth Mode</span>
                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
                
            </div>

        </div>

    </nav>

    <div class="home">
        <div class="text">
            ¡Hola, Bienvenid@, Profesor <?php echo $u['nombre1'].' '. $u['nombre2'].' '.$u['apellido1'].' '.$u['apellido2']?>!
            <br>

        </div>

        <div class="filtros">
            <h1 class="frase">Un espacio digital diseñado para estudiantes, docentes y directivos.</h1>
        
            <img src="../IMG/logos/Wallpaper_GOE.JPG" alt="" class="wallpaper">
        </div>
        
        <div class="filtros complement">
            <div class="card">
                <span class="small-text">Descarga la App ahora!</span>
                <span class="titulo">GOE</span>
                <span class="desc">📲 ¡Lleva tu gestión escolar a otro nivel! Descarga nuestra app móvil y accede a asistencias, directorios y observadores desde cualquier lugar. 📥✨</span>
                <div class="botons">
                    <a href="#" class="boton">
                    <span class="icons"><i class='bx bxl-play-store icon app'></i></span>
                    <div class="boton-text google">
                        <span>Obtener en la</span>
                        <span>Google Play</span>
                    </div>
                    </a>
                    <a href="#" class="boton">
                    <span class="icons"><i class='bx bxl-apple icon app'></i></span>
                    <div class="boton-text apple icon">
                        <span>Descargar en la</span>
                        <span>App Store</span>
                    </div>
                    </a>
                </div>
            </div>
    
            <div class="cookie-card">
                <span class="title">🎓 Conéctate y administra</span>
                <p class="description">Bienvenido a GOE, el espacio donde estudiantes, docentes y directivos tienen todo a su alcance. Registra asistencias en segundos, accede a directorios actualizados y gestiona observadores estudiantiles de forma sencilla. Simplificamos la administración escolar para que puedas enfocarte en lo que realmente importa: la educación. ¡Explora una nueva forma de conectar y gestionar tu comunidad académica!<a href="#">Read cookies policies</a>. </p>
            </div>
        </div>
    </div>
    
    <script src="../JS/script.js"></script>
</body>
</html>
