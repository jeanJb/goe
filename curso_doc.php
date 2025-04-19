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
$materias = $uDao->ListarMaterias();
$docentes = $uDao->ListarDocentes();
$asignaciones = $uDao->MateriasAsignadasCurso();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['registro_curso'])) {
        // Registro de curso
        $grado = $_POST['grado'];
        $salon = $_POST['salon'];
        $rm = $uDao->registrarCurso($grado, $salon);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } elseif (isset($_POST['registro_materia'])) {
        // Registro de materia en curso
        $grado = $_POST['grado'];
        $materia = $_POST['idMat'];
        $rm = $uDao->registrarCurMat($grado, $materia);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } elseif (isset($_POST['eliminar_curso'])) {
        // Registro de curso
        $grado = $_POST['id'];
        $rm = $uDao->eliminarCurso($grado);
        header("Location: curso_doc.php?success=1");
        exit();
    } elseif (isset($_POST['eliminar_materia'])) {
        // Registro de materia en curso
        $id = $_POST['id'];
        $rm = $uDao->eliminarCurMat($id);
        header("Location: curso_doc.php?success=1");
        exit();
    }
}


// Procesar datos para asegurar compatibilidad con JSON
$docentes = array_map(function($docente) {
    return array_map(function($value) {
        if (is_null($value)) {
            return ''; // Convertir NULL a cadena vacía
        }
        if (is_string($value)) {
            return utf8_encode($value); // Asegurar codificación UTF-8
        }
        return $value; // Dejar otros tipos como están
    }, $docente);
}, $docentes);


// Intentar generar JSON
$jsonDocentes = json_encode($docentes);
?>
<?php if (isset($_GET['success'])): ?>
    <p class="success">Operación realizada con éxito.</p>
<?php elseif (isset($_GET['deleted'])): ?>
    <p class="error">Eliminación completada.</p>
