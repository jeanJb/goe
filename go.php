<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header('Pragma:no-cache');
header("Expires:0");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="STYLES/index.css">
    <link rel="icon" href="IMG/logos/goe03.png" type="image/png">
    <title>GOE</title>
    <script>
        function ir() {
            window.location.href = "registro.php";
        }
    </script>
</head>
<body>
    <nav>
        <a href="index.html" class="atras a">ATRAS</a>
        <form action="loginproceso.php" method="POST">
            <div class="log">
                <img src="IMG//user.png" alt="">
                <h1>INICIAR SESION</h1>
                <br>
                <h3>Correo</h3>
                <input type="text" name="txtemail" required>
                <br>
                <h3>Contraseña</h3>
                <input type="password" name="txtpass" required>
                <br>    
                <br>    
                <button class="button" type="submit">INICIAR</button>
                <button href="Registro.php" type="reset" class="button" onclick="ir()">Registrarse</button>
                <br>
                <br>
                <a href="recovery.php" class="a">Recuperar Contraseña</a>
            </div>
        </form>
    </nav>
    <div class="img">
        <div class="ribbon"></div>
    </div>
    <!-- Contenedor para las alertas -->
    <div id="alert-container"></div>

<script>
    // Función para mostrar alertas
    function showAlert(message, type) {
        const alertContainer = document.getElementById('alert-container');

        // Crear el elemento de la alerta
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            ${message}
            <span class="close-btn" onclick="this.parentElement.classList.remove('show')">&times;</span>
        `;

        // Agregar la alerta al contenedor
        alertContainer.appendChild(alertDiv);

        // Mostrar la alerta
        setTimeout(() => alertDiv.classList.add('show'), 100);

        // Ocultar la alerta después de 5 segundos
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 500); // Eliminar después de la animación
        }, 5000);
    }

    // Obtener parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const message = urlParams.get('message');

    // Mostrar alerta según el estado
    if (status && message) {
        showAlert(decodeURIComponent(message), status);
    }
</script>
</body>
</html>