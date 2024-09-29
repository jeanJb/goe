<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/diseño.css">
    <link rel="stylesheet" href="css/home.css">
    <title>Registro observador</title>
</head>

<body class="container m-0 row justify-content-center">
    <div class="menu colordis">
        <div class="title">
            <h1>GOE</h1>
            <img src="IMG/GOE.jpg" alt="">
        </div>
        <ul>
            <li><a href="#"><img src="IMG/home.svg" alt=""><p>Home</p></a></li>
            <li><a href="#"><img src="IMG/info.svg" alt=""><p>Observador</p></a></li>
            <li><a href="#"><img src="IMG/inbox.svg" alt=""><p>Asistencia</p></a></li>
            <li><a href="#"><img src="IMG/user.svg" alt=""><p>Login</p></a></li>
        </ul>
    </div>
    <main class="main col">
        <h2></h2>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-6 mt-4">
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
                    <button type="submit" name="registro" class="btn btn-primary">Registrar</button>
                    <a href="listado.php" class="btn btn-info btn-md btn-block">REPORTE</a>
                    <a href="index.php" class="btn btn-info btn-md btn-dark">regresar</a>
                </form>
            </div>
        </div>
    </main>






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