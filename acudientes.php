<?php
/* session_start();
if(isset($_SESSION['documento'])){
    header('Location:index.html');

} */

header("Cache-Control: no-cache, no-store, must-revalidate");
header('Pragma:no-cache');
header("Expires:0");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> -->
    <link rel="stylesheet" href="STYLES/Bootstrap/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="acudiente.css">

</head>
<body> 
    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <space class="text-primary">GO</space>E
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="index.html">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">ACERCA DE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">SERVICIOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">PORTAFOLIO</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.html">GRUPO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">CONTACTANOS</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  text-primary" href="acudientes.html">LOGIN ACUDIENTES</a>
                    </li>

                </ul>

            </div>

            
        </div>
    </nav>


    <div class="container d-flex justify-content-center align-items-center min-vh-100">

    
            <div class="row border rounded-5 p-3 bg-white shadow box-area">

    
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #47495085;">
                <div class="featured-image mb-3">
                <img src="img/libro.png" class="img-fluid" style="width: 250px;">
                </div>
                <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">Ser verificado</p>
                <small class="text-white text-wrap text-center" style="width: 17rem;font-family: 'Courier New', Courier, monospace;">Lorem ipsum dolor sit amet consectetur</small>
            </div> 
    

            
            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>BIENVENIDOS</h2>
                        <p>Estamos felices de tenerte de vuelta.</p>
                    </div>
                    <form action="loginacu.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="txtacu" class="form-control form-control-lg bg-light fs-6" placeholder="Documento acudiente" name="txtpass">
                    </div>
                    <div class="input-group mb-3"> 
                        <input type="text" name="txtdoc" class="form-control form-control-lg bg-light fs-6" placeholder="Documento Estudiante" name="txtdoc" >
                    </div>
                    </div>
                    <div class="input-group mb-3">
                        <button class="btn btn-lg btn-primary w-100 fs-6" >Login</button>
                    </div>
                    </form>
            </div> 
    
            </div>
        </div>


</body>
</html>