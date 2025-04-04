<?php
session_start();

if (!isset($_SESSION['documento'])) {
    header("Location:./index.html");
}
require 'ModeloDAO/UsuarioDao.php';
require 'ModeloDTO/UsuarioDto.php';
require 'Utilidades/conexion.php';

$uDao = new UsuarioDao();

$u = $uDao->user($_SESSION['documento']);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="STYLES/diseño.css">
    <link rel="stylesheet" href="STYLES/home.css">
    <title>GOE
    </title>
</head>
<body>
    <div class="main-header">
        <!-- <label for="btn-nav" class="btn-nav"><i class="fas fa-bars">x</i></label>
        <input type="checkbox" id="btn-nav"> -->
        <div class="menu colordis n">
            <div class="title">
                <h1>GOE</h1>
                <img src="IMG/GOE.jpg" alt="">
            </div>

            <ul class="navegation">
                <li><a href="home.php"><img src="IMG/home.svg" alt=""><p>Home</p></a></li>
                <li><a href="observadores.php"><img src="IMG/info.svg" alt=""><p>Observador</p></a></li>
                <li><a href="asistencia.php"><img src="IMG/inbox.svg" alt=""><p>Asistencia</p></a></li>
                <li><a href="intro.php"><img src="IMG/user.svg" alt=""><p>Login</p></a></li>
            </ul>
        </div> 
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
        <div class="cuadros">
            <div class="uno colordiv"></div>
            <div class="dos colordiv">
                <img src="./IMG/user.svg" alt="">
                <h3 style="text-align: center;">
                    <?php echo isset($u['nombre1']) ? $u['nombre1'] : 'Usuario'; ?>
                    <?php echo isset($u['apellido1']) ? $u['apellido1'] : ''; ?>
                </h3>
            </div>
            <div class="colordiv slider">
                <!-- <div class="slider-frame">
                    <ul>
                        <li><img src="../IMG/White-Lamborghini-Huracan.jpg" alt=""></li>
                        <li><img src="../IMG/portada.jpg" alt=""></li>
                        <li><img src="../IMG/White-Lamborghini-Huracan.jpg" alt=""></li>
                        <li><img src="../IMG/portada.jpg" alt=""></li>
                    </ul>
                </div> -->

                <figure>
                    <div class="slide">
                        <h1></h1>
                        <img src="./IMG/jfr1.jpg" alt="">
                    </div>

                    <div class="slide">
                        <h1></h1>
                        <img src="./IMG/jfr2.jpg" alt="">
                    </div>

                    <div class="slide">
                        <h1></h1>
                        <img src="./IMG/jfr3.jpg" alt="">
                    </div>

                    <div class="slide">
                        <h1></h1>
                        <img src="./IMG/jfr4.jpg" alt="">
                    </div>
                </figure>
            </div>
        </div>
        
        <!-- <div class="cell colordiv">
            <div></div>
            <iframe src="https://www.google.com/maps/embed?pb=!3m2!1ses!2sco!4v1727382609250!5m2!1ses!2sco!6m8!1m7!1sy5ycthCLRx4R76m1rGpaiw!2m2!1d4.574753243950389!2d-74.08742229769808!3f36.597942!4f0!5f0.7820865974627469" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" frameborder="0"></iframe>
        </div> -->
        <iframe class="cell"  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3977.106624637012!2d-74.08991592065429!3d4.574864000000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3f98f0356fc28f%3A0xcfcd594237664a0c!2sColegio%20Jos%C3%A9%20F%C3%A9lix%20Restrepo%20Sede%20D!5e0!3m2!1ses!2sco!4v1727383592848!5m2!1ses!2sco" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" frameborder="0">
            <div></div>
        </iframe>
    </div>
</body>
</html>

<!--https://layers.to/layers/clv0q7xjj005dky0hzjj7rhtb-->