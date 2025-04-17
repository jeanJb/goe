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
$materias = $uDao->ListarMaterias();
$docentes = $uDao->ListarDocentes();
$asignaciones = $uDao->MateriasAsignadas();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idmat = $_POST['idMat']?? NULL;
    $asignatura = $_POST['materia'];
    $rm = $uDao->registrarMateria($asignatura);
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
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
    
                    <li class="nav-link enfoque">
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
        <div class="text">Asignar o crear Materia</div>

        <!-- Contenido -->

        <div class="colordiv">
            <form action="controladores/controlador.promat.php" method="POST">
                <h4>Asignar</h4>
                <div class="filtros">
                    <br>
                    <span>Docente:</span>
                    <select name="documento" id="documento">
                        <option value="">Seleccionar Docente</option>
                        <?php
                            foreach ($docentes as $index => $docente) { ?>
                                <option value="<?php echo $docente['documento']; ?>"><?php echo $docente['nombre1']; ?> <?php echo $docente['nombre2'];?> <?php echo $docente['apellido1']; ?></option>;
                        <?php } ?>
                    </select>

                    <span>Materia:</span>
                    <select name="idMat" id="idMat">
                        <option value="">Seleccionar materia</option>
                        <?php
                            foreach ($materias as $index => $materia) { ?>
                                <option value="<?php echo $materia['idmat']; ?>"><?php echo $materia['nomb_mat']; ?></option>;
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="button" name="registro" id="registro">Asignar Materia</button>
            </form>

        </div>

        <div class="colordiv">
            <h4>Materias Asignadas</h4>
            <input type="text" id="buscador" placeholder="Buscar observacion..." onkeyup="buscarEnPrimeraTabla()">
            <br>
            <br>
            <table id="user-table">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Docente</th>
                        <th>Materia</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($asignaciones as $index => $asig) { ?>
                    <tr>
                        <th><?php echo $asig['documento'];?></th>
                        <th><?php echo $asig['nombre1']; ?> <?php echo $asig['nombre2'];?> <?php echo $asig['apellido1']; ?></th>
                        <th><?php echo $asig['nomb_mat'];?></th>
                        <th>
                            <a href="controladores/controlador.promat.php?id=<?php echo $asig['id_relacion'];?>" onclick=" return confirmar(event);" class="button_2">
                                <i class='bx bx-trash op'></i>
                            </a>
                        </th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Crear Materias -->

        <div class="colordiv">
            <form action="pro_mat.php" method="POST">
                <h4>Crear Materias</h4>
                
                <br>
                <span>Nombre de la nueva materia:</span>
                <input type="text" name="materia" required>
                
                <br>
                <button type="submit" class="button" name="registro" id="registro">Asignar Materia</button>
            </form>

        </div>

        <div class="colordiv">
            <h4>Materias Existentes</h4>
            <table id="user-table">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($materias as $index => $materia) { ?>
                    <tr>
                        <th><?php echo $materia['nomb_mat'];?></th>
                        <th>
                            <a href="controladores/controlador.promat.php?name=<?php echo $materia['idmat'];?>" onclick=" return confirmar(event);" class="button_2">
                                <i class='bx bx-trash op'></i>
                            </a>
                        </th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
    
    <script src="JS/script.js"></script>
</body>
</html>
