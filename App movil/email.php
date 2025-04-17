<?php
session_start();

if (!isset($_SESSION['documento']) || !isset($_SESSION['token_sesion']) || $_SESSION['id_rol'] != 104) {
    header("Location:./index.html");
    exit();
}

// Obtener el token de la base de datos
require 'Utilidades/conexion.php';
$cnn = Conexion::getConexion();
$stmt = $cnn->prepare("SELECT token_sesion FROM usuario WHERE documento = :documento");
$stmt->bindParam(':documento', $_SESSION['documento'], PDO::PARAM_INT);
$stmt->execute();
$valor = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificación de token
if (!$valor || $valor['token_sesion'] !== $_SESSION['token_sesion']) {
    session_unset();
    session_destroy();
    header("Location: go.php?error=Tu sesión ha expirado. Inicia sesión nuevamente.");
    exit();
}

require 'ModeloDAO/UsuarioDao.php';
$uDao = new UsuarioDao();
$usuarios = $uDao->ListarTodos();

// Mostrar mensaje de éxito/error si existe
$mensaje = '';
if (isset($_GET['exito'])) {
    $mensaje = '<div class="alert success">'.htmlspecialchars($_GET['exito']).'</div>';
} elseif (isset($_GET['error'])) {
    $mensaje = '<div class="alert error">'.htmlspecialchars($_GET['error']).'</div>';
}
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
    <!-- Antes de los otros scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        #id_estudiante {
            display: none;
            margin-top: 10px;
        }
    </style>
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

                    <li class="nav-link enfoque">
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
        <div class="text">Generar correos</div>
        <br>
        <?php echo $mensaje; ?>
        <!-- En la sección del formulario -->
        <div class="colordiv container email">
            <h2>Enviar Correo</h2>
            <form action="Utilidades/generar_email.php" method="POST" onsubmit="return confirm('¿Estás seguro de enviar este correo?');">
                <label for="destinatario">Enviar a:</label>
                <select id="destinatario" name="destinatario" onchange="toggleFields()">
                    <option value="estudiante">Estudiante Específico</option>
                    <option value="curso">Curso Completo</option>
                    <option value="todos">Todos los Cursos</option>
                </select>

                <!-- Selector de curso (visible para estudiante y curso completo) -->
                <div id="cursoContainer">
                    <label for="id_curso">Curso:</label>
                    <select id="id_curso" name="id_curso" class="select2">
                        <?php 
                        $stmt = $cnn->prepare("SELECT grado, salon FROM curso ORDER BY grado");
                        $stmt->execute();
                        $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($cursos as $curso): ?>
                            <option value="<?= htmlspecialchars($curso['grado']) ?>">
                                Grado <?= htmlspecialchars($curso['grado']) ?> - <?= htmlspecialchars($curso['salon']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Selector de estudiante (solo visible para estudiante específico) -->
                <div id="estudianteContainer">
                    <label for="id_estudiante">Estudiante:</label>
                    <select id="id_estudiante" name="id_estudiante" class="select2" disabled>
                        <!-- Se cargará dinámicamente con AJAX -->
                    </select>
                </div>

                <label class="check">
                    <input type="checkbox" name="incluir_acudiente" value="1">
                    <span>Enviar también al acudiente</span>
                </label>

                <input type="text" name="asunto" placeholder="Asunto" required>
                <textarea name="mensaje" placeholder="Escribe tu mensaje aquí..." rows="5" required></textarea>
                <button type="submit" class="button">Enviar</button>
            </form>
        </div>

        <!-- Script para manejar la lógica -->
        <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2({ width: '100%' });

            // Evento para cambiar los select al elegir destinatario
            $('#destinatario').change(function() {
                toggleFields();
            });

            // Evento para cargar estudiantes cuando cambia el curso
            $('#id_curso').change(function() {
                let cursoSeleccionado = $(this).val();
                console.log("Curso seleccionado:", cursoSeleccionado); // Depuración

                if ($('#destinatario').val() === 'estudiante' && cursoSeleccionado) {
                    cargarEstudiantes(cursoSeleccionado);
                }
            });

            // Función para cargar estudiantes por AJAX
            function cargarEstudiantes(grado) {
                console.log("Cargando estudiantes para el curso:", grado); // Depuración

                $.ajax({
                    url: 'Utilidades/cargar_estudiantes.php',
                    method: 'POST',
                    data: { grado: grado },
                    dataType: 'json',
                    success: function(data) {
                        console.log("Estudiantes recibidos:", data); // Depuración

                        if (data.length > 0) {
                            $('#id_estudiante').empty().append('<option value="">Seleccione un estudiante</option>');
                            $.each(data, function(index, estudiante) {
                                $('#id_estudiante').append(
                                    `<option value="${estudiante.documento}">${estudiante.nombre1} ${estudiante.nombre2} ${estudiante.apellido1} ${estudiante.apellido2}</option>`
                                );
                            });
                            $('#id_estudiante').prop('disabled', false);
                        } else {
                            $('#id_estudiante').empty().append('<option value="">No hay estudiantes</option>');
                            $('#id_estudiante').prop('disabled', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en AJAX:", status, error);
                    }
                });
            }

            // Función para mostrar/ocultar selectores según la opción elegida
            function toggleFields() {
                let tipo = $('#destinatario').val();
                
                if (tipo === 'estudiante') {
                    $('#cursoContainer').show();
                    $('#estudianteContainer').show();
                    if ($('#id_curso').val()) {
                        cargarEstudiantes($('#id_curso').val());
                    }
                } else if (tipo === 'curso') {
                    $('#cursoContainer').show();
                    $('#estudianteContainer').hide();
                } else {
                    $('#cursoContainer').hide();
                    $('#estudianteContainer').hide();
                }
            }

            // Ejecutar al cargar la página
            toggleFields();
        });

        </script>
    </div>
    
    <script src="JS/script.js"></script>
</body>
</html>