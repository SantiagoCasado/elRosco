function listarMasGanadores() {

    var comboMasGanadores = document.getElementById("idComboMasGanadores");
    var dificultad = comboMasGanadores.options[comboMasGanadores.selectedIndex].value;

    var peticion = new XMLHttpRequest();
    peticion.open("GET", "php/rankings.php?dificultad="+dificultad, true);
    peticion.onreadystatechange = cargarMasGanadores;
    peticion.send(null);
    
    function cargarMasGanadores() {
    var tablaMasGanadores = document.getElementById("idMasGanadores");

    while (tablaMasGanadores.rows.length > 1) {
        tablaMasGanadores.deleteRow(1);
    }

    if ((peticion.readyState == 4) && (peticion.status==200))
        {	
            var victorias = JSON.parse(peticion.responseText);
            console.log(victorias)
            for (i = 0; i < victorias.length; i++) {
                var tr = document.createElement("tr");

                var victoria = victorias[i]

                var tdNombreUsuario = document.createElement("td");
                tdNombreUsuario.innerHTML = victoria.nombreUsuario;
                tr.appendChild(tdNombreUsuario);

                var tdVictorias = document.createElement("td");
                tdVictorias.innerHTML = victoria.victorias;
                tr.appendChild(tdVictorias);

                tablaMasGanadores.appendChild(tr);
            }
        }
    }
}

// function listaMejoresJugadores(idEmpresa, ciudadOrigen, ciudadDestino) {

//     var peticion = new XMLHttpRequest();
//     peticion.open("GET", "index_b.php?idEmpresa="+idEmpresa+"&ciudadOrigen="+ciudadOrigen+"&ciudadDestino="+ciudadDestino, true);
//     peticion.onreadystatechange = cargarServicios;
//     peticion.send(null);
    
//     function cargarServicios() {

//     var listarServicios = document.getElementById("listaServicios");

//     while (listarServicios.rows.length > 1) {
//         listarServicios.deleteRow(1);
//     }

//     if ((peticion.readyState == 4) && (peticion.status==200))
//         {	
//             var servicios = JSON.parse(peticion.responseText);


//             for (i = 0; i < servicios.length; i++) {
//                 var tr = document.createElement("tr");

//                 var servicio = servicios[i];

//                 var nro = document.createElement("td");
//                 nro.innerHTML = servicio.nroServicio;
//                 var estacionOrigen = document.createElement("td");
//                 estacionOrigen.innerHTML = servicio.estacionOrigenServicio;
//                 var estacionDestino = document.createElement("td");
//                 estacionDestino.innerHTML = servicio.estacionDestinoServicio;
//                 var horaSalida = document.createElement("td");
//                 horaSalida.innerHTML = servicio.horaSalidaServicio;
//                 var horaLlegada = document.createElement("td");
//                 horaLlegada.innerHTML = servicio.horaLlegadaServicio;
//                 var frecuencia = document.createElement("td");
//                 frecuencia.innerHTML = servicio.frecuenciaServicio;
//                 var precio = document.createElement("td");
//                 precio.innerHTML = servicio.precioServicio;

//                 tr.appendChild(nro);
//                 tr.appendChild(estacionOrigen);
//                 tr.appendChild(estacionDestino);
//                 tr.appendChild(horaSalida);
//                 tr.appendChild(horaLlegada);
//                 tr.appendChild(frecuencia);
//                 tr.appendChild(precio);

//                 listarServicios.appendChild(tr);
//             }
//         }
//     }
// }