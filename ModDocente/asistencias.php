<?php
session_start();

require '../ModeloDAO/UsuarioDao.php';

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

$uDao = new UsuarioDao();
$cursos = $uDao->ListarCursos();
$u = $uDao->user($_SESSION['documento']);

$trimestre = $_GET['trim'] ?? null;
$fecha = $_GET['fecha'] ?? null;
$curso = $_GET['gradoc'] ?? null;
$materia = $_GET['idmatc'] ?? null;
$docente = $u['nombre1'] . ' ' . $u['apellido1'];

// Obtener las asistencias filtradas
$materias = $uDao->ListarMateriasdoc($_SESSION['documento']);
$asistencias = $uDao->listarAsistenciasDoc($fecha, $curso, $materia, $trimestre, $docente);

$listado = $_POST['listado'] ?? null;
$list = $uDao->listadoAsis($listado);
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
        const darkMode = localStorage.getItem('dark-mode');
        if (darkMode === 'enabled') {
            document.documentElement.classList.add('dark');
        }

        function actualizarAsistencia(id) {
            let estado = document.querySelector(`#estado_asis_${id}`).value;
            let justificacion = document.querySelector(`#justificacion_inasistencia_${id}`).value;

            let formData = new FormData();
            formData.append("idasistencia", id);
            formData.append("estado_asis", estado);
            formData.append("justificacion_inasistencia", justificacion);
            formData.append("update", true);

            fetch("../controladores/controlador.asistenciadoc.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    alert("Asistencia actualizada correctamente");
                } else {
                    alert("Error al actualizar");
                }
            })
            .catch(err => console.error("Error:", err));
        }



        function openList(element) {
            var idListado = element.getAttribute("data-listado");

            fetch("../controladores/controlador.asistenciadoc.php?idlistado=" + idListado)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let contenido = "";

                        data.forEach(asistencia => {
                            contenido += `
                                <tr>
                                    <td>${asistencia.apellido1} ${asistencia.apellido2} ${asistencia.nombre1} ${asistencia.nombre2}</td>
                                    <td>
                                        <select name="estado_asis" class="select" id="estado_asis_${asistencia.idasistencia}">
                                            <option value="${asistencia.estado_asis}" selected>${asistencia.estado_asis}</option>
                                            <option value="Asistio">Asistió</option>
                                            <option value="Retardo">Retardo</option>
                                            <option value="Falla">Falla</option>
                                            <option value="Excusa">Excusa</option>
                                        </select>
                                    </td>
                                    <td><input type="text" id="justificacion_inasistencia_${asistencia.idasistencia}" value="${asistencia.justificacion_inasistencia}" required></td>
                                    <td>${asistencia.curso}</td>
                                    <td>${asistencia.nomb_mat}</td>
                                    <td><button onclick="actualizarAsistencia(${asistencia.idasistencia})" class="button">Actualizar</button></td>
                                </tr>`;
                        });

                        document.querySelector("#contenidoModal tbody").innerHTML = contenido;
                    } else {
                        document.querySelector("#contenidoModal tbody").innerHTML = "<tr><td colspan='6'>No hay asistencias registradas para este listado.</td></tr>";
                    }

                    document.getElementById("openModal").style.display = "block"; // Muestra el modal
                })
                .catch(error => console.error("Error al cargar las asistencias:", error));
        }

    </script>
    <script defer>
        function filtrarPorCurso(grado) {
            if (grado) {
                const url = `asistencias.php?grado=${encodeURIComponent(grado)}`;
                window.location.href = url;
            }
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
        
        <div class="container colordiv">
            <form method="POST" action="../controladores/controlador.asistenciadoc.php">
                <!-- Filtros: fecha, curso y materia -->
                <div class="filtros">
                    <h3>Fecha</h3>
                    <input type="datetime-local" name="fecha_asistencia" required>
                    <h3>Curso</h3>
                    <select name="grado" id="curso" class="select" onchange="filtrarPorCurso(this.value)">
                        <option value="">Selecciona un curso</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?php echo $curso['grado']; ?>"><?php echo $curso['grado']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <h3>Materia</h3>
                    <select name="idmat" class="select" required>
                        <option value="">Selecciona una materia</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?php echo $materia['idMat']; ?>"><?php echo $materia['nomb_mat']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <h3>Trimestre</h3>
                    <select name="trimestre" id="" class="select">
                        <option value="">Selecciona un trimestre</option>
                        <option value="I">I</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                    </select>
                </div>
                
                <h1>Estudiantes</h1>
                <table id="user-table">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Apellidos</th>
                            <th>Nombres</th>
                            <th>Curso</th>
                            <th>Asistencia</th>
                            <th>Justificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grado = $_GET['grado'] ?? '';
                        if (!empty($grado)) {
                            $lista = $uDao->TomaAsistenciaCurso($grado);
                            if (!empty($lista)) {
                                foreach ($lista as $estudiante):
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $estudiante['documento']; ?>
                                            <input type="hidden" name="documentos[]" value="<?php echo $estudiante['documento']; ?>">
                                        </td>
                                        <td><?php echo $estudiante['apellido1'] . ' ' . $estudiante['apellido2']; ?></td>
                                        <td><?php echo $estudiante['nombre1'] . ' ' . $estudiante['nombre2']; ?></td>
                                        <td><?php echo $estudiante['grado']; ?></td>
                                        <td>
                                            <select name="estado_asistencias[<?php echo $estudiante['documento']; ?>]" class="select">
                                                <option value="Asistio">Asistió</option>
                                                <option value="Retardo">Retardo</option>
                                                <option value="Falla">Falla</option>
                                                <option value="Excusa">Excusa</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="justificacion_inasistencia[<?php echo $estudiante['documento']; ?>]" value="N/A">
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                            } else {
                                echo '<tr><td colspan="6">No hay estudiantes asignados a este curso.</td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="6">No has seleccionado ningún curso aún.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Se envía el documento del profesor -->
                <input type="hidden" name="profesor" value="<?php echo $u['nombre1']; ?> <?php echo $u['apellido1']; ?>">
                <button class="button" type="submit">ENVIAR</button>
                <button class="button" type="reset">LIMPIAR</button>
            </form>
        </div>

        <div class="container colordiv" style="max-height: 600px; overflow:auto;">
            <form method="GET" action="asistencias.php">
                <div class="filtros">
                    <h3>Fecha</h3>
                    <input type="date" name="fecha">
                    <h3>Curso</h3>
                    <select name="gradoc" id="curso" class="select">
                        <option value="">Selecciona un curso</option>
                        <?php
                        foreach ($cursos as $index => $curso) { ?>
                            <option value="<?php echo $curso['grado']; ?>"><?php echo $curso['grado']; ?></option>;
                        <?php
                        }
                        ?>
                    </select>
                    
                    <h3>Materia</h3>
                    <select name="idmatc" id="" class="select">
                        <option value="">Selecciona una materia</option>
                        <?php
                            foreach ($materias as $index => $materia) { ?>
                                <option value="<?php echo $materia['idMat']; ?>"><?php echo $materia['nomb_mat']; ?></option>;
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
                        <!-- <th>Docente</th> -->
                        <th>Curso</th>
                        <th>Fecha y Hora</th>
                        <th>Trimestre</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($asistencias as $index => $asistencia): ?>
                            <tr>
                                <td><?php echo $asistencia['nomb_mat']; ?></td>
                                <!-- <td><?php echo $asistencia['profesor']; ?></td> -->
                                <td><?php echo $asistencia['curso']; ?></td>
                                <td><?php echo $asistencia['fecha_asistencia']; ?></td>
                                <td><?php echo $asistencia['trimestre']; ?></td>
                                <td>
                                    <!-- Botón para ver/editar (abre el modal con el registro completo) -->
                                    <a href="#openModal" class="button_2" data-listado="<?php echo $asistencia['idlistado']; ?>" onclick="openList(this)">
                                        <i class='bx bx-edit op'></i>
                                    </a>
                                    <!-- Botón para eliminar -->
                                    <a href="controladores/controlador.asistencia.php?id=<?php echo $asistencia['idlistado']; ?>" onclick="return confirmar(event);" class="button_2">
                                        <i class='bx bx-trash op'></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>

            <!--div class="buttons">
                <button>Excel</button>
                <button>PDF</button>
            </div-->
        </div>
    </div>
    
    <script src="JS/script.js"></script>
</body>
</html>

<!-- Modal (se puede ocultar con CSS y mostrar con JavaScript) -->
<div id="openModal" class="modal_asis">
    <div class="modaldiv" id="contenidoModal">
        <a href="#close" id="exit" class="exit">X</a>
        <h2 style="text-align: center;">Detalle de Asistencia</h2>
        
        <!-- <div class="filtros">
            <h3>Curso:</h3>
            <p><?php echo $list['curso']; ?></p>
            <h3>Materia:</h3>
            <p><?php echo $list['nomb_mat']; ?></p>
        </div> -->

        <table id="user-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Estado Asistencia</th>
                    <th>Justificación</th>
                    <th>Curso</th>
                    <th>Materia</th>
                    <th>Actualizar</th>
                </tr>
            </thead>
            
            <tbody>
                <!-- <?php 
                /* if($list){
                    foreach ($list as $index => $li){
                        ?>
                        <tr>
                            <form action="" method="post">
                                <td><?php echo $li['apellido1'].''. $li['apellido2'].''. $li['nombre1'].''. $li['nombre2']; ?></td>
                                <td><?php echo $li['estado_asis']; ?></td>
                                <td><input type="text" value="<?php echo $li['justificacion_inasistencia']; ?>" required></td>
                                <td><button type="submit" class="button">Actualizar</button></td>
                            </form>
                        </tr>  
                    <?php
                    }
                } */
                ?> -->
            </tbody>
        </table>
    </div>
</div>

<script>
    // Función para confirmar eliminación (puedes implementarla según tu lógica)
    function confirmar(event) {
        if (!confirm("¿Estás seguro de eliminar este registro?")) {
            event.preventDefault();
        }
    }
</script>
