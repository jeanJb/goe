<!DOCTYPE html>
<html lang="es">

<head>


       
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Listado</title>
    <style>
        body {
            background-image: url('img/morado.png'); /* Cambia la ruta a la imagen que quieres usar */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
    
    <script>
    function confirmar(event) {
        event.preventDefault();  // Para evitar que el formulario se envíe directamente al action
        const link = event.currentTarget.href;
        Swal.fire({
        icon: 'warning',
        title: '¿Estas seguro de eliminar el registro?',
        text: 'Esa acción no se puede deshacer',
        background: '#b0c0ff',
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

<body class="container" style="background-color:#000000 ;">

    <center>
        <table class="table table-striped mt-5" border=1>
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Id_rol</th>
                    <th>Email</th>
                    <th>Clave</th>
                    <th>Tipo_doc</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Telefono</th>
                    <th>Direccion</th>
                    <th>Foto</th>

                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>

                    <td><a href="controladores/controlador.usuario.php?id=<?php echo $user['idUsuario'];?>
                    " onclick=" return confirmar(event);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="black" class="bi bi-person-dash" viewBox="0 0 16 16">
  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M11 12h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1m0-7a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
  <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
</svg></a>
                    </td>
                </tr>
                

            </tbody>
        </table>
    </center>
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

    <a href="registro.php" class="btn btn-danger" > Registrarse </a>
    <a href="home.html" class="btn btn-success"> Regresar </a>
   
</body>

</html>
