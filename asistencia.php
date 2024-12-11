<?php
session_start();

if (!isset($_SESSION['documento'])) {
    header("Location:./index.html");
    exit();
}

require 'ModeloDAO/UsuarioDao.php';
require 'ModeloDTO/UsuarioDto.php';
require 'Utilidades/conexion.php';

$uDao = new UsuarioDao();
$cursos = $uDao->ListarCursos();
$materias = $uDao->ListarMaterias();
$u = $uDao->user($_SESSION['documento']);

$fecha = $_GET['fecha'] ?? null;
$curso = $_GET['gradoc'] ?? null;
$materia = $_GET['idmatc'] ?? null;

if ($fecha && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
}

if ($curso && !preg_match('/^\w+$/', $curso)) {
    $curso = null;
}

if ($materia && !preg_match('/^\d+$/', $materia)) {
    $materia = null;
}

// Obtener las asistencias filtradas
$asistencias = $uDao->listarAsis($fecha, $curso, $materia);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="STYLES/diseño.css">
    <link rel="stylesheet" href="STYLES/asistencia.css">
    <title>GOE</title>
</head>
<script defer>
        function filtrarPorCurso(grado) {
            if (grado) {
                const url = `asistencia.php?grado=${encodeURIComponent(grado)}`;
                window.location.href = url;
            }
        }
    </script>
<body>


