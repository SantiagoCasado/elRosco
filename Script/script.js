function crearVistaJuego(partida) {
    crearVistaRoscos(partida.roscos);

    var enJuego = true;
    vistaInteraccion(partida.idPartida, partida.jugadores[partida.turnoActual], partida.roscos[partida.jugadores[partida.turnoActual].id], partida.turnoActual, enJuego);
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

function vistaInteraccion(idPartida, jugador, rosco, turnoActual, enJuego) {

    var h2Turno = document.getElementById('idTurnoDe');
    h2Turno.innerHTML = jugador.nombreUsuario;

    if (enJuego) {
        // VISTA JUEGO
        
        // Iniciar temporizador del jugador
        
        // Mostrar letra y descripcion del rosco
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

        //Crear botones del formulario
        var botonArriesgar = document.createElement('button');
        botonArriesgar.type = 'button';
        botonArriesgar.innerHTML = 'Arriesgar';
        botonArriesgar.className = 'botonJuego' + turnoActual;
        botonArriesgar.onclick = function () {
            enJuego = true;
            //Fijarse los parametros necesarios
            juegoRosco(idPartida, jugador.id, pregunta.idPregunta);
        }
        formularioJuego.appendChild(botonArriesgar);
        formularioJuego.appendChild(document.createElement('br'));
        formularioJuego.appendChild(document.createElement('br'));

        var botonPasapalabra = document.createElement('button');
        botonPasapalabra.type = 'button';
        botonPasapalabra.innerHTML = 'Pasapalabra';
        botonPasapalabra.onclick = function () {
            enJuego = false;
            vistaInteraccion(jugador, rosco, turnoActual, enJuego);
        }
        formularioJuego.appendChild(botonPasapalabra);
        formularioJuego.appendChild(document.createElement('br'));
        formularioJuego.appendChild(document.createElement('br'));

        var strLetra = document.createElement('p');
        strLetra.innerHTML = 'Siguiente letra: ' + rosco.preguntasPendientes[1].letra;
        formularioJuego.appendChild(strLetra);

    } else {
        // VISTA CAMBIO TURNO
        
        // Detener temporizador Jugador actual

        // Cambiar turno al siguiente jugador

        // Vista cambio turno
        var strLetra = document.createElement('p');
        strLetra.innerHTML = 'Siguiente letra: ' + rosco.preguntasPendientes[0].letra;
        h2Turno.appendChild(document.createElement('br'));
        h2Turno.appendChild(strLetra);

        var contenidoFormulario = document.getElementById('idFormularioJuego');
        contenidoFormulario.innerHTML = '';
        contenidoFormulario.method = 'GET';

        var botonComenzarTurno = document.createElement('button');
        botonComenzarTurno.innerHTML = 'Comenzar Turno';
        botonComenzarTurno.className = 'botonJuego' + turnoActual;
        botonComenzarTurno.onclick = function () {
            enJuego = true;
            vistaInteraccion(jugador, rosco, turnoActual, enJuego);
        }
        contenidoFormulario.appendChild(botonComenzarTurno);
    }
}

function juegoRosco(idPartida, idUsuario, idPregunta) {

    // Verificar estado del juego para ver si mostrar cambio de turno o el juego

    // Cambiar estado a enJuego = TRUE
    // Iniciar temporizador
    iniciarTemporizador();


    // enJuego = true

    var respuesta = document.getElementById('idRespuesta').value;
    // Ver que parametros son necesarios
    var parametros = "idPartida=" + idPartida //???
                    + "&idUsuario=" + idUsuario //
                    + "&idPregunta=" + idPregunta //
                    + "&respuesta=" + respuesta //
                    + "&tiempoRestante=" + 0; //
    
    var peticion = new XMLHttpRequest();
    peticion.open("POST", "php/verificarRespuesta.php", true); // Relativo a la vista (rosco.php)
    peticion.onreadystatechange = mostrarResultado;
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(parametros);

    function mostrarResultado() {

        if ((peticion.readyState == 4) && (peticion.status==200)) {
            console.log('resultado: ' + peticion.responseText);
            try {
                var resultado = JSON.parse(peticion.responseText);
                // Obtener el label de la letra correspondiente y actualizo su estado
                var letra = document.getElementById(resultado.pregunta.idPregunta);
                letra.className = resultado.pregunta.estadoRespuesta;
                
                if (resultado.pregunta.estadoRespuesta == "correcto") {
                    // Actualizar puntaje
                    var puntaje = document.getElementById('puntaje' + resultado.jugador.idUsuario);
                    puntaje.innerHTML = resultado.jugador.puntaje;
    
                } else {
                    // Detener temporizador
                    detenerTemporizador();
                    // Cambiar turno                
                }
            } catch (e) {
                console.error("Error al parsear JSON:", e);
            }
        } else {
            // Poner mensaje de error
        }
    }
}

function iniciarTemporizador(segundos) {
    //console.log("Iniciar Temporizador");
}

function detenerTemporizador() {
    //console.log("Detener Temporizador");
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
