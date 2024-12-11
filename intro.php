<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="STYLES/diseño.css">
    <link rel="stylesheet" href="STYLES/intro.css">
    <!-- <script src="JS/validaciones.js"></script> -->
    <title>GOE</title>
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
        <ul>
            <li><a href="alert.html"><img src="IMG/message-circle.svg" alt=""><!-- <p>Notificaciones</p> --></a></li>
            <li><a href="perfil.php"><img src="IMG/user.svg" alt="" style="border: 1px #50c6e3 solid;"><!-- <p>Perfil</p> --></a></li>
            <li><a href="exit.php"><img src="IMG/exit.svg" alt=""><!-- <p>Cerrar Sesion</p> --></a></li>
        </ul>
    </nav>  

    <!--Contenido de la pagina-->
    <div class="contenido" style="display: grid;place-items: center;">
        <form  class="form colordiv" action="controladores/controlador.usuarios.php" method="POST" onsubmit="validar_usuarios(event)">
            <p class="titles">Registrar Usuarios </p>
            <p class="message">Seleccione adecuadamente el rol del usuario. </p>
            <label>
                <input class="input" name="documento" id="documento" type="text" placeholder="" >
                <span>Documento</span>
            </label>

            <label>
                <h3>Rol</h3>
                <select name="id_rol" id="id_rol" class="select" style="width: auto;">
                    <option value="101">ESTUDIANTE</option>
                    <option value="102">DOCENTE</option>
                    <option value="104">ADMINISTRADOR</option>
                </select>
            </label>
            <label>
                <input class="input" name="email" id="email" type="email" placeholder="">
                <span>Email</span>
            </label> 
                
            <label>
                <input class="input" name="clave" id="clave" type="password" placeholder="">
                <span>Contraseña</span>
            </label>

            <label>
                <h3>Tipo de Documento</h3>
                <select name="tipo_doc" id="tipo_doc" class="select" style="width: auto;">
                    <option value="T.I">T.I</option>
                    <option value="C.C">C.C</option>
                    <option value="C.E">C.E</option>
                </select>
            </label>

            <div class="flex">
                <label>
                    <input class="input" name="nombre1" id="nombre1" type="text" placeholder="">
                    <span>Primer Nombre</span>
                </label>
        
                <label>
                    <input class="input" name="nombre2" id="nombre2" type="text" placeholder="">
                    <span>Segundo Nombre</span>
                </label>
            </div>  

            
                <div class="flex">
                <label>
                    <input class="input" name="apellido1" id="apellido1" type="text" placeholder="">
                    <span>Primer Apellido</span>
                </label>
        
                <label>
                    <input class="input" name="apellido2" id="apellido2" type="text" placeholder="">
                    <span>Segundo Apellido</span>
                </label>
            </div>  
            <label>
                <input class="input" name="telefono" id="telefono" type="number" placeholder="">
                <span>Telefono</span>
            </label>
            <label>
                <input class="input" name="direccion" id="direccion" type="text" placeholder="">
                <span>Direccion</span>
            </label>
<!--             <label>
                <input class="input" name="foto" id="foto" type="file" accept="image*">
                <span>Foto</span>
            </label> -->
            <label>
                <input class="input" name="grado" id="grado" type="number" placeholder="">
                <span>Grado</span>
            </label>

            <button type="submit" class="submit" name="registro" id="registro">Registrar</button>
        </form>
    </div>
</body>
</html>