<div class="menu colordis">
        <div class="title">
            <h1>GOE</h1>
            <img src="IMG/GOE.jpg" alt="">
        </div>

        <ul>
            <li><a href="home.php"><img src="IMG/home.svg" alt=""><p>Home</p></a></li>
            <li><a href="observadores.php"><img src="IMG/info.svg" alt=""><p>Observador</p></a></li>
            <li><a href="asistencia.php"><img src="IMG/inbox.svg" alt=""><p>Asistencia</p></a></li>
            <li><a href="intro.php"><img src="IMG/user.svg" alt=""><p>Login</p></a></li>

        </ul>
    </div> 

    <!-- nav -->

    <nav class="nav colordis">
        <h1 style="float: left; margin: 12px 0 10px 10px;">
            <?php echo isset($u['nombre1']) ? $u['nombre1'] : 'Usuario'; ?>
            <?php echo isset($u['apellido1']) ? $u['apellido1'] : ''; ?>
        </h1>
        <ul>
            <li><a href="alert.html"><img src="IMG/message-circle.svg" alt=""><!-- <p>Notificaciones</p> --></a></li>
            <li><a href="perfil.php"><img src="IMG/user.svg" alt="" style="border: 1px #50c6e3 solid;"><!-- <p>Perfil</p> --></a></li>
            <li><a href="exit.php"><img src="IMG/exit.svg" alt=""><!-- <p>Cerrar Sesion</p> --></a></li>
        </ul>
    </nav>

    <!--Contenido de la pagina-->
    <div class="contenido">
        <form method="POST" action="controladores/controlador.asistencia.php">
            <div class="container colordiv">
                <div class="uno">
                    <h3>Fecha</h3>
                    <input type="datetime-local"  name="fecha_asistencia" required>
                    <h3>Curso</h3>
                    <select name="grado" id="curso" class="select" onchange="filtrarPorCurso(this.value)">
                        <option value="">Selecciona un curso</option>
                        <?php
                        foreach ($cursos as $index => $curso) { ?>
                            <option value="<?php echo $curso['grado']; ?>"><?php echo $curso['grado']; ?></option>;
                        <?php } ?>
                    </select>
                    <h3>Materia</h3>
                    <select name="idmat" id="" class="select" required>
                        <option value="">Selecciona una materia</option>
                        <?php
                            foreach ($materias as $index => $materia) { ?>
                                <option value="<?php echo $materia['idmat']; ?>"><?php echo $materia['nomb_mat']; ?></option>;
                            <?php } ?>
                    </select>
                </div>
    
                <br>
                <h1>Estudiantes</h1>
                <table id="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Apellidos</th>
                            <th>Nombres</th>
                            <th>Curso</th>
                            <th>Asistencia</th>
                            <th>Justificacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grado = isset($_GET['grado'])? $_GET['grado'] : '';

                        if (!empty($grado)) {
                            $lista = $uDao->TomaAsistenciaCurso($grado);
                            if (!empty($lista)) {
                                foreach ($lista as $index => $estudiante) {?>
                                    <tr>
                                        <td type="hidden" name="documentos[]" value="<?php echo $estudiante['documento']; ?>"><?php echo $estudiante['documento']; ?></td>
                                        <td><?php echo $estudiante['apellido1']; ?> <?php echo $estudiante['apellido2']; ?></td>
                                        <td><?php echo $estudiante['nombre1']; ?> <?php echo $estudiante['nombre2']; ?></td>
                                        <td><?php echo $estudiante['grado']; ?></td>
                                        <td style="display: flex; flex-direction: row; justify-content: first baseline;">
                                            <select name="estado_asistencias[<?php echo $estudiante['documento']; ?>]" id="" style="width: auto;" class="select">
                                                <option value="Asistio">Asistio</option>
                                                <option value="Retardo">Retardo</option>
                                                <option value="Falla">Falla</option>
                                                <option value="Excusa">Excusa</option>
                                            </select>
                                        </td>
                                        <td><input type="text" style="width: auto;" name="justificacion_inasistencia[<?php echo $estudiante['documento']; ?>]" value="N/A"></td>
                                    </tr>
                            <?php }
                            } else {
                                echo '<tr><td colspan="5">No hay estudiantes asignados a este curso.</td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No haz seleccionado ningun curso aun.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>  
                <button class="button" type="submit">ENVIAR</button>
                <button class="button" type="reset">LIMPIAR</button>
            </div>
            <input type="hidden" name="profesor" value="<?php echo $_SESSION['documento']; ?>">
        </form>




        <div class="container colordiv" style="max-height: 600px; overflow:auto;">
            <form method="GET" action="asistencia.php">
                <div class="uno">
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
                                <option value="<?php echo $materia['idmat']; ?>"><?php echo $materia['nomb_mat']; ?></option>;
                            <?php }
                        ?>
                    </select>
                </div>
                
                <button type="submit" class="button" style=" margin: 10px 42%;">Buscar</button>
            </form>
            
            <h1>Asistencias</h1>
            <table id="user-table">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Profesor</th>
                        <th>Fecha Hora</th>
                        <th>Opcion</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>

                <?php

                    if (!empty($asistencias)) {
                        foreach ($asistencias as $index => $asistencia) {?>
                            <tr>
                                <td><?php echo $asistencia['nomb_mat']; ?></td>
                                <td><?php echo $asistencia['profesor']; ?></td>
                                <td><?php echo $asistencia['fecha_asistencia']; ?></td>
                                <td><a href="#openModal" class="button" id="<?php echo $index; ?>" onclick="verObservation(<?php echo $index; ?>)">VER</a></td>
                                <td><a href="controladores/controlador.asistencia.php?id=<?php echo $asistencia['idasistencia'];?>
                                " onclick=" return confirmar(event);">
                                <img src=" IMG/trash.svg" height="48" width="48" class=" img-thumbnail" alt=""></a></td>
                            </tr>
                    <?php }
                    } else {
                        echo '<tr><td colspan="5">No hay asistencias disponibles.</td></tr>';
                    }?>

                    <!-- Add more rows here as needed -->
                </tbody>
            </table>
            <!--div class="buttons">
                <button>Excel</button>
                <button>PDF</button>
            </div-->
        </div>
    </div>
</body>
</html>


<!-- modal para actualizar y ver la asistencia -->


<div id="openModal" class="modal_asis">
    <div class="colordiv">
        <a href="#close" id="close" class="close">X</a>
        <h2 style="text-align: center;">Listado de Asistencia</h2>
        
        <br>
            <h3>Documento:</h3>
            <input type="text" name="documento" id="" value="<?php echo $asistencia['documento']; ?>" required>

            <br>
            <h3>Profesor:</h3>
            <input type="text" name="profesor" id="" min="2" max="3" value="<?php echo $asistencia['profesor']; ?>" required>

            <br>
            <h3>Estado:</h3>
            <input type="text" name="estado" id="" value="<?php echo $asistencia['estado_asis']; ?>" required>

            <br>
            <h3>Materia:</h3>
            <input type="date" name="materia" id="" value="<?php echo $asistencia['nomb_mat']; ?>" required>
            
            <br>
            <h3>fecha:</h3>
            <input type="text" name="fecha" id="" value="<?php echo $asistencia['fecha_asistencia']; ?>" required>

            <br>
            <h3>Justificacion:</h3>
            <input type="text" name="justificacion" id="" value="<?php echo $asistencia['justificacion_inasistencia']; ?>" required>
            <br>

            <button class="button" type="submit" name="actualizar" id="actualizar">ACTUALIZAR</button>
        </form>
    </div>
</div>


<script>
    function verListado(index) {
        // Obtener la lista de observaciones desde PHP (puedes pasarla al JS de alguna manera, por ejemplo, como un JSON en un input oculto)
        const asistencias = <?php echo json_encode(array_map('htmlspecialchars', $asistencias)); ?>;
        
        // Buscar la observación que corresponde al índice
        const asistencia = asistencias[index];

        document.getElementById("profesor").innerText = asistencia.profesor;
        document.getElementById("documento").innerText = asistencia.documento;
        document.getElementById("estado_asis").innerText = asistencia.estado_asis;
        document.getElementById("nomb_mat").innerText = asistencia.nomb_mat;
        document.getElementById("fecha_asistencia").innerText = asistencia.fecha_asistencia;
        document.getElementById("justificacion_inasistencia").innerText = asistencia.justificacion_inasistencia;
    }

    function verAsistencia(index) {
        const asistencia = asistencias[index];
        if (asistencia) {
            document.getElementById("profesor").value = asistencia.profesor || "N/A";
            document.getElementById("documento").value = asistencia.documento || "N/A";
            // Resto de los campos...
        } else {
            alert("La asistencia no existe.");
        }
    }


    /* function filtrarPorCurso(grado) {
        if (grado) {
            // Redirigir asegurando que no haya espacios o caracteres innecesarios
            const url = `./asistencia.php?grado=${encodeURIComponent(grado)}`;
            window.location.href = url;
        }
    } */
</script>


<!--https://layers.to/layers/clv0q7xjj005dky0hzjj7rhtb-->