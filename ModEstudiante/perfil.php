<?php
session_start();

if (!isset($_SESSION['documento']) || !isset($_SESSION['token_sesion']) || $_SESSION['id_rol'] != 101) {
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

// Llamada al método para listar las observaciones del usuario logueado
$user = $uDao->listarUsuariosper($_SESSION['documento']);
$curso = $uDao->Cursoper($_SESSION['documento']);
$usuario = $uDao->obtenerUsuario($_SESSION['documento']);


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
        <div class="text">Mi Perfil</div>

        <!-- Contenido -->

        <?php
        foreach ($user as $us) { ?>
    
        <div class="perfil colordiv">
            <div class="pp">
                <div class="img">
                    <img src="../uploads/<?php echo $usuario['foto']; ?>" alt="Imagen de perfil">
                </div>
                <center><h2><?php echo $us['nombre1']; ?> <?php echo $us['apellido1']; ?></h2></center>
                <center><h3 style="margin-bottom: 8px;"><?php echo $us['nom_rol']; ?></h3></center>
                <center><h3>CURSO:   <?php echo $us['grado'] ?: 'Sin asignar'; ?></h3></center>
                <center><a href="#openModal" class="button">ACTUALIZAR PERFIL</a></center>
            </div>
            <!--h6>Nombre:</!--h6>
            <h3>Jean Paul Martinez</h3>
            <br-->
            <div style="width: 35%; float: left;">
                <h1>Datos Adicionales</h1>
                <br>
                <h3>Documento:</h3>
                <h4><?php echo $us['documento']; ?></h4>
                <br>
                <h3>Email:</h3>
                <h4><?php echo $us['email']; ?></h4>
                <br>
                <h3>Telefono:</h3>
                <h4><?php echo $us['telefono']; ?></h4>
                <br>
                <h3>Direccion:</h3>
                <h4><?php echo $us['direccion']; ?></h4>
            </div>

        </div>

        <?php
        } ?>


        <div class="modulos">
            <div class="materia colordiv">
                <h1 style="text-align: center;">MATERIAS</h1>
                <br>
                <table id="user-table">
                    <thead>
                        <tr>
                            <td>Materia</td>
                            <td>Semana/Dia</td>
                            <td>Incio</td>
                            <td>Final</td>
                        </tr>
                    </thead>
    
                    <tbody>
                    <?php
                    foreach ($curso as $cus) { ?>
                        <tr>
                            <td><?php echo $cus['nomb_mat']; ?></td>
                            <td><?php echo $cus['dia']; ?></td>
                            <td><?php echo $cus['Fecha_inicio']; ?></td>
                            <td><?php echo $cus['Fecha_fin']; ?></td>
                        </tr>
                        <?php
                    } ?>
                    </tbody>
                </table>
            </div>
            
    
            <?php
            foreach ($user as $us) { ?>
            <div class="cursos colordiv" style="overflow: auto;">
                            <!-- Datos tabla directorio -->
                <!-- Solo para estudiantes -->
                <div style="width: 35%; float: left;">
                    <br>
                    <h3>RH:</h3>
                    <h4><?php echo $us['rh_estudiante']; ?></h4>
                    <br>
                    <h3>EPS:</h3>
                    <h4><?php echo $us['eps']; ?></h4>
                    <br>
                    <h3>Fecha de Nacimiento:</h3>
                    <h4><?php echo $us['fecha_naci']; ?></h4>
                    <br>
                    <h3>Enfermedades:</h3>
                    <h4><?php echo $us['enfermedades']; ?></h4>
                    <br>
                    <h3>Nombre Acudiente:</h3>
                    <h4><?php echo $us['nom_acu']; ?></h4>
                    <br>
                    <h3>Telefono Acudiente</h3>
                    <h4><?php echo $us['telefono_acu']; ?></h4>
                    <br>
                    <h3>Documento Acudiente:</h3>
                    <h4><?php echo $us['doc_acu']; ?></h4>
                    <br>
                    <h3>Email Acudiente:</h3>
                    <h4><?php echo $us['email_acu']; ?></h4>
                    <br>
                    
                    <a href="#openModal1" class="button">ACTUALIZAR</a>
                </div>
            </div>
        </div>
        <?php
        } ?>
        </div>
    
    <script src="../JS/script.js"></script>
</body>
</html>

<!-- Modal para actualizar perfil -->

<div id="openModal" class="modal_asis">
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <h2 style="text-align: center;">Actualizar Perfil</h2>
        <br>
        <form action="../controladores/controlador.usuarios.php" method="POST" enctype="multipart/form-data">
            <br>
            <h3>Documento:</h3>
            <input type="number" name="documento" id="" value="<?php echo $us['documento']; ?>" readonly>

            <br>
            <h3>ROL:</h3>
            <input type="text" name="" id="" value="<?php echo $us['nom_rol']; ?>" readonly>

            <br>
            <h3>Email:</h3>
            <input type="email" name="email" id="" value="<?php echo $us['email']; ?>" readonly>
            
            <br>
            <h3>Contraseña:</h3>
            <input type="password" name="clave" id="" value="<?php echo $us['pass']; ?>" required>

            <br>
            <h3>Tipo Documento:</h3>
            <input type="text" name="tipo_doc" id="" value="<?php echo $us['tipo_doc']; ?>" readonly>

            <br>
            <h3>Primer Nombre:</h3>
            <input type="text" name="nombre1" id="" value="<?php echo $us['nombre1']; ?>" required>

            <br>
            <h3>Segundo Nombre:</h3>
            <input type="text" name="nombre2" id="" value="<?php echo $us['nombre2']; ?>">

            <br>
            <h3>Primer Apellido:</h3>
            <input type="text" name="apellido1" id="" value="<?php echo $us['apellido1']; ?>" required>

            <br>
            <h3>Segundo Apellido:</h3>
            <input type="text" name="apellido2" id="" value="<?php echo $us['apellido2']; ?>" >

            <br>
            <h3>Telefono:</h3>
            <input type="number" name="telefono" id="" value="<?php echo $us['telefono']; ?>">

            <br>
            <h3>Direccion:</h3>
            <input type="text" name="direccion" id="" value="<?php echo $us['direccion']; ?>" required>

            <br>
            <h3>Foto:</h3>
            <input type="file" name="foto" id="">
            <br>
            <button type="submit" name="eliminar_foto" class="button">Eliminar Foto</button>
            
            <br>
            <h3>Grado:</h3>
            <input type="text" name="grado" id="" value="<?php echo $usuario['grado']?: 'Sin asignar'; ?>" readonly>
            <br>

            <button class="button" type="submit" name="actualizar" id="actualizar">ACTUALIZAR</button>
        </form>
    </div>
</div>

<!-- Modal para actualizar los datos del estudiante -->

<div id="openModal1" class="modal_asis">                                                
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <h2 style="text-align: center;">Actualizar Datos del Directorio</h2>
        <br>
        <form action="../controladores/controlador.directorio.php" method="POST">
            <br>
            <h3>Mi Documento:</h3>
            <input type="text" name="documento" id="" value="<?php echo $us['documento']; ?>" readonly>

            <br>
            <h3>RH:</h3>
            <input type="text" name="rh_estudiante" id="" min="2" max="3" value="<?php echo $us['rh_estudiante']; ?>" required>

            <br>
            <h3>EPS:</h3>
            <input type="text" name="eps" id="" value="<?php echo $us['eps']; ?>" required>

            <br>
            <h3>Fecha de Nacimiento:</h3>
            <input type="date" name="fecha_naci" id="" value="<?php echo $us['fecha_naci']; ?>" required>
            
            <br>
            <h3>Enfermedades:</h3>
            <input type="text" name="enfermedades" id="" value="<?php echo $us['enfermedades'] ?? 'N/A'; ?>">

            <br>
            <h3>Nombre Acudiente:</h3>
            <input type="text" name="nom_acu" id="" value="<?php echo $us['nom_acu']; ?>" required>

            <br>
            <h3>Telefono Acudiente:</h3>
            <input type="number" name="telefono_acu" id="" value="<?php echo $us['telefono_acu']; ?>" required>

            <br>
            <h3>Documento Acudiente:</h3>
            <input type="number" name="doc_acu" id="" value="<?php echo $us['doc_acu']; ?>" required>

            <br>
            <h3>Email Acudiente:</h3>
            <input type="email" name="email_acu" id="" value="<?php echo $us['email_acu']; ?>" required>

            <button class="button" type="submit" name="actualizar" id="actualizar">ACTUALIZAR</button>
        </form>
    </div>
</div>

<?php
    if(isset($_GET['mensaje'])){
        ?>

    <div class="row"> <br><br>
        <div class="col-md-6"></div>
        <div class="col-md-4 text-center">
            <h4><?php echo $mensaje = $_GET['mensaje']?>
            </h4>
        </div>
        <div class="col-md-5"></div>
    </div>

<?php } ?>