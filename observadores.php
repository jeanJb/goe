<?php
session_start();

if (!isset($_SESSION['documento'])) {
    header("Location:./index.html");
}
require 'ModeloDAO/UsuarioDao.php';
require 'ModeloDTO/UsuarioDto.php';
require 'Utilidades/conexion.php';

$uDao = new UsuarioDao();

$allObs = $uDao->ListarObservadores();
$cursos = $uDao->ListarCursos();
$u = $uDao->user($_SESSION['documento']);

$documento = $_GET['user'] ?? null;
$obs = $uDao->listarUsuariosper($documento);

$estudiantes = isset($estudiantes) ? $estudiantes : [];

if (isset($_GET['grado']) && is_numeric($_GET['grado'])) {
    $grado = $_GET['grado'];
    $estudiantes = $uDao->listarEstudiantesPorCurso($grado);
    echo json_encode($estudiantes);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="STYLES/diseño.css">
    <link rel="stylesheet" href="STYLES/observadores.css">
    <title>GOE
    </title>
</head>
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
    <h1 style="float: left; margin: 12px 0 10px 10px;"><?php echo $u['nombre1']; ?> <?php echo $u['apellido1']; ?></h1>
        <ul>
            <li><a href="alert.html"><img src="IMG/message-circle.svg" alt=""><!-- <p>Notificaciones</p> --></a></li>
            <li><a href="perfil.php"><img src="IMG/user.svg" alt="" style="border: 1px #50c6e3 solid;"><!-- <p>Perfil</p> --></a></li>
            <li><a href="exit.php"><img src="IMG/exit.svg" alt=""><!-- <p>Cerrar Sesion</p> --></a></li>
        </ul>
    </nav>

    <!--Contenido de la pagina-->
    <div class="contenido">
        <div class="datos colordiv">
            <h2>Datos del Estudiante</h2>
            <form method="GET" action="observadores.php">
                <div class="uno">
                    <h3>Curso:</h3>
                    <select name="grado" id="curso" onchange="cargarEstudiantes()" class="select">
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
                <h3>Correo: <p><?php echo $ob['email']; ?></p></h3>
            </div>

            <div class="c">
                <h3>Telefono: <p><?php echo $ob['telefono']; ?></p></h3>
                <h3>Direccion: <p><?php echo $ob['direccion']; ?></p></h3>
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

            <form action="controladores/controlador.observador.php" method="POST">
            <div class="" style="display: flex; flex-direction: row; align-items: end;">
                    <h3 style="margin-right: 8px;">Documento:</h3>
                    <input type="number" name="documento" value="<?php echo $ob['documento']; ?>" required>
                </div>
                <br> 
            
                <div class="" style="display: flex; flex-direction: row; align-items: end;">
                    <h3 style="margin-right: 8px;">FECHA:  </h3>
                    <input type="datetime-local" name="fecha" id="">
                </div>
    
                <br>
    
                <div class="" style="width: 50%; height: auto; float: left;">
                    <h1>Porque se hace la Observacion?</h1>
                    <textarea name="descripcion_falta" id=""></textarea>
                </div>
                <br>

                <div class="" style="width: 50%; height: auto; float: left;">
                    <h1>Compromiso del Estudiante</h1>
                    <textarea name="compromiso" id=""></textarea>
                </div>
                <br>

                <div class="" style="">
                    <h3 style="margin-right: 8px;">Docente:</h3>
                    <br>
                    <input type="text" name="seguimiento" value="<?php echo $u['nombre1']; ?> <?php echo $u['apellido1']; ?>" required>
                </div>
                <br>

    
                <h3>Nivel de la Falta:</h3>
                <select name="falta" id="" class="select">
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
            <br>
            <table id="user-table">
                <thead>
                    <tr>
                        <td>Estudiante</td>
                        <td>Descripcion</td>
                        <td>Nivel de la Falta</td>
                        <td>Opciones</td>
                        <td>Eliminar</td>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($allObs as $index => $obs) { ?>
                        <tr>
                            <td><?php echo $obs['nombre1']; ?> <?php echo $obs['apellido1']; ?> <?php echo $obs['apellido2']; ?></td>
                            <td><?php echo $obs['descripcion_falta']; ?></td>
                            <td><?php echo $obs['falta']; ?></td>
                            <td><a href="#openModal" class="button" id="<?php echo $index; ?>" onclick="verObservation(<?php echo $index; ?>)">VER</a></td>
                            <td><a href="controladores/controlador.observador.php?id=<?php echo $obs['idobservador'];?>" ><!-- onclick=" return confirmar(event);" -->
                            <img src=" IMG/trash.svg" height="48" width="48" class=" img-thumbnail" alt=""></a></td>
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
    <div class="colordiv">
        <a href="#close" id="close" class="close">X</a>
        <h1 style="text-align: center;">Actualizar Observación</h1>
        <form action="controladores/controlador.observador.php" method="POST">
            
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
    }

</script>

<script>

    function cargarEstudiantes() {
        const curso = document.getElementById('curso').value;

        if (curso) {
            fetch(`obtener_estudiantes.php?grado=${curso}`)
                .then(response => response.json())
                .then(data => {
                    const estudiantes = document.getElementById('estudiantes');
                    estudiantes.innerHTML = '<option value="">Seleccione</option>';

                    if (data.length > 0) {
                        data.forEach(estudiante => {
                            const option = document.createElement('option');
                            option.value = estudiante.documento;
                            option.textContent = `${estudiante.nombre1} ${estudiante.apellido1}`;
                            estudiantes.appendChild(option);
                        });
                    } else {
                        estudiantes.innerHTML = '<option value="">No hay estudiantes</option>';
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

    function confirmar(event) {
        event.preventDefault();  // Para evitar que el formulario se envíe directamente al action
        const link = event.currentTarget.href;
        Swal.fire({
        icon: 'warning',
        title: '¿Estas seguro de eliminar el registro?',
        text: 'Esa acción no se puede deshacer',
        background: '#f7f7f7',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí,eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton:'btn btn-success',
            cancelButton: 'btn btn-secondary',
        }
    }).then((result)=>{
        if(result.isConfirmed) {
            window.location.href = link;
        }
    });  
        
    }
</script>

<!--https://layers.to/layers/clv0q7xjj005dky0hzjj7rhtb-->