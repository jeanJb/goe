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

$u = $uDao->user($_SESSION['documento']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="IMG/logos/goe03.png" type="image/png">
    <title>GOE</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="STYLES/diseno.css">
    <script>
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
    
                    <li class="nav-link enfoque">
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
        <div class="text">
            Registro de Usuarios
            <h6>Registra preferiblemente Administradores y Docentes</h6>
        </div>

        <!-- Contenido -->
        
        <form  class="form colordiv" action="controladores/controlador.usuarios.php" method="POST" onsubmit="validar_usuarios(event)">
            <p class="titles">Registrar Usuarios </p>
            <p class="message">Seleccione adecuadamente el rol del usuario. </p>
            <label>
                <input class="input" name="documento" id="documento" type="text" placeholder="" >
                <span>Documento</span>
            </label>

            <label>
                <h3>Rol</h3>
                <select name="id_rol" id="id_rol" class="select" style="width: auto;">
                    <!-- <option value="101">ESTUDIANTE</option> -->
                    <option value="102">DOCENTE</option>
                    <option value="104">ADMINISTRADOR</option>
                </select>
            </label>
            <label>
                <input class="input" name="email" id="email" type="email" placeholder="">
                <span>Email</span>
            </label> 
                
            <label>
                <input class="input" name="clave" id="clave" type="password" placeholder="">
                <span>Contraseña</span>
            </label>

            <label>
                <h3>Tipo de Documento</h3>
                <select name="tipo_doc" id="tipo_doc" class="select" style="width: auto;">
                    <option value="T.I">T.I</option>
                    <option value="C.C">C.C</option>
                    <option value="C.E">C.E</option>
                </select>
            </label>

            <div class="flex">
                <label>
                    <input class="input" name="nombre1" id="nombre1" type="text" placeholder="">
                    <span>Primer Nombre</span>
                </label>
        
                <label>
                    <input class="input" name="nombre2" id="nombre2" type="text" placeholder="">
                    <span>Segundo Nombre</span>
                </label>
            </div>  

            
                <div class="flex">
                <label>
                    <input class="input" name="apellido1" id="apellido1" type="text" placeholder="">
                    <span>Primer Apellido</span>
                </label>
        
                <label>
                    <input class="input" name="apellido2" id="apellido2" type="text" placeholder="">
                    <span>Segundo Apellido</span>
                </label>
            </div>  
            <label>
                <input class="input" name="telefono" id="telefono" type="number" placeholder="">
                <span>Telefono</span>
            </label>
            <label>
                <input class="input" name="direccion" id="direccion" type="text" placeholder="">
                <span>Direccion</span>
            </label>
<!--             <label>
                <input class="input" name="foto" id="foto" type="file" accept="image*">
                <span>Foto</span>
            </label> -->
            <label>
                <input class="input" name="grado" id="grado" type="number" placeholder="">
                <span>Grado</span>
            </label>

            <button type="submit" class="submit" name="registro" id="registro">Registrar</button>
        </form>
    </div>
    
    <script src="JS/script.js"></script>
        <!-- Contenedor para las alertas -->
    <div id="alert-container"></div>
    <script>
        // Función para mostrar alertas
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');

            // Crear el elemento de la alerta
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
                ${message}
                <span class="close-btn" onclick="this.parentElement.classList.remove('show')">&times;</span>
            `;

            // Agregar la alerta al contenedor
            alertContainer.appendChild(alertDiv);

            // Mostrar la alerta
            setTimeout(() => alertDiv.classList.add('show'), 100);

            // Ocultar la alerta después de 5 segundos
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 500); // Eliminar después de la animación
            }, 5000);
        }

        // Obtener parámetros de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const message = urlParams.get('message');

        // Mostrar alerta según el estado
        if (status && message) {
            showAlert(decodeURIComponent(message), status);
        }
    </script>
</body>
</html>
