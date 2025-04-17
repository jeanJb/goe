<!-- <?php
session_start();

if (!isset($_SESSION['documento']) || !isset($_SESSION['token_sesion']) || $_SESSION['id_rol'] != 104) {
    header("Location:./index.html");
}

// Obtener el token de la base de datos
require 'Utilidades/Conexion.php';
$cnn = Conexion::getConexion();
$stmt = $cnn->prepare("SELECT token_sesion FROM usuario WHERE documento = :documento");
$stmt->bindParam(':documento', $_SESSION['documento'], PDO::PARAM_INT);
$stmt->execute();
$valor = $stmt->fetch(PDO::FETCH_ASSOC);

// Si el token en la BD no coincide con el de la sesión, cerrar sesión
if (!$valor || $valor['token_sesion'] !== $_SESSION['token_sesion']) {
    session_unset();
    session_destroy();
    header("Location: go.php?error=Tu sesión ha expirado. Inicia sesión nuevamente.");
    exit();
}

require 'ModeloDAO/UsuarioDao.php';
require 'ModeloDTO/UsuarioDto.php';

$uDao = new UsuarioDao();

// Obtenemos los datos del usuario
$usuario = $uDao->obtenerUsuario($_SESSION['documento']);
$u = $uDao->user($_SESSION['documento']);

// Validar si los datos están disponibles
if (!$usuario || !$u) {
    echo "<h1>Error: Usuario no encontrado</h1>";
    exit();
}
?> -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="IMG/logos/goe03.png" type="image/png">
    <title>GOE</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="STYLES/diseno.css"><script>
        if (localStorage.getItem('dark-mode') === 'enabled') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body>

    <nav class="sidebar close">
        <img src="IMG/logos/goe03.png" alt="" class="imag">
        <header>
            <div class="text logo">
                <span class="name">GOE</span>
                <span class="eslogan"></span>
            </div>
            <i class="bx bx-menu toggle"></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-liks">
                    <li class="nav-link house">
                        <a href="home.php">
                            <i class="bx bx-home-alt icon"></i>
                            <span class="text nav-text">Inicio</span>
                        </a>
                    </li>
    
                    <li class="nav-link">
                        <a href="observadores.php">
                            <i class="bx bx-book icon"></i>
                            <span class="text nav-text">Observadores de Estudiantes</span>
                        </a>
                    </li>
    
                    <li class="nav-link">
                        <a href="asistencias.php">
                            <i class="bx bx-calendar-week icon"></i>
                            <span class="text nav-text">Asistencia de Estudiantes</span>
                        </a>
                    </li>
    
                    <li class="nav-link">
                        <a href="curso_estu.php">
                            <i class="bx bx-objects-horizontal-right icon"></i>
                            <span class="text nav-text">Asignar Curso a Estudiantes</span>
                        </a>
                    </li>
    
                    <li class="nav-link">
                        <a href="curso_doc.php">
                            <i class="bx bx-objects-horizontal-left icon"></i>
                            <span class="text nav-text">Asignar Cursos y Materias</span>
                        </a>
                    </li>
    
                    <li class="nav-link">
                        <a href="pro_mat.php">
                            <i class="bx bx-book-content icon"></i>
                            <span class="text nav-text">Materias de los Docentes</span>
                        </a>
                    </li>
    
                    <li class="nav-link">
                        <a href="usuarios.php">
                            <i class="bx bx-user icon"></i>
                            <span class="text nav-text">Gestión de Usuarios</span>
                        </a>
                    </li>
    
                    <li class="nav-link">
                        <a href="intro.php">
                            <i class="bx bx-user-plus icon"></i>
                            <span class="text nav-text">Registrar Usuarios</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="email.php">
                                <i class='bx bx-envelope icon'></i>
                                <span class="text nav-text">Enviar correos</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="enfoque">
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
        <div class="text">Perfil</div>

        <!-- Contenido -->

        <div class="perfil colordiv">
            <div class="pp">
                <div class="img">
                    <img src="uploads/<?php echo $usuario['foto']; ?>" alt="Imagen de perfil">
                </div>
                <center><h2><?php echo $usuario['nombre1'] . " " . $usuario['apellido1']; ?></h2></center>
                <center><h3 style="margin-bottom: 8px;"><?php echo $usuario['nom_rol']; ?></h3></center>
                <center><h3>CURSO: <?php echo $usuario['grado']; ?></h3></center>
                <br>
                <center><a href="#openModal" class="button">ACTUALIZAR PERFIL</a></center>
            </div>
    
            <div class="dates">
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
    
        <!-- <div class="modulos">
            <div class="materia colordiv">
                <h1>MATERIAS</h1>
                <br>
                <h3>Ciencias</h3>
                <h3>Química</h3>
                <h3>Física</h3>
            </div>
            
            <div class="cursos colordiv"></div>
        </div> -->

    </div>
    
    <script src="JS/script.js"></script>
</body>
</html>

<!-- Modal -->

<div id="openModal" class="modal_asis">
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <h2 style="text-align: center;">Actualizar Perfil</h2>
        <br>
        <form action="controladores/controlador.usuariosAdmin.php" method="POST" enctype="multipart/form-data">
            <br>
            <h3>Documento:</h3>
            <input type="number" name="documento" id="" value="<?php echo $usuario['documento']; ?>" readonly>

            <br>
            <h3>ROL:</h3>
            <input type="text" name="" id="" value="<?php echo $usuario['nom_rol']; ?>" readonly>

            <br>
            <h3>Email:</h3>
            <input type="email" name="email" id="" value="<?php echo $usuario['email']; ?>" readonly>
            
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
            <button type="submit" name="eliminar_foto" class="button">Eliminar Foto</button>
            
            <br>
            <h3>Grado:</h3>
            <input type="text" name="grado" id="" value="<?php echo $usuario['grado']; ?>" readonly>
            <br>

            <button class="button" type="submit" name="actualizar" id="actualizar">ACTUALIZAR</button>
            <br>
        </form>
        <br>
        <br>
        <br>
    </div>
</div>

