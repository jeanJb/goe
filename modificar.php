<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Registro</title>
    
    <style>
         .success-message {
            color: #4caf50; /* Color verde de éxito */
        }
        
        body {
            font-family: Arial, sans-serif;
            background-image: url('IMG/mora.avif'); /* Fondo con una imagen */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #000000
            ;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.5);
            width: 50%;
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-group img {
            width: 70px;
            margin-left: 10px; /* Mover las imágenes a la derecha */
        }

        .form-group label {
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 5px;
            width: 30%;
        }

        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="file"],
        .form-group select {
            width: 65%;
            padding: 10px;
            border: 1px solid #b2dfdb;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group button {
            background-color: #00796b;
            color: #ffffff;
            border: #ffffff;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 50%;
            margin-top: 1px;
        }

        .form-group button:hover {
            background-color: #004d40;
        }

        .message {
            color: #4caf50;
            font-weight: bold;
            text-align: center;
        }
        .submit {
            color: #;
           
        }
    </style>
</head>

<body>


    
    <div class="form-container">
        <form action="#">
        <button type="submit" class="btn w-100" style="background-color: #676767; color: #FFFFFF;">MODIFICAR</button>
       

 <!-- Documento -->
        <div class="form-group">
            <div>
                <label for="documento">Documento</label>
                <input type="text" id="documento" name="documento" required>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-telephone-inbound-fill" viewBox="0 0 16 16">
  <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0"/>
</svg>
        </div>

        <!-- Tipo de Documento -->
        <div class="form-group">
            <div>
                <label for="tipo_doc">Tipo Doc</label>
                <select id="tipo_doc" name="tipo_doc" required>
                    <option value="">SELECCIONE</option>
                    <option value="T.I">T.I TARJETA IDENTIDAD</option>
                    <option value="C.C">C.C CÉDULA CIUDADANÍA</option>
                    <option value="C.E">C.E CÉDULA EXTRANJERÍA</option>
                </select>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-telephone-inbound-fill" viewBox="0 0 16 16">
            <path d="M3.626 6.832A.5.5 0 0 1 4 6h8a.5.5 0 0 1 .374.832l-4 4.5a.5.5 0 0 1-.748 0z"/>
  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/>
</svg>
        </div>

        <!-- Nombres -->
        <div class="form-group">
            <div>
                <label for="nombres">Nombres</label>
                <input type="text" id="nombres" name="nombres" required>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-telephone-inbound-fill" viewBox="0 0 16 16">
  <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
  <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8m0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5"/>
</svg>
        </div>

        <!-- Apellidos -->
        <div class="form-group">
            <div>
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos">
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-telephone-inbound-fill" viewBox="0 0 16 16">
  <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
  <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8m0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5"/>
</svg>
        </div>

        <!-- Teléfono -->
        <div class="form-group">
            <div>
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" required>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-telephone-inbound-fill" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877zM15.854.146a.5.5 0 0 1 0 .708L11.707 5H14.5a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 1 0v2.793L15.146.146a.5.5 0 0 1 .708 0"/>
</svg>
        </div>

        <!-- Dirección -->
        <div class="form-group">
            <div>
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff"  class="bi bi-geo-alt" viewBox="0 0 16 16">
  <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
  <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
</svg>
        </div>

        <!-- Email -->
        <div class="form-group">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
  <path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 9.671V4.697l-5.803 3.546.338.208A4.5 4.5 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671"/>
  <path d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791"/>
</svg>
        </div>

        <!-- Clave -->
        <div class="form-group">
            <div>
                <label for="clave">Clave</label>
                <input type="password" id="clave" name="clave" required>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-file-earmark-lock2-fill" viewBox="0 0 16 16">
  <path d="M7 7a1 1 0 0 1 2 0v1H7z"/>
  <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M10 7v1.076c.54.166 1 .597 1 1.224v2.4c0 .816-.781 1.3-1.5 1.3h-3c-.719 0-1.5-.484-1.5-1.3V9.3c0-.627.46-1.058 1-1.224V7a2 2 0 1 1 4 0"/>
</svg>
        </div>

        <!-- Foto -->
        <div class="form-group">
            <div>
                <label for="foto">Foto</label>
                <input type="file" id="foto" name="foto">
            </div>
            
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-camera-fill" viewBox="0 0 16 16">
            <path d="M5 8c0-1.657 2.343-3 4-3V4a4 4 0 0 0-4 4"/>
  <path d="M12.318 3h2.015C15.253 3 16 3.746 16 4.667v6.666c0 .92-.746 1.667-1.667 1.667h-2.015A5.97 5.97 0 0 1 9 14a5.97 5.97 0 0 1-3.318-1H1.667C.747 13 0 12.254 0 11.333V4.667C0 3.747.746 3 1.667 3H2a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1h.682A5.97 5.97 0 0 1 9 2c1.227 0 2.367.368 3.318 1M2 4.5a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0M14 8A5 5 0 1 0 4 8a5 5 0 0 0 10 0"/>
</svg></svg>
        </div>

        <!-- Rol -->
        <div class="form-group">
            <div>
                <label for="id_rol">Rol</label>
                <select id="id_rol" name="id_rol" required>
                    <option value="">SELECCIONE</option>
                    <option value="101">ESTUDIANTE</option>
                    <option value="102">DOCENTE</option>
                    <option value="103">ACUDIENTE</option>
                    <option value="104">ADMINISTRADOR</option>
                </select>
            </div>
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4989ff" class="bi bi-telephone-inbound-fill" viewBox="0 0 16 16">
            <path d="M3.626 6.832A.5.5 0 0 1 4 6h8a.5.5 0 0 1 .374.832l-4 4.5a.5.5 0 0 1-.748 0z"/>
  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/>
</svg>
        </div>







                <!--nuevo  -->
                <div class="d-flex justify-content-center">
                    <input type="submit" name="modificar" id="modificar" value="Modificar" class="btn btn-primary">
                    <a href="home.html" class="btn btn-info btn-block mx-2 ">Inicio</a>
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
