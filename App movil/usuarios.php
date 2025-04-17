<?php
session_start();

if (!isset($_SESSION['documento']) || !isset($_SESSION['token_sesion']) || $_SESSION['id_rol'] != 104) {
    header("Location:./index.html");
}

// Obtener el token de la base de datos
require 'Utilidades/conexion.php';
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
$usuarios = $uDao->ListarTodos();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="IMG/logos/goe03.png" type="image/png">
    <title>GOE</title>
    <script src="JS/search.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="STYLES/diseno.css">
    <script>
        if (localStorage.getItem('dark-mode') === 'enabled') {
            document.documentElement.classList.add('dark');
        }

        function confirmar(event) {
            if (!confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                event.preventDefault();
            }
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
    
                    <li class="nav-link enfoque">
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
        <div class="text">Gestión de Usuarios</div>
        <br>
        <div class="colordiv">
            <input type="text" id="buscador" placeholder="Buscar observacion..." onkeyup="buscarEnTablas()">
            <br>
            <br>
            <table id="user-table">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <th>Curso</th>
                        <th>Rol</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
    
                <tbody>
                    <?php    
                        foreach ($usuarios as $index => $usuario) {?>
                            <tr>
                                <td><?php echo $usuario['documento']; ?></td>
                                <td><?php echo $usuario['nombre1']; ?> <td><?php echo $usuario['nombre2']; ?></td></td>
                                <td><?php echo $usuario['apellido1']; ?> <?php echo $usuario['apellido2']; ?></td>
                                <td><?php echo $usuario['email']; ?></td>
                                <td><?php echo $usuario['grado']; ?></td>
                                <td><?php echo $usuario['nom_rol'];?></td>
                                <td>
                                    <a href="controladores/controlador.usuarios.php?id=<?php echo $usuario['documento'];?>" onclick=" return confirmar(event);" class="button_2">
                                        <i class='bx bx-trash op'></i>
                                    </a>
                                </td>
                            </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="JS/script.js"></script>
</body>
</html>
