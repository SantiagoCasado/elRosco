function listarEmpresas() {

    var comboOrigen = document.getElementById("comboOrigen");
    var ciudadOrigen = comboOrigen.options[comboOrigen.selectedIndex].value;

    var comboDestino = document.getElementById("comboDestino");
    var ciudadDestino = comboDestino.options[comboDestino.selectedIndex].value;

    var peticion = new XMLHttpRequest();
    peticion.open("GET", "index_a.php?comboOrigen="+ciudadOrigen+"&comboDestino="+ciudadDestino, true);
    peticion.onreadystatechange = cargarEmpresas;
    peticion.send(null);
    
    function cargarEmpresas() {
    var listarEmpresas = document.getElementById("listaEmpresas");

    while (listarEmpresas.rows.length > 1) {
        listarEmpresas.deleteRow(1);
    }

    if ((peticion.readyState == 4) && (peticion.status==200))
        {	
            var empresas = JSON.parse(peticion.responseText);

            for (i = 0; i < empresas.length; i++) {
                var tr = document.createElement("tr");

                var empresa = empresas[i]
                console.log("idEmpresa= "+empresa.idEmpresa);
                console.log("Ciudad Origen = "+empresa.ciudadOrigenServicio);
                console.log("Ciudad Destino ="+empresa.ciudadDestinoServicio);

                var logo = document.createElement("img");
                logo.src = empresa.logoEmpresa;
                logo.className = "logoEmpresa";
                logo.setAttribute("onclick", "listarServicios('" + empresa.idEmpresa + "', '" + empresa.ciudadOrigenServicio + "', '" + empresa.ciudadDestinoServicio + "')");
                // logo.onclick = function() {
                //     listarServicios(empresa.idEmpresa, empresa.ciudadOrigenServicio, empresa.ciudadDestinoServicio);
                // }
                var nombre = document.createElement("p");
                nombre.innerHTML = empresa.nombreEmpresa;
                var pais = document.createElement("p");
                pais.innerHTML = empresa.paisEmpresa;
                var web = document.createElement("a");
                web.innerHTML = empresa.webEmpresa;
                var cantidadServicios = document.createElement("p");
                cantidadServicios.innerHTML = empresa.cantidadServicios;

                var tdLogo = document.createElement("td");
                tdLogo.appendChild(logo);
                tr.appendChild(tdLogo);

                var tdNombre = document.createElement("td");
                tdNombre.appendChild(nombre);
                tr.appendChild(tdNombre);

                var tdPais = document.createElement("td");
                tdPais.appendChild(pais);
                tr.appendChild(tdPais);

                var tdWeb = document.createElement("td");
                tdWeb.appendChild(web);
                tr.appendChild(tdWeb);

                var tdCantidadServicios = document.createElement("td");
                tdCantidadServicios.appendChild(cantidadServicios);
                tr.appendChild(tdCantidadServicios);

                listarEmpresas.appendChild(tr);
            }
        }
    }
}

function listarServicios(idEmpresa, ciudadOrigen, ciudadDestino) {

    var peticion = new XMLHttpRequest();
    peticion.open("GET", "index_b.php?idEmpresa="+idEmpresa+"&ciudadOrigen="+ciudadOrigen+"&ciudadDestino="+ciudadDestino, true);
    peticion.onreadystatechange = cargarServicios;
    peticion.send(null);
    
    function cargarServicios() {

    var listarServicios = document.getElementById("listaServicios");

    while (listarServicios.rows.length > 1) {
        listarServicios.deleteRow(1);
    }

    if ((peticion.readyState == 4) && (peticion.status==200))
        {	
            var servicios = JSON.parse(peticion.responseText);


            for (i = 0; i < servicios.length; i++) {
                var tr = document.createElement("tr");

                var servicio = servicios[i];

                var nro = document.createElement("td");
                nro.innerHTML = servicio.nroServicio;
                var estacionOrigen = document.createElement("td");
                estacionOrigen.innerHTML = servicio.estacionOrigenServicio;
                var estacionDestino = document.createElement("td");
                estacionDestino.innerHTML = servicio.estacionDestinoServicio;
                var horaSalida = document.createElement("td");
                horaSalida.innerHTML = servicio.horaSalidaServicio;
                var horaLlegada = document.createElement("td");
                horaLlegada.innerHTML = servicio.horaLlegadaServicio;
                var frecuencia = document.createElement("td");
                frecuencia.innerHTML = servicio.frecuenciaServicio;
                var precio = document.createElement("td");
                precio.innerHTML = servicio.precioServicio;

                tr.appendChild(nro);
                tr.appendChild(estacionOrigen);
                tr.appendChild(estacionDestino);
                tr.appendChild(horaSalida);
                tr.appendChild(horaLlegada);
                tr.appendChild(frecuencia);
                tr.appendChild(precio);

                listarServicios.appendChild(tr);
            }
        }
    }
}
