function crearVistaJuego(partida) {
    crearVistaRoscos(partida.roscos);

    var enJuego = true;
    vistaInteraccion(partida.jugadores[partida.turnoActual], partida.roscos[partida.jugadores[partida.turnoActual].id], partida.turnoActual, enJuego);
}

function crearVistaRoscos(roscos) {
    Object.entries(roscos).forEach(([idJugador, rosco]) => {
        // Se obtiene la zona del jugador
        var zonaJugador = document.getElementById('idLetrasJugador' + idJugador);

        // Se obtienen las preguntas del rosco
        var preguntas = rosco.preguntasPendientes;

        // Se crea el Label para cada letra (pregunta) del rosco
        preguntas.forEach(pregunta => {
            var label = document.createElement("label");
            label.id = pregunta.idPregunta;
            label.className = pregunta.estadoRespuesta;
            label.innerHTML = pregunta.letra;

            zonaJugador.appendChild(label);
        });
    });
}

function vistaInteraccion(jugador, rosco, turnoActual, enJuego) {

    var h2Turno = document.getElementById('idTurnoDe');
    h2Turno.innerHTML = jugador.nombreUsuario;

    var strLetra = document.getElementById('idSiguienteLetra');
    strLetra.innerHTML = rosco.preguntasPendientes[0].letra; // Primer letra del arreglo preguntasPendientes es la siguiente del jugador

    if (enJuego) {
        // VISTA JUEGO

        // Iniciar temporizador del jugador
        
        // Mostrar letra y descripcion del rosco
        var strLetra = document.getElementById('idSiguienteLetra');
        strLetra.innerHTML = rosco.preguntasPendientes[1].letra;

        var pregunta = rosco.preguntasPendientes[0];

        var formularioJuego = document.getElementById('idFormularioJuego');
        formularioJuego.innerHTML = '';

        var h3Letra = document.createElement('h3');
        h3Letra.innerHTML = 'Letra ' + pregunta.letra;
        formularioJuego.appendChild(h3Letra);
        formularioJuego.appendChild(document.createElement('br'));
        
        var labelDescripcion = document.createElement('label');
        labelDescripcion.htmlFor = 'idRespuesta';
        labelDescripcion.className = 'labelFormulario';
        labelDescripcion.innerHTML = pregunta.descripcion;
        formularioJuego.appendChild(labelDescripcion);
        
        if (false) {
        // if (ayudaAdicional) {
            var labelAyuda = document.createElement('label');
            labelAyuda.htmlFor = 'idRespuesta';
            labelAyuda.className = 'labelFormulario';
            labelAyuda.innerHTML = '<br>Contiene ' + pregunta.palabra.length + ' letras';
            formularioJuego.appendChild(labelAyuda);
        }
        
        formularioJuego.appendChild(document.createElement('br'));
        formularioJuego.appendChild(document.createElement('br'));
        var respuesta = document.createElement('input');
        respuesta.type = 'text'; 
        respuesta.id = 'idRespuesta';
        respuesta.name = 'respuesta';
        formularioJuego.appendChild(respuesta);
        respuesta.focus(); // Para escribir directamente sin seleccionar el input
        formularioJuego.appendChild(document.createElement('br'));
        formularioJuego.appendChild(document.createElement('br'));

        var botonArriesgar = document.createElement('button');
        botonArriesgar.innerHTML = 'Arriesgar';
        botonArriesgar.className = 'botonJuego' + turnoActual;
        botonArriesgar.onclick = function () {
            enJuego = true;
            //Fijarse los parametros necesarios
            verificarRespuestsa(respuesta);
        }
        formularioJuego.appendChild(botonArriesgar);
        formularioJuego.appendChild(document.createElement('br'));
        formularioJuego.appendChild(document.createElement('br'));

        var botonPasapalabra = document.createElement('button');
        botonPasapalabra.innerHTML = 'Pasapalabra';
        botonPasapalabra.onclick = function () {
            enJuego = false;
            vistaInteraccion(jugador, rosco, turnoActual, enJuego);
        }
        formularioJuego.appendChild(botonPasapalabra);



        // // Ejecutar la función asociada al botón Arriesgar cuando se presione Enter
        // respuesta.addEventListener('keydown', function(event) {
        // if (event.key === 'Enter') {
        //     botonArriesgar.click();
        // }
        // });
        
    } else {
        // VISTA CAMBIO TURNO
        
        // Detener temporizador Jugador actual

        // Cambiar turno al siguiente jugador

        // Vista cambio turno
        var strLetra = document.getElementById('idSiguienteLetra');
        strLetra.innerHTML = rosco.preguntasPendientes[0].letra;

        var formularioJuego = document.getElementById('idFormularioJuego');
        formularioJuego.innerHTML = '';
        var botonComenzarTurno = document.createElement('button');
        botonComenzarTurno.innerHTML = 'Comenzar Turno';
        botonComenzarTurno.className = 'botonJuego' + turnoActual;
        botonComenzarTurno.onclick = function () {
            enJuego = true;
            vistaInteraccion(jugador, rosco, turnoActual, enJuego);
        }
        formularioJuego.appendChild(botonComenzarTurno);
    }
}

function juegoRosco(rosco) {

    // Verificar estado del juego para ver si mostrar cambio de turno o el juego

    // Estado: cambioTurno
    // Cambiar estado a enJuego
    // Iniciar temporizador
    // Limpiar seccion
    // Mostrar temporizador
    // Mostrar descripcion de la letra
    // Mostrar boton de pasapalabra con accion de cambioTurno()
    // Mostrar boton de arriesgar con accion de verificarPalabra()
    // Mostrar input text

    // Estado: enJuego
    // Cambiar estado a cambioTurno
    // 

}



// PARA EL ROSCO EN FORMA DE CIRCULO
// function crearVistaRosco(roscos) {
//     roscos.forEach(rosco => {
//         // Se obtiene la zona del jugador
//         var zonaJugador = document.getElementById('idLetrasJugador' + rosco.idJugador);
//         var preguntas = rosco.preguntasPendientes;
//         var totalPreguntas = preguntas.length;
//         var radioCirculo = 300;
        
//         preguntas.forEach((pregunta, index) => {
//             var label = document.createElement("label");
//             label.id = pregunta.idPregunta;
//             label.className = pregunta.estadoRespuesta;
//             label.innerHTML = pregunta.letra;
            
//             // Calcula la posición de la letra en el círculo
//             var angulo = (index / totalPreguntas) * (2 * Math.PI) - Math.PI / 2; // (numeroLetra - 1) / 27 * 360 - 90 [grados]
//             var x = radioCirculo * Math.cos(angulo) + (zonaJugador.offsetWidth / 2) - 20;
//             var y = radioCirculo * Math.sin(angulo) + (zonaJugador.offsetHeight / 2) - 30;
            
//             label.style.left = `${x}px`;
//             label.style.top = `${y}px`;
//             zonaJugador.appendChild(label);
//         });
//     });
// }
