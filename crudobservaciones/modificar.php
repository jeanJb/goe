<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <title>Modificar</title>
</head>

<body>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <?php
            require 'ModeloDAO/UsuarioDao.php';
            require 'ModeloDTO/UsuarioDto.php';
            require 'Utilidades/conexion.php';

            if($_GET['id']!=NULL){
                $uDao = new UsuarioDao();
                $usuario = $uDao->obtenerUsuario($_GET['id']);
                }
            ?>

<form action="controladores/controlador.usuarios.php" method="POST">
                    <h3 class="text-center">REGISTRAR</h3>
                    <label for="idobservador">ID Observador</label>
                    <input type="text" name="idobservador" class="form-control">
                    <br>
                    <label for="documento">Documento</label>
                    <input type="text" name="documento" class="form-control">
                    <br>
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" class="form-control">
                    <br>
                    <label for="descripcion_falta">Descripción Falta</label>
                    <input type="text" name="descripcion_falta" class="form-control">
                    <br>
                    <label for="compromiso">Compromiso</label>
                    <input type="text" name="compromiso" class="form-control">
                    <br>
                    <label for="firma">Firma</label>
                    <input type="text" name="firma" class="form-control">
                    <br>
                    <label for="seguimiento">Seguimiento</label>
                    <input type="text" name="seguimiento" class="form-control">
                    <br>
                    <label for="falta">Falta</label>
                    <input type="text" name="falta" class="form-control">
                    <br>
                <!--nuevo  -->
                <div class="d-flex justify-content-center">
                    <input type="submit" name="modificar" id="modificar" value="Modificar" class="btn btn-primary">
                    <a href="index.php" class="btn btn-info btn-block mx-2 ">Inicio</a>
                    <a href="listado.php" class="btn btn-info btn-dark mx-2 ">regresar</a>
                </div>
            </form>

        </div>
        <div class="col-md-4 mt-4"></div>
    </div>

    <?php
    if(isset($_GET['mensaje'])){
        ?>

    <div class="row"> <br><br>
        <div class="col-md-6"></div>
        <div class="col-md-4 text-center">
            <h4><?php echo $mensaje = $_GET['mensaje']?>
            </h4>
        </div>
        <div class="col-md-5"></div>
    </div>

    <?php } ?>

</body>

</html>