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
$cursos = $uDao->ListarCursos();
$estudiantes = $uDao->ListarEstudiantes();

// Incluye lógica para manejar búsqueda y actualización.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $gradoOld = $_POST['gradoOld'] ?? '';
    $gradoNew = $_POST['gradoNew'] ?? '';

    if ($action === 'buscar') {
        // Filtra estudiantes según el curso seleccionado
        $eg = $uDao->listarEstudiantesPorCurso($gradoOld);
    } else if ($action === 'actualizar' && !empty($gradoOld) && !empty($gradoNew)) {
        // Actualiza los estudiantes al nuevo curso
        $update = $uDao->modificarGradoAll($gradoNew, $gradoOld);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

$estudiantes = array_map(function($estudiante) {
    return array_map(function($value) {
        if (is_null($value)) {
            return ''; // Convertir NULL a cadena vacía
        }
        if (is_string($value)) {
            return utf8_encode($value); // Asegurar codificación UTF-8
        }
        return $value; // Dejar otros tipos como están
    }, $estudiante);
}, $estudiantes);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="IMG/logos/goe03.png" type="image/png">
    <title>GOE</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="JS/search.js"></script>
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
    
                    <li class="nav-link enfoque">
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
        <div class="text">Actualizar Curso de Estudiantes</div>

        <!-- Contenido -->
        
        <div class="colordiv">
            <h2>Actualizar Curso por Estudiante</h2>
            <input type="text" id="buscador" placeholder="Buscar estudiante..." onkeyup="buscarEnPrimeraTabla()">
            <br>
            <br>
            <table id="user-table">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Estudiante</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Curso</th>
                        <th>Actualizar Curso</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                    foreach ($estudiantes as $index => $estudiante) { ?>
                    <tr>
                        <th><?php echo $estudiante['documento']; ?></th>
                        <th><?php echo $estudiante['nombre1']; ?> <?php echo $estudiante['nombre2'];?> <?php echo $estudiante['apellido1']; ?></th>
                        <th><?php echo $estudiante['email']; ?></th>
                        <th><?php echo $estudiante['telefono']; ?></th>
                        <th><?php echo $estudiante['grado']; ?></th>
                        <th><a href="#openModal" class="button_2" id="<?php echo $index; ?>" onclick="verEstudiante(<?php echo $index; ?>)"><i class='bx bx-edit op'></i></a></th>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="colordiv">
            <h2>Actualizar Curso por Grado</h2>

            <br>
            <!-- Formulario -->
            <form action="" method="POST">
                <div class="filtros">
                    <h4>Curso a Actualizar :</h4>
                    <select name="gradoOld" class="select">
                        <option value="">Curso</option>
                        <?php foreach ($cursos as $curso) { ?>
                            <option value="<?php echo $curso['grado']; ?>" 
                                <?php echo isset($gradoOld) && $gradoOld === $curso['grado'] ? 'selected' : ''; ?>>
                                <?php echo $curso['grado']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    
                    <h4>Cursos a Asignar :</h4>
                    <select name="gradoNew" class="select">
                        <option value="">Curso</option>
                        <?php foreach ($cursos as $curso) { ?>
                            <option value="<?php echo $curso['grado']; ?>">
                                <?php echo $curso['grado']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <button class="button" type="submit" name="action" value="buscar">Buscar Curso</button>
                <button class="button" type="submit" name="action" value="actualizar">ACTUALIZAR</button>
            </form>

            <!-- Tabla de estudiantes -->
            <?php if (!empty($eg)) { ?>
                <h4>Estudiantes del Curso a Actualizar</h4>
                <table id="user-table">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Curso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eg as $e) { ?>
                            <tr>
                                <td><?php echo $e['documento']; ?></td>
                                <td><?php echo $e['nombre1'] . ' ' . $e['nombre2'] . ' ' . $e['apellido1']; ?></td>
                                <td><?php echo $e['email']; ?></td>
                                <td><?php echo $e['telefono']; ?></td>
                                <td><?php echo $e['grado']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else if (isset($gradoOld) && empty($eg)) { ?>
                <p>No se encontraron estudiantes en el curso seleccionado.</p>
            <?php } ?>

    </div>
    
    <script src="JS/script.js"></script>
</body>
</html>

<!-- Modal -->

<div id="openModal" class="modal_asis">
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <h2 style="text-align: center;">Actualizar Curso del Estudiante</h2>
        <form action="controladores/controlador.grado.estudiantes.php" method="POST">
        
            <br>
            <input type="hidden" id="documento" name="documento">
            <div class="row">
                <h3>estudiante:</h3>
                <span id="nombre"></span>
            </div>
            <br>

            <div class="row">
                <h3>Correo:</h3>
                <span id="email"></span>
            </div>
            <br>

            <div class="row">
                <h3>Teléfono:</h3>
                <span id="telefono"></span>
            </div>
            <br>

            <div class="row">
                <h3>Curso Actual:</h3>
                <span id="grado"></span>
            </div>
            <br>

            <div class="row">
                <h3>Nuevo Curso:</h3>
                <select name="grado" class="select">
                    <option value="">Curso</option>
                    <?php
                        foreach ($cursos as $curso) { ?>
                            <option value="<?php echo $curso['grado']; ?>"><?php echo $curso['grado']; ?></option>;
                    <?php } ?>
                    <option value=""></option>
                </select>
            </div>
            <br>

            <button class="button" type="submit" name="actualizar" id="actualizar">ACTUALIZAR</button>
        </form>
    </div>
</div>


<script>
    function verEstudiante(index) {
    const estudiantes = <?php echo json_encode($estudiantes); ?>;
    const estudiante = estudiantes[index];

    // Asignar valores a los elementos del modal
    document.getElementById("documento").value = estudiante.documento;
    document.getElementById("nombre").innerText = estudiante.nombre1 + " " + estudiante.nombre2+ " " + estudiante.apellido1;
    document.getElementById("email").innerText = estudiante.email;
    document.getElementById("telefono").innerText = estudiante.telefono;
    document.getElementById("grado").innerText = estudiante.grado? estudiante.grado : 'Sin curso asignado';
}

</script>