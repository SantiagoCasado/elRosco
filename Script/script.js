function crearVistaJuego(partida) {
    crearVistaRoscos(partida.roscos);

    var enJuego = true;
    jugadorActual = partida.jugadores[partida.turnoActual];
    pregunta = partida.roscos[jugadorActual.idUsuario].preguntasPendientes[0];
    letraSiguiente = partida.roscos[jugadorActual.idUsuario].preguntasPendientes[0].letra
    vistaInteraccion(jugadorActual, pregunta, letraSiguiente, partida.turnoActual, partida.ayuda, enJuego);
}

function crearVistaRoscos(roscos) {
    Object.entries(roscos).forEach(([idUsuario, rosco]) => {
        // Se obtiene la zona del jugador
        var zonaJugador = document.getElementById('idLetrasJugador' + idUsuario);

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

function vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, enJuego) {

    var h2Turno = document.getElementById('idTurnoDe');
    h2Turno.innerHTML = jugador.nombreUsuario;

    if (enJuego) {
        // VISTA JUEGO

        // Iniciar temporizador del jugador
        
        // Mostrar letra y descripcion del rosco
        var formularioJuego = document.getElementById('idFormularioJuego');
        formularioJuego.innerHTML = '';

        // Creo la letra actual
        var h3Letra = document.createElement('h3');
        h3Letra.id = 'idLetra';
        h3Letra.innerHTML = 'Letra ' + pregunta.letra;
        formularioJuego.appendChild(h3Letra);
        formularioJuego.appendChild(document.createElement('br'));
        
        // Creo el
        var labelDescripcion = document.createElement('label');
        labelDescripcion.id = 'idDescripcion'
        labelDescripcion.htmlFor = 'idRespuesta';
        labelDescripcion.className = 'labelFormulario';
        labelDescripcion.innerHTML = pregunta.descripcion;
        formularioJuego.appendChild(labelDescripcion);
        
        if (false) {
        // if (ayudaAdicional) {
            var labelAyuda = document.createElement('label');
            labelAyuda.id = 'idAyudaAdicional';
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
        // Escribir directamente sin seleccionar el input
        respuesta.focus();
        // Evitar envio de formulario (recarga de pagina) al presionar enter
        respuesta.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                juegoRosco(jugador.idUsuario, pregunta.idPregunta);
            }
        });
        formularioJuego.appendChild(document.createElement('br'));
        formularioJuego.appendChild(document.createElement('br'));

        //Crear botones del formulario
        var botonArriesgar = document.createElement('button');
        botonArriesgar.id = 'idBotonArriesgar';
        botonArriesgar.type = 'button';
        botonArriesgar.innerHTML = 'Arriesgar';
        botonArriesgar.className = 'botonJuego' + turnoActual;
        botonArriesgar.onclick = function () {
            enJuego = true;
            //Fijarse los parametros necesarios
            juegoRosco(jugador.idUsuario, pregunta.idPregunta);
        }
        formularioJuego.appendChild(botonArriesgar);
        formularioJuego.appendChild(document.createElement('br'));
        formularioJuego.appendChild(document.createElement('br'));

        var botonPasapalabra = document.createElement('button');
        botonPasapalabra.type = 'button';
        botonPasapalabra.innerHTML = 'Pasapalabra';
        botonPasapalabra.onclick = function () {
            enJuego = false;
            vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, enJuego);
        }
        formularioJuego.appendChild(botonPasapalabra);
        formularioJuego.appendChild(document.createElement('br'));
        formularioJuego.appendChild(document.createElement('br'));

        var strLetra = document.createElement('p');
        strLetra.id = 'idLetraSiguiente';
        strLetra.innerHTML = 'Siguiente letra: ' + letraSiguiente;
        formularioJuego.appendChild(strLetra);

    } else {
        // VISTA CAMBIO TURNO
        
        // Detener temporizador Jugador actual

        // Cambiar turno al siguiente jugador

        // Vista cambio turno
        var strLetra = document.createElement('p');
        strLetra.innerHTML = 'Siguiente letra: ' + pregunta.letra;
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
            vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, enJuego)
        }
        contenidoFormulario.appendChild(botonComenzarTurno);
    }
}

function juegoRosco(idUsuario, idPregunta) {

    // Verificar estado del juego para ver si mostrar cambio de turno o el juego

    // Cambiar estado a enJuego = TRUE
    // Iniciar temporizador
    iniciarTemporizador();


    // enJuego = true

    var respuesta = document.getElementById('idRespuesta').value;

    var parametros = "idUsuario=" + idUsuario
                    + "&idPregunta=" + idPregunta
                    + "&respuesta=" + respuesta //
                    + "&tiempoRestante=" + 0;
    
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

                // Actualizar vista rosco para el jugador
                actualizarVistaRosco(resultado.respuesta.idPregunta, resultado.respuesta.estadoRespuesta);
                
                if (resultado.respuesta.estadoRespuesta == "correcto") {
                    // Actualizar pregunta
                    actualizarPregunta(resultado.jugador.idUsuario, resultado.pregunta, resultado.pregunta.letraSiguiente)

                    // Actualizar puntaje
                    actualizarPuntaje(resultado.jugador.idUsuario, resultado.jugador.puntaje);
    
                } else {
                    // Detener temporizador
                    detenerTemporizador();
                    // Cambiar turno
                    enJuego = false;     
                    vistaInteraccion(resultado.jugador, resultado.pregunta, resultado.pregunta.letraSiguiente, resultado.estadoPartida.turnoActual, resultado.estadoPartida.ayudaAdicional, enJuego);
                }
            } catch (e) {
                console.error("Error al parsear JSON:", e);
            }
        } else {
            // Poner mensaje de error
        }
    }
}

function actualizarVistaRosco(idPregunta, estadoRespuesta) {
    var letra = document.getElementById(idPregunta);
    letra.className = estadoRespuesta;
}

function actualizarPregunta(idUsuario, pregunta, letraSiguiente) {
    // Actualizar letra actual
    var h3Letra = document.getElementById('idLetra');
    h3Letra.innerHTML = 'Letra ' + pregunta.letra;
    
    var labelDescripcion = document.getElementById('idDescripcion');
    labelDescripcion.innerHTML = pregunta.descripcion;
    
    if (false) {
    // if (ayudaAdicional) {
        // Actualizar la cantidad de letras de la palabra
        var labelAyuda = document.getElementById('idAyudaAdicional');
        labelAyuda.innerHTML = '<br>Contiene ' + pregunta.palabra.length + ' letras';
    }
    
    var respuesta = document.getElementById('idRespuesta');
    respuesta.value = ''; 
    respuesta.focus();
    respuesta.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            juegoRosco(idUsuario, pregunta.idPregunta);
        }
    });

    // Actualizar la funcion a llamar
    var botonArriesgar = document.getElementById('idBotonArriesgar');
    botonArriesgar.onclick = function () {
        enJuego = true;
        juegoRosco(idUsuario, pregunta.idPregunta);
    }

    // Actualizar la siguiente letra
    var strLetra = document.getElementById('idLetraSiguiente');
    strLetra.innerHTML = 'Siguiente letra: ' + letraSiguiente;
}

function actualizarPuntaje(idUsuario, puntaje) {
    var puntaje = document.getElementById('puntajeJugador' + idUsuario);
    puntaje.innerHTML = puntaje;
}

function actualizarTemporizador(idUsuario, tiempo) {
    var temporizador = document.getElementById('tiempoJugador' + idUsuario);
    temporizador.innerHTML = tiempo;
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
//         var zonaJugador = document.getElementById('idLetrasJugador' + rosco.idUsuario);
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
