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
$uDao2 = new UsuarioDao();

// Llamada al método para listar las observaciones del usuario logueado
$allObs = $uDao->listarObservadoresper($_SESSION['documento']);
$allDir = $uDao2->listarDirectoriosper($_SESSION['documento']);
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

                    <li class="nav-link enfoque">
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
        <div class="text">Observaciones</div>

        <!-- Contenido -->

        <!-- Mis Datos -->
        <?php
        foreach ($allDir as $dir) { ?>

        <details class="colordiv">
            <summary>Mis Datos </summary>
            <table id="user-table">
                <tbody>
                    <tr>
                        <td><b>RH</b></td>
                        <td><?php echo $dir['rh_estudiante']; ?></td>
                    </tr>
                    
                    <tr>
                        <td><b>EPS</b></td>
                        <td><?php echo $dir['eps']; ?></td>
                    </tr>

                    <tr>   
                        <td><b>Fecha de Nacimiento</b></td>
                        <td><?php echo $dir['fecha_naci']; ?></td>
                    </tr>

                    <tr>
                        <td><b>Enfermedades</b></td>
                        <td><?php echo $dir['enfermedades']; ?></td>
                    </tr>

                    <tr>
                        <td><b>Nombre Acudiente</b></td>
                        <td><?php echo $dir['nom_acu']; ?></td>
                    </tr>

                    <tr>
                        <td><b>Telefono Acudiente</b></td>
                        <td><?php echo $dir['telefono_acu']; ?></td>
                    </tr>

                    <tr>
                        <td><b>Documento Acudiente</b></td>
                        <td><?php echo $dir['doc_acu']; ?></td>
                    </tr>

                </tbody>
            </table>
            
        </details>
        <?php } ?>

        <div class="datos1 colordiv">
            <h2>Observaciones</h2>
            <input type="text" id="buscador" placeholder="Buscar observacion..." onkeyup="buscarEnTablas()">
            <br>
            <br>
            <table id="user-table">
                <thead>
                    <tr>
                        <td>Descripcion</td>
                        <td>Nivel de la Falta</td>
                        <td>Trimestre</td>
                        <td>Opciones</td>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($allObs as $index => $obs) { ?>
                        <tr>
                            <td><?php echo $obs['descripcion_falta']; ?></td>
                            <td><?php echo $obs['falta']; ?></td>
                            <td><?php echo $obs['trimestre']; ?></td>
                            <td><a href="#openModal" class="button" id="<?php echo $index; ?>" onclick="verObservation(<?php echo $index; ?>)">VER</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="../JS/script.js"></script>
</body>
</html>


<!-- modal para ver la observacion un estudiante-->
<?php
        foreach ($allObs as $obs) { ?>

<div id="openModal" class="modal_asis">
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <center><h1>Observacion</h1></center>
        <div class="row">
            <h3>Docente:  </h3>
            <h4 id="docente"></h4>
        </div>

        <div class="row">
            <h3>Documento:</h3>
            <h4 id="documento"></h4>
        </div>

        <br>    
    
        <div class="row">
            <h3>FECHA:  </h3>   
            <h4 id="fecha"></h4>
        </div>

        <br>

        <div class="textone">
            <h1>Porque se hace la Observacion?</h1>
            <p id="descripcion_falta"></p>
        </div>
        <div class="texttwo">
            <h1>Compromiso del Estudiante</h1>
            <p id="compromiso"></p>
        </div>
        <br>
        <br>
        <h3>Nivel de la Falta: </h3>
        <p id="falta"></p>
    </div>
</div>
<?php } ?>

<script>
    function verObservation(index) {
        // Obtener la lista de observaciones desde PHP (puedes pasarla al JS de alguna manera, por ejemplo, como un JSON en un input oculto)
        const allObs = <?php echo json_encode($allObs); ?>;
        
        // Buscar la observación que corresponde al índice
        const obs = allObs[index];

        document.getElementById("docente").innerText = obs.seguimiento;
        document.getElementById("documento").innerText = obs.documento;
        document.getElementById("fecha").innerText = obs.fecha;
        document.getElementById("descripcion_falta").innerText = obs.descripcion_falta;
        document.getElementById("compromiso").innerText = obs.compromiso;
        document.getElementById("falta").innerText = obs.falta;
    }
</script>