function validar_usuarios(event) {
    event.preventDefault(); // Detiene el envío del formulario

    var documento = document.getElementById("documento").value.trim();
    var rol = document.getElementById("id_rol").value.trim();
    var correo = document.getElementById("email").value.trim();
    var clave = document.getElementById("clave").value.trim();
    var td = document.getElementById("tipo_doc").value.trim();
    var nombre1 = document.getElementById("nombre1").value.trim();
    var nombre2 = document.getElementById("nombre2").value.trim();
    var apellido1 = document.getElementById("apellido1").value.trim();
    var apellido2 = document.getElementById("apellido2").value.trim();
    var telefono = document.getElementById("telefono").value.trim();
    var direccion = document.getElementById("direccion").value.trim();
    // var grado = document.getElementById("grado").value.trim();

    if (
        documento === "" || 
        rol === "" || 
        correo === "" || 
        clave === "" || 
        td === "" || 
        nombre1 === "" || 
        nombre2 === "" || 
        apellido1 === "" || 
        apellido2 === "" || 
        telefono === "" || 
        direccion === ""
    ) {
        Swal.fire({
            icon: 'error',
            title: 'Campos vacíos!!',
            text: 'Revisa los campos obligatorios, no pueden estar vacíos',
            background: '#f7f7f7',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Volver a intentar',
            customClass: {
                title: 'swal-title',
                popup: 'swal-popup',
            }
        });
        return false;
    }

     // Si todo está validado, envía el formulario
    event.target.submit();
    return true;
    console.log("Formulario enviado correctamente.");
}