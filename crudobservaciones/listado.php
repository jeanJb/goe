<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    
    
    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Listado</title>
    <script>
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

</head>

<body class="container-fluid">

 
   
            <main class="main col ">
                <h2>REPORTE DE OBSERVACIONES </h2>
                <table class=" table table-container table-striped table-hover table-bordered table-responsive mt-4">
            <thead class="table-dark light-header">
                <tr class="text-center">
                    <th style="font-weight:normal">idobservador</th>
                    <th style="font-weight:normal" >documento</th>
                    <th style="font-weight :normal">fecha</th>
                    <th style="font-weight :normal">compromiso</th>
                    <th style="font-weight :normal">firma</th>
                    <th style="font-weight :normal">seguimiento</th>
                    <th style="font-weight :normal">falta</th>
                    <th style="font-weight:normal">Modificar</th>
                    <th style="font-weight :normal">Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'ModeloDAO/UsuarioDao.php';
                require 'ModeloDTO/UsuarioDto.php';
                require 'Utilidades/conexion.php';

                $uDao = new UsuarioDao();
                $allUsers = $uDao->listarTodos();
                foreach($allUsers as $user){?>
                <tr class="text-center">
                    <td><?php echo $user['idobservador'];?></td>
                    <td><?php echo $user['documento'];?></td>
                    <td><?php echo $user['fecha'];?></td>
                    <td><?php echo $user['compromiso'];?></td>
                    <td><?php echo $user['firma'];?></td>
                    <td><?php echo $user['seguimiento'];?></td>
                    <td><?php echo $user['falta'];?></td>
                    <td><a href="modificar.php?id=<?php echo $user['idobservador']; ?>"> <img src="img/editar3.png"
                                height="48" width="48" class=" img-thumbnail" alt=""></a></td>
                    <td><a href="controladores/controlador.usuarios.php?id=<?php echo $user['idobservador'];?>
                    " onclick=" return confirmar(event);">
                            <img src=" img/eliminar2.png" height="48" width="48" class=" img-thumbnail" alt=""></a>
                    </td>
                </tr>
                <?php
                } ?>

            </tbody>
        </table>


<div class="text-left">
<a href="registro.php" class="btn btn-danger"> REGISTRAR OBSERVACION </a>
<a href="index.php" class="btn btn-success"> REGRSAR </a>
<a href="reporte.php" class="btn btn-warning">REPORTE </a>
</div>
    </main>
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



</body>

</html>