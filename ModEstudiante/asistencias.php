<?php
session_start();

if (!isset($_SESSION['documento']) || !isset($_SESSION['token_sesion']) || $_SESSION['id_rol'] != 101) {
    header("Location:../index.html");
    exit();
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

$documento = $_SESSION['documento'];
$trimestre = $_GET['trim'] ?? null;
$fecha = $_GET['fecha'] ?? null;
$curso = $_GET['gradoc'] ?? null;
$materia = $_GET['idmatc'] ?? null;


// Llamada al método para listar las asistencias del usuario logueado
$asistencias = $uDao->listarAsistenciasPorDocumento($fecha, $materia, $trimestre, $documento);
$curso = $uDao->Cursoper($_SESSION['documento']);
$u = $uDao->user($_SESSION['documento']);
$materias = $uDao->ListarMaterias();


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
    <script src="../JS/search.js"></script>
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

                    <li class="nav-link enfoque">
                        <a href="asistencias.php">
                            <i class="bx bx-calendar-week icon"></i>
                            <span class="text nav-text">Asistencias</span>
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
        <div class="text">Asistencias</div>

        <!-- Contenido -->

        <div class="colordiv">
            <form method="GET" action="asistencias.php">
                    <div class="filtros">
                        <h3>Fecha</h3>
                        <input type="date" name="fecha">
                        <h3 style= "display:flex; flex-direction: row;" >Curso: <?php echo $u['grado'];?></h3>
                        
                        
                        <h3>Materia</h3>
                        <select name="idmatc" id="" class="select">
                            <option value="">Selecciona una materia</option>
                            <?php
                                foreach ($materias as $index => $materia) { ?>
                                    <option value="<?php echo $materia['idmat']; ?>"><?php echo $materia['nomb_mat']; ?></option>;
                                <?php }
                            ?>
                        </select>

                        <h3>Trimestre</h3>
                        <select name="trim" id="" class="select">
                            <option value="">Selecciona un trimestre</option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="button" style=" margin: 10px 42%;">Buscar</button>
                </form>
                
                <h1>Asistencias</h1>
                <input type="text" id="buscador" placeholder="Buscar asistencia..." onkeyup="buscarEnTablas()">
                <br>
                <br>
                <table id="user-table">
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Profesor</th>
                            <th>Trimestre</th>
                            <th>Fecha Hora</th>
                            <th>Estado</th>
                            <th>Opcion</th>
                        </tr>
                    </thead>
                    <tbody>
    
                    <?php
                        foreach ($asistencias as $index => $asistencia) { ?>
                            <tr>
                                <td><?php echo $asistencia['nomb_mat']; ?></td>
                                <td><?php echo $asistencia['profesor']; ?></td>
                                <td><?php echo $asistencia['trimestre']; ?></td>
                                <td><?php echo $asistencia['fecha_asistencia']; ?></td>
                                <td><?php echo $asistencia['estado_asis']; ?></td>
                                <td><a href="#openModal" class="button" id="<?php echo $index; ?>" onclick="verObservation(<?php echo $index; ?>)">VER</a></td>
                            </tr>
                    <?php }?>
                    </tbody>
                </table>
                <!--div class="buttons">
                    <button>Excel</button>
                    <button>PDF</button>
                </div-->
            </div>
    </div>
    
    <script src="../JS/script.js"></script>
</body>
</html>

<!-- modal para actualizar y ver la asistencia -->

<div id="openModal" class="modal_asis">
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <h2 style="text-align: center;">Asistencia</h2>
        <div class="row"><h3>Curso: </h3><h4><?php echo $u['grado']; ?></h4></div>
        <div class="row"><h3>Materia: </h3> <h4 id="nomb_mat"></h4></div>
        <div class="row"><h3>Fecha: </h3><h4 id="fecha_asistencia"></h4></div>
        <br>
        <form action="">
            <table id="user-table">
                <thead>
                    <tr>
                        <th>Profesor</th>
                        <th>Mi Documento</th>
                        <th>Estado</th>
                        <th>Justificacion</th>
                        <th>Trimestre</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="profesor"></td>
                        <td id="documento"></td>
                        <td id="estado_asis"></td>
                        <td id="justificacion_inasistencia"></td>
                        <td id="trimestre"></td>
                    </tr>
            </table>
            <br>

        </form>
    </div>
</div>


<script>
    function verObservation(index) {
        // Obtener la lista de observaciones desde PHP (puedes pasarla al JS de alguna manera, por ejemplo, como un JSON en un input oculto)
        const asistencias = <?php echo json_encode($asistencias); ?>;
        
        // Buscar la observación que corresponde al índice
        const asistencia = asistencias[index];

        document.getElementById("profesor").innerText = asistencia.profesor;
        document.getElementById("documento").innerText = asistencia.documento;
        document.getElementById("estado_asis").innerText = asistencia.estado_asis;
        document.getElementById("nomb_mat").innerText = asistencia.nomb_mat;
        document.getElementById("fecha_asistencia").innerText = asistencia.fecha_asistencia;
        document.getElementById("justificacion_inasistencia").innerText = asistencia.justificacion_inasistencia;
    }
</script>
