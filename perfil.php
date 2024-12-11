<?php
session_start();

if (!isset($_SESSION['documento'])) {
    header("Location:./index.html");
    exit();
}

require 'ModeloDAO/UsuarioDao.php';
require 'ModeloDTO/UsuarioDto.php';
require 'Utilidades/conexion.php';

$uDao = new UsuarioDao();

// Obtenemos los datos del usuario
$usuario = $uDao->obtenerUsuario($_SESSION['documento']);
$u = $uDao->user($_SESSION['documento']);

// Validar si los datos están disponibles
if (!$usuario || !$u) {
    echo "<h1>Error: Usuario no encontrado</h1>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="STYLES/diseño.css">
    <link rel="stylesheet" href="STYLES/perfil.css">
    <title>GOE</title>
</head>
<body>
<div class="menu colordis">
    <div class="title">
        <h1>GOE</h1>
        <img src="IMG/GOE.jpg" alt="">
    </div>
    <ul>
        <li><a href="home.php"><img src="IMG/home.svg" alt=""><p>Home</p></a></li>
        <li><a href="observadores.php"><img src="IMG/info.svg" alt=""><p>Observador</p></a></li>
        <li><a href="asistencia.php"><img src="IMG/inbox.svg" alt=""><p>Asistencia</p></a></li>
        <li><a href="intro.php"><img src="IMG/user.svg" alt=""><p>Login</p></a></li>
    </ul>
</div>

<nav class="nav colordis">
    <h1 style="float: left; margin: 12px 0 10px 10px;">
        <?php echo htmlspecialchars($u['nombre1']) . " " . htmlspecialchars($u['apellido1']); ?>
    </h1>
    <ul>
        <li><a href="alert.html"><img src="IMG/message-circle.svg" alt=""></a></li>
        <li><a href="perfil.php"><img src="IMG/user.svg" alt="" style="border: 1px #50c6e3 solid;"></a></li>
        <li><a href="exit.php"><img src="IMG/exit.svg" alt=""></a></li>
    </ul>
</nav>

<div class="contenido">
    <div class="perfil colordiv">
        <div class="pp">
            <div class="img">
                <img src="./IMG/user.svg" alt="">
            </div>
            <center><h2><?php echo $usuario['nombre1'] . " " . $usuario['apellido1']; ?></h2></center>
            <center><h3 style="margin-bottom: 8px;"><?php echo $usuario['nom_rol']; ?></h3></center>
            <center><h3>CURSO: <?php echo $usuario['grado']; ?></h3></center>
            <br>
            <center><a href="#openModal" class="button">ACTUALIZAR PERFIL</a></center>
        </div>

        <div style="width: 35%; float: left;">
            <h1>Datos Adicionales</h1>
            <br>
            <h3>Documento:</h3>
            <h4><?php echo $usuario['documento']; ?></h4>
            <br>
            <h3>Email:</h3>
            <h4><?php echo $usuario['email']; ?></h4>
            <br>
            <h3>Teléfono:</h3>
            <h4><?php echo $usuario['telefono']; ?></h4>
            <br>
            <h3>Dirección:</h3>
            <h4><?php echo $usuario['direccion']; ?></h4>
        </div>
    </div>

    <div class="materia colordiv">
        <h1>MATERIAS</h1>
        <br>
        <h3>Ciencias</h3>
        <h3>Química</h3>
        <h3>Física</h3>
    </div>
    <div class="cursos colordiv"></div>
</div>
</body>
</html>


<div id="openModal" class="modal_asis">
    <div class="colordiv">
        <a href="#close" id="close" class="close">X</a>
        <h2 style="text-align: center;">Actualizar Perfil</h2>
        <br>
        <form action="controladores/controlador.usuariosAdmin.php" method="POST">
            <br>
            <h3>Documento:</h3>
            <input type="number" name="documento" id="" value="<?php echo $usuario['documento']; ?>" readonly>

            <br>
            <h3>ROL:</h3>
            <input type="text" name="" id="" value="<?php echo $usuario['nom_rol']; ?>" readonly>

            <br>
            <h3>Email:</h3>
            <input type="email" name="email" id="" value="<?php echo $usuario['email']; ?>" required>
            
            <br>
            <h3>Contraseña:</h3>
            <input type="password" name="clave" id="" value="<?php echo $usuario['clave']; ?>" required>

            <br>
            <h3>Tipo Documento:</h3>
            <input type="text" name="tipo_doc" id="" value="<?php echo $usuario['tipo_doc']; ?>" readonly>

            <br>
            <h3>Primer Nombre:</h3>
            <input type="text" name="nombre1" id="" value="<?php echo $usuario['nombre1']; ?>" required>

            <br>
            <h3>Segundo Nombre:</h3>
            <input type="text" name="nombre2" id="" value="<?php echo $usuario['nombre2']; ?>">

            <br>
            <h3>Primer Apellido:</h3>
            <input type="text" name="apellido1" id="" value="<?php echo $usuario['apellido1']; ?>" required>

            <br>
            <h3>Segundo Apellido:</h3>
            <input type="text" name="apellido2" id="" value="<?php echo $usuario['apellido2']; ?>" >

            <br>
            <h3>Telefono:</h3>
            <input type="number" name="telefono" id="" value="<?php echo $usuario['telefono']; ?>">

            <br>
            <h3>Direccion:</h3>
            <input type="text" name="direccion" id="" value="<?php echo $usuario['direccion']; ?>" required>

            <br>
            <h3>Foto:</h3>
            <input type="file" name="foto" id="">
            
            <br>
            <h3>Grado:</h3>
            <input type="text" name="grado" id="" value="<?php echo $usuario['grado']; ?>" readonly>
            <br>

            <button class="button" type="submit" name="actualizar" id="actualizar">ACTUALIZAR</button>
        </form>
    </div>
</div>
