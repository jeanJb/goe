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

// Obtenemos los datos del usuario
$usuario = $uDao->obtenerUsuario($_SESSION['documento']);
$estudiantes = $uDao->EstudiantesCurso($usuario['grado']);
$materias = $uDao->MateriasAsignadasDocente($usuario['documento']);

// Validar si los datos están disponibles
if (!$usuario || empty($usuario['documento'])) {
    echo "<h1>Error: Usuario no encontrado</h1>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logos/goe03.png" type="image/png">
    <title>GOE</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../STYLES/diseno.css"><script>
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
                    <li class="nav-link house">
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
                    <img src="../uploads/<?php echo $usuario['foto']; ?>" alt="Imagen de perfil">
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
    
        <div class="modulos">
            <div class="materia colordiv">
                <h1>Mis Materias</h1>
                <br>
                <?php if (!empty($materias)) { ?>
                            <?php foreach ($materias as $materia) { ?>
                                <h3><?php echo $materia['nomb_mat']; ?></td>
                                <br>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <h2>No se encontraron materias.</h2>
                            </tr>
                        <?php } ?>
            </div>
            
            <div class="cursos colordiv">
                <h1>Mis Estudiantes</h1>
                <table id="user-table">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Telefono</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($estudiantes)) { ?>
                            <?php foreach ($estudiantes as $estudiante) { ?>
                                <tr>
                                    <td><?php echo $estudiante['documento']; ?></td>
                                    <td><?php echo $estudiante['nombre1'] . " " . $estudiante['apellido1']; ?></td>
                                    <td><?php echo $estudiante['email']; ?></td>
                                    <td><?php echo $estudiante['telefono']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">No tienes un curso asignado.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    
    <script src="../JS/script.js"></script>
</body>
</html>

<!-- Modal -->

<div id="openModal" class="modal_asis">
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <h2 style="text-align: center;">Actualizar Perfil</h2>
        <br>
        <form action="../controladores/controlador.usuariosDoc.php" method="POST" enctype="multipart/form-data">
            <br>
            <h3>Documento:</h3>
            <input type="number" name="documento"  value="<?php echo $usuario['documento']; ?>" readonly>

            <br>
            <h3>ROL:</h3>
            <input type="text" name=""  value="<?php echo $usuario['nom_rol']; ?>" readonly>

            <br>
            <h3>Email:</h3>
            <input type="email" name="email"  value="<?php echo $usuario['email']; ?>" readonly>
            
            <br>
            <h3>Contraseña:</h3>
            <input type="password" name="clave"  value="<?php echo $usuario['clave']; ?>" required>

            <br>
            <h3>Tipo Documento:</h3>
            <input type="text" name="tipo_doc"  value="<?php echo $usuario['tipo_doc']; ?>" readonly>

            <br>
            <h3>Primer Nombre:</h3>
            <input type="text" name="nombre1"  value="<?php echo $usuario['nombre1']; ?>" required>

            <br>
            <h3>Segundo Nombre:</h3>
            <input type="text" name="nombre2"  value="<?php echo $usuario['nombre2']; ?>">

            <br>
            <h3>Primer Apellido:</h3>
            <input type="text" name="apellido1"  value="<?php echo $usuario['apellido1']; ?>" required>

            <br>
            <h3>Segundo Apellido:</h3>
            <input type="text" name="apellido2"  value="<?php echo $usuario['apellido2']; ?>" >

            <br>
            <h3>Telefono:</h3>
            <input type="number" name="telefono"  value="<?php echo $usuario['telefono']; ?>">

            <br>
            <h3>Direccion:</h3>
            <input type="text" name="direccion"  value="<?php echo $usuario['direccion']; ?>" required>

            <br>
            <h3>Foto:</h3>
            <input type="file" name="foto" >
            <button type="submit" name="eliminar_foto" class="button">Eliminar Foto</button>

            <br>
            <h3>Grado:</h3>
            <input type="text" name="grado"  value="<?php echo $usuario['grado']; ?>" readonly>
            <br>

            <button class="button" type="submit" name="actualizar" id="actualizar">ACTUALIZAR</button>
        </form>
    </div>
</div>