<?php endif; ?>

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
    
                    <li class="nav-link enfoque">
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
        <div class="text">Asignar Curso a Docentes</div>

        <!-- Contenido -->  

        <div class="colordiv">
            <h4>Actualizar o Asignar Curso</h4>
            <table id="user-table">
            <input type="text" id="buscador" placeholder="Buscar docente..." onkeyup="buscarEnPrimeraTabla()">
            <br>
            <br>
                <thead>
                    <tr>
                        <th>Docente</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Curso</th>
                        <th>Actualizar Curso</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                    foreach ($docentes as $index => $docente) { ?>
                    <tr>
                        <th><?php echo $docente['nombre1']; ?> <?php echo $docente['nombre2'];?> <?php echo $docente['apellido1']; ?></th>
                        <th><?php echo $docente['email']; ?></th>
                        <th><?php echo $docente['telefono']; ?></th>
                        <th><?php echo $docente['grado']; ?></th>
                        <th><a href="#openModal" class="button_2" id="<?php echo $index; ?>" onclick="verDocente(<?php echo $index; ?>)"><i class='bx bx-edit op'></i></a></th>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>


        <!-- Asignar materias a cursos -->
        
        <div class="text">Asignar Materias a Cursos</div>
        <div class="colordiv">
            <form action="curso_doc.php" method="POST">
                <h4>Asignar Materia</h4>
                <div class="filtros">
                    <br>
                    <span>Curso:</span>
                    <select name="grado" id="grado">
                        <option value="">Seleccionar Curso</option>
                        <?php
                            foreach ($cursos as $index => $curso) { ?>
                                <option value="<?php echo $curso['grado']; ?>"><?php echo $curso['grado']; ?></option>
                        <?php } ?>
                    </select>

                    <span>Materia:</span>
                    <select name="idMat" id="idMat">
                        <option value="">Seleccionar materia</option>
                        <?php
                            foreach ($materias as $index => $materia) { ?>
                                <option value="<?php echo $materia['idmat']; ?>"><?php echo $materia['nomb_mat']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="button" name="registro_materia">Crear Materia</button>
            </form>

        </div>

        <div class="colordiv">
            <h4>Materias Asignadas</h4>
            <input type="text" id="lupa" placeholder="Buscar estudiante..." onkeyup="buscarEnTabla()">
            <br>
            <br>
            <table id="user-table" class="table-one">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Materia</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($asignaciones as $index => $asig) { ?>
                    <tr>
                        <th><?php echo $asig['grado'];?></th>
                        <th><?php echo $asig['nomb_mat'];?></th>
                        <th>
                            <!-- <a href="curso_doc.php?id=<?php echo $asig['id_relacion'];?>" name="eliminar_materia" onclick=" return confirmar(event);" class="button_2">
                                <i class='bx bx-trash op'></i>
                            </a> -->
                            <form action="curso_doc.php" method="POST" onsubmit="return confirmar(event);">
                                <input type="hidden" name="id" value="<?php echo $asig['id_relacion']; ?>">
                                <button type="submit" name="eliminar_materia" class="button_2">
                                    <i class='bx bx-trash op'></i>
                                </button>
                            </form>
                        </th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Crear Cursos -->

        <div class="colordiv">
            <form action="curso_doc.php" method="POST">
                <h4>Nuevo Curso</h4>
                
                <div class="filtros">
                    <br>
                    <span>Nuevo Curso:</span>
                    <input type="number" name="grado" required>

                    <br>
                    <span>Salon asignado al Curso:</span>
                    <input type="number" name="salon" required>
                </div>
                
                <br>
                <button type="submit" class="button" name="registro_curso">Crear Curso</button>
            </form>

        </div>

        <div class="colordiv">
            <h4>Cursos Existentes</h4>
            <table id="user-table">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Salon</th>
                        <th>Actualizar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($cursos as $index => $curso) { ?>
                    <tr>
                        <th><?php echo $curso['grado'];?></th>
                        <th><?php echo $curso['salon'];?></th>
                        <th><a href="#openModal1" class="button_2" id="<?php echo $index; ?>" onclick="verCurso(<?php echo $index; ?>)"><i class='bx bx-edit op'></i></a></th>
                        <th>
                            <!-- <a href="curso_doc.php?id=<?php echo $curso['grado'];?>" name="eliminar_Curso" onclick=" return confirmar(event);" class="button_2">
                                <i class='bx bx-trash op'></i>
                            </a> -->
                            <form action="curso_doc.php" method="POST" onsubmit="return confirmar(event);">
                                <input type="hidden" name="id" value="<?php echo $curso['grado']; ?>">
                                <button type="submit" name="eliminar_curso" class="button_2">
                                    <i class='bx bx-trash op'></i>
                                </button>
                            </form>

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

<!-- Modal -->

<div id="openModal" class="modal_asis">
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <h2 style="text-align: center;">Actualizar Curso del Docente</h2>
        <form action="controladores/controlador.grado.php" method="POST">
        
            <br>
            <input type="hidden" id="documento" name="documento">
            <div class="row">
                <h3>Docente:</h3>
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
                <select name="grado" id="" class="select">
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

<!-- Modal Curso -->

<div id="openModal1" class="modal_asis">
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <h2 style="text-align: center;">Actualizar el Salon correspondiente al Curso</h2>
        <form action="controladores/controlador.grado.php" method="POST">

            <br>
            
            <div class="row">
                <h3>Grado:</h3>
                <input type="text" id="curso" name="grado" readonly>
            </div>
            <br>

            <div class="row">
                <h3>Salon Antiguo:</h3>
                <span id="oldsalon"></span>
            </div>
            <br>

            <div class="row">
                <h3>Nuevo Salon:</h3>
                <input type="number" id="salon" name="salon" required>
            </div>
            <br>

            <button class="button" type="submit" name="update" id="update">ACTUALIZAR</button>
        </form>
    </div>
</div>


<script>
    function verDocente(index) {
        const docentes = <?php echo json_encode($docentes); ?>;
        const docente = docentes[index];

        // Asignar valores a los elementos del modal
        document.getElementById("documento").value = docente.documento;
        document.getElementById("nombre").innerText = docente.nombre1 + " " + docente.nombre2 + " " + docente.apellido1;
        document.getElementById("email").innerText = docente.email;
        document.getElementById("telefono").innerText = docente.telefono;
        document.getElementById("grado").innerText = docente.grado? docente.grado : 'Sin curso asignado';
    }

    function verCurso(index) {
        const cursos = <?php echo json_encode($cursos); ?>;
        const curso = cursos[index];

        // Asignar valores a los elementos del modal
        document.getElementById("curso").value = curso.grado;
        document.getElementById("oldsalon").innerText = curso.salon;
    }

</script>