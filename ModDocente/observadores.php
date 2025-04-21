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

$trimestre = $_POST['trim'] ?? null;
$allObs = $uDao->ListarObservadores($trimestre);
$cursos = $uDao->ListarCursos();
$u = $uDao->user($_SESSION['documento']);


$documento = $_GET['user'] ?? '';
$obs = $uDao->listarUsuariosper($documento);
$grado = $_GET['grado'] ?? '703';
$estudiantes = $uDao->listarEstudiantesPorCurso($grado);


$allObs = array_map(function($obsv) {
    return array_map(function($value) {
        if (is_null($value)) {
            return ''; // Convertir NULL a cadena vacía
        }
        if (is_string($value)) {
            return utf8_encode($value); // Asegurar codificación UTF-8
        }
        return $value; // Dejar otros tipos como están
    }, $obsv);
}, $allObs);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logos/goe03.png" type="image/png">
    <title>GOE</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="../JS/search.js"></script>
    <link rel="stylesheet" href="../STYLES/diseno.css">
    <script>
        if (localStorage.getItem('dark-mode') === 'enabled') {
            document.documentElement.classList.add('dark');
        }

        function confirmar(event) {
            if (!confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                event.preventDefault();
            }
        }

        $(document).ready( function () {
            $('#myTable').DataTable();
        });
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
        <div class="text">Observadores</div>

        <!-- Contenido -->

        <div class="datos colordiv">
            <h2>Datos del Estudiante</h2>
            <form method="GET" action="observadores.php">
                <div class="filtros">
                    <h3>Curso:</h3>
                    <select name="grado" id="curso" class="select">
                        <option value="">seleccione</option>
                        <?php foreach($cursos as $index => $curso){ ?>
                            <option value="<?php echo $curso['grado']; ?>"><?php echo $curso['grado']; ?></option>;
                        <?php } ?>
                        
                    </select>

                    <h3>Estudiante:</h3>
                    <select name="user" id="estudiantes" class="select">
                        <option value="">seleccione</option>
                        <?php foreach($estudiantes as $index => $estudiante){ ?>
                            <option value="<?php echo $estudiante['documento']; ?>"><?php echo $estudiante['nombre1']; ?> <?php echo $estudiante['apellido1']; ?> <?php echo $estudiante['apellido2']; ?></option>;
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="button" style=" margin: 10px 42%;">Buscar</button>
            </form>
            <br>
            
            <?php foreach($obs as $ob){ ?>
            <div class="c">
                <h3>Documento:  <p> <?php echo $ob['documento']; ?></p></h3>
                <h3>Documento:  <p> <?php echo $ob['nombre1']; ?> <?php echo $ob['nombre2']; ?> <?php echo $ob['apellido1']; ?> <?php echo $ob['apellido2']; ?></p></h3>
                <h3>Correo: <p> <?php echo $ob['email']; ?></p></h3>
            </div>

            <div class="c">
                <h3>Telefono: <p> <?php echo $ob['telefono']; ?></p></h3>
                <h3>Direccion: <p> <?php echo $ob['direccion']; ?></p></h3>
            </div>
            <?php } ?>
        </div>

        <!-- Mas Datos del Estudiante -->

        <details class="colordiv">
            <summary>Mas Datos del Estudiante</summary>
            <table id="user-table">
                <tbody>
                <?php foreach($obs as $ob){ ?>
                    <tr>
                        <td><b>RH</b></td>
                        <td><?php echo $ob['rh_estudiante']; ?></td>
                    </tr>

                    <tr>
                        <td><b>EPS</b></td>
                        <td><?php echo $ob['eps']; ?></td>
                    </tr>

                    <tr>
                        <td><b>Fecha de Nacimiento</b></td>
                        <td><?php echo $ob['fecha_naci']; ?></td>
                    </tr>

                    <tr>
                        <td><b>Enfermedades</b></td>
                        <td><?php echo $ob['enfermedades']; ?></td>
                    </tr>

                    <tr>
                        <td><b>Nombre Acudiente</b></td>
                        <td><?php echo $ob['nom_acu']; ?></td>
                    </tr>

                    <tr>
                        <td><b>Telefono Acudiente</b></td>
                        <td><?php echo $ob['telefono_acu']; ?></td>
                    </tr>

                    <tr>
                        <td><b>Documento Acudiente</b></td>
                        <td><?php echo $ob['doc_acu']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            
        </details>

        <div class="datos1 colordiv">

            <form action="../controladores/controlador.observadordoc.php" method="POST">
                <div class="" style="display: flex; flex-direction: row; align-items: end;">
                    <h3 style="margin-right: 8px;">Documento:</h3>
                    <input type="number" name="documento" <?php foreach($obs as $ob){ ?>value="<?php echo $ob['documento']; ?>" <?php }?> required>
                </div>
            
                <div class="" style="display: flex; flex-direction: row; align-items: end;">
                    <h3 style="margin-right: 8px;">FECHA:  </h3>
                    <input type="datetime-local" name="fecha" id="" required>
                </div>
    
                <div class="" style="width: 50%; height: auto; float: left;">
                    <h1>Porque se hace la Observacion?</h1>
                    <textarea name="descripcion_falta" id="" required></textarea>
                </div>

                <div class="" style="width: 50%; height: auto; float: left;">
                    <h1>Compromiso del Estudiante</h1>
                    <textarea name="compromiso" id="" required></textarea>
                </div>
                
                <div class="">
                    <h3 style="margin-right: 8px;">Docente:</h3>
                    <input type="text" name="seguimiento" value="<?php echo $u['nombre1']; ?> <?php echo $u['apellido1']; ?>" required readonly>
                    <h3>Trimestre</h3>
                        <select name="trimestre" id="" class="select" required>
                            <option value="">Selecciona un trimestre</option>
                            <option value="I">I</option>
                            <option value="II">II</option> 
                            <option value="III">III</option>
                        </select>
                </div>

    
                <h3>Nivel de la Falta:</h3>
                <select name="falta" id="" class="select" required>
                    <option value="Leve">Leve</option>
                    <option value="Regular">Regular</option>
                    <option value="Grave">Grave</option>
                </select>
    
    
                <br>
                <button type="submit" class="button" name="registro" id="registro">Enviar</button>
            </form>
        </div>


        <!-- Consulta de Observadores -->
        <div class="datos1 colordiv">
            <h2>Observaciones</h2>
            
            <input type="text" id="buscador" placeholder="Buscar observacion..." onkeyup="buscarEnTablas()">
            <br>
            <br>
            <table id="user-table">
                <thead>
                    <tr>
                        <td>Estudiante</td>
                        <td>Fecha</td>
                        <td>Descripcion</td>
                        <td>Trimestre</td>
                        <td>Nivel de la Falta</td>
                        <td>Opciones</td>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($allObs as $index => $obsv) { ?>
                        <tr>
                            <td><?php echo $obsv['nombre1']; ?> <?php echo $obsv['nombre2']; ?> <?php echo $obsv['apellido1']; ?> <?php echo $obsv['apellido2']; ?></td>
                            <td><?php echo $obsv['fecha']; ?></td>
                            <td><?php echo $obsv['descripcion_falta']; ?></td>
                            <td><?php echo $obsv['trimestre']; ?></td>
                            <td><?php echo $obsv['falta']; ?></td>
                            <td>
                                <a href="#openModal" class="button_2" id="<?php echo $index; ?>" onclick="verObservation(<?php echo $index; ?>)"><i class='bx bx-edit op'></i></a>
                                <a href="../controladores/controlador.observadordoc.php?id=<?php echo $obsv['idobservador'];?>" class="button_2" onclick=" return confirmar(event);">
                                    <i class='bx bx-trash op'></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<!-- modal para ver la observacion un estudiante-->
<div id="openModal" class="modal_asis">
    <div class="modaldiv">
        <a href="#close" id="exit" class="exit">X</a>
        <h1 style="text-align: center;">Actualizar Observación</h1>
        <form action="../controladores/controlador.observadordoc.php" method="POST">
            
            <input type="hidden" name="idobservador" id="id" value="...">


            <div class="" style="display: flex; flex-direction: row; align-items: end;">
                <h3 style="margin-right: 8px;">Docente: </h3>
                <input type="text" id="docente" name="seguimiento" value="" required>
            </div>

            <div class="" style="display: flex; flex-direction: row; align-items: end;">
                <h3 style="margin-right: 8px;">Documento:</h3>
                <input type="text" id="documento" name="" value="" readonly>
            </div>

            <br>    

            <div class="" style="display: flex; flex-direction: row; align-items: end;">
                <h3 style="margin-right: 8px;">Fecha:</h3>
                <input type="input" id="fecha" name="fecha" value="" readonly>
            </div>

            <br>

            <div class="" style="width: 50%; height: auto; float: left;">
                <h1>Porque se hace la Observación?</h1>
                <textarea id="descripcion_falta" name="descripcion_falta" required></textarea>
            </div>
            <div class="" style="width: 50%; height: auto; float: left; border-left: black solid 1px; padding: 7px;">
                <h1>Compromiso del Estudiante</h1>
                <textarea id="compromiso" name="compromiso" required></textarea>
            </div>
            <br>
            <br>
            <h3>Nivel de la Falta: </h3>
            <select id="falta" name="falta" class="select" required>
                <option value="Leve">Leve</option>
                <option value="Regular">Regular</option>
                <option value="Grave">Grave</option>
            </select>

            <div class="filtros">
                <h3>Trimestre: </h3>
                <h4 id="trimestres"></h4>
            </div>
            
            <br><br>
            <button type="submit" class="button" name="actualizar">Actualizar</button>
        </form> 
    </div>
</div>

<?php
    if(isset($_GET['mensaje'])){ ?>
    <div class="row"> <br><br>
        <div class="col-md-6"></div>
        <div class="col-md-4 text-center">
            <h4><?php echo $mensaje = $_GET['mensaje']?>
            </h4>
        </div>
        <div class="col-md-5"></div>
    </div>

    <?php
    }
    ?>

<script>
    function verObservation(index) {
        const allObs = <?php echo json_encode($allObs); ?>;

        const obs = allObs[index];

        document.getElementById("id").value = obs.idobservador;
        document.getElementById("docente").value = obs.seguimiento;
        document.getElementById("documento").value = obs.documento;
        document.getElementById("fecha").value = obs.fecha;
        document.getElementById("descripcion_falta").value = obs.descripcion_falta;
        document.getElementById("compromiso").value = obs.compromiso;
        document.getElementById("falta").value = obs.falta;
        document.getElementById("trimestres").innerText = obs.trimestre; 
    }

</script>