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

function listaMejoresJugadores() {

    var comboMejoresPuntajes = document.getElementById("idComboMejoresPuntajes");
    var puntaje = comboMejoresPuntajes.options[comboMejoresPuntajes.selectedIndex].value;

    var comboMejoresTiempo = document.getElementById("idComboMejoresTiempo");
    var tiempoUtilizado = comboMejoresTiempo.options[comboMejoresTiempo.selectedIndex].value;


    var peticion = new XMLHttpRequest();
    peticion.open("GET", "php/rankings.php?puntaje="+puntaje+"&tiempoUtilizado="+tiempoUtilizado, true);
    peticion.onreadystatechange = cargarMejoresJugadores;
    peticion.send(null);
    
    function cargarMejoresJugadores() {
    var tablaMejoresJugadores = document.getElementById("idMejoresJugadores");

    while (tablaMejoresJugadores.rows.length > 1) {
        tablaMejoresJugadores.deleteRow(1);
    }

    if ((peticion.readyState == 4) && (peticion.status==200))
        {	
            var mejoresJugadores = JSON.parse(peticion.responseText);
            console.log(mejoresJugadores)
            for (i = 0; i < mejoresJugadores.length; i++) {
                var tr = document.createElement("tr");

                var mejorJugador = mejoresJugadores[i]

                var tdNombreUsuario = document.createElement("td");
                tdNombreUsuario.innerHTML = mejorJugador.nombreUsuario;
                tr.appendChild(tdNombreUsuario);

                var tdPuntaje = document.createElement("td");
                tdPuntaje.innerHTML = mejorJugador.puntaje;
                tr.appendChild(tdPuntaje);

                var tdTiempoUtilizado = document.createElement("td");
                tdTiempoUtilizado.innerHTML = mejorJugador.tiempoUtilizado + ' segundos';
                tr.appendChild(tdTiempoUtilizado);

                tablaMejoresJugadores.appendChild(tr);
            }
        }
    }
}