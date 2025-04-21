function buscarEnTablas() {
    var input, filtro, tablas, filas, celdas, encontrado;
    input = document.getElementById("buscador");
    filtro = input.value.toLowerCase();
    tablas = document.getElementsByTagName("table");

    for (var t = 0; t < tablas.length; t++) {
        filas = tablas[t].getElementsByTagName("tr");

        for (var i = 1; i < filas.length; i++) { // Empieza en 1 para saltar el encabezado
            celdas = filas[i].getElementsByTagName("td", "th");
            encontrado = false;

            for (var j = 0; j < celdas.length; j++) {
                if (celdas[j].innerHTML.toLowerCase().includes(filtro)) {
                    encontrado = true;
                    break;
                }
            }

            filas[i].style.display = encontrado ? "" : "none";
        }
    }
}

function buscarEnTabla() {
    var input = document.getElementById("lupa");
    var filtro = input.value.toLowerCase();
    
    // Obtener solo la PRIMERA tabla con el ID "use-table"
    var tabla = document.querySelector(".table-one");
    var filas = tabla.getElementsByTagName("tr");

    for (var i = 1; i < filas.length; i++) { // Saltar el encabezado
        var celdas = filas[i].getElementsByTagName("th", "td");
        var encontrado = false;

        for (var j = 0; j < celdas.length; j++) {
            if (celdas[j].textContent.toLowerCase().includes(filtro)) {
                encontrado = true;
                break;
            }
        }

        filas[i].style.display = encontrado ? "" : "none";
    }
}

function buscarEnPrimeraTabla() {
    var input = document.getElementById("buscador");
    var filtro = input.value.toLowerCase();
    
    // Obtener solo la PRIMERA tabla con el ID "use-table"
    var tabla = document.querySelector("#user-table");
    var filas = tabla.getElementsByTagName("tr");

    for (var i = 1; i < filas.length; i++) { // Saltar el encabezado
        var celdas = filas[i].getElementsByTagName("th");
        var encontrado = false;

        for (var j = 0; j < celdas.length; j++) {
            if (celdas[j].textContent.toLowerCase().includes(filtro)) {
                encontrado = true;
                break;
            }
        }

        filas[i].style.display = encontrado ? "" : "none";
    }
}