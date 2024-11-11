function crearVistaJuego(partida) {
    crearVistaRoscos(partida.roscos);

    var enJuego = false;
    jugadorActual = partida.jugadores[partida.turnoActual];
    pregunta = partida.roscos[jugadorActual.idUsuario].preguntasPendientes[0];
    letraSiguiente = partida.roscos[jugadorActual.idUsuario].preguntasPendientes[0].letra
    vistaInteraccion(jugadorActual, pregunta, letraSiguiente, partida.turnoActual, partida.ayuda, enJuego);
}

function crearVistaRoscos(roscos) {
    Object.entries(roscos).forEach(([idUsuario, rosco]) => {
        // Crear la tabla para las letras y palabras del rosco
        var zonaJugador = document.getElementById('idLetrasJugador' + idUsuario);
        var tablaRosco = document.createElement('table');
        tablaRosco.className = 'tablaRosco';
        
        // Agregar las filas y las celdas (9x3)
        for (i = 0; i < 9; i++) {
            var fila = document.createElement('tr');
            
            // Cada fila contiene tres letra/palabras
            for (j = 0; j < 3; j++) {
                var celdaLetra = document.createElement('td');
                var celdaPalabra = document.createElement('td');
                celdaLetra.className = 'celdaLetra';
                celdaPalabra.className = 'celdaPalabra';
                
                var pregunta = rosco.preguntasPendientes[i * 3 + j];
                if (pregunta) {
                    var label = document.createElement("label");
                    label.id = pregunta.idPregunta;
                    label.className = pregunta.estadoRespuesta;
                    label.innerHTML = pregunta.letra;
                    celdaLetra.appendChild(label);
                    
                    var h3palabra = document.createElement("h3");
                    h3palabra.id = 'palabra' + pregunta.idPregunta;
                    celdaPalabra.appendChild(h3palabra);
                }
                
                fila.appendChild(celdaLetra);
                fila.appendChild(celdaPalabra);
            }
            
            tablaRosco.appendChild(fila);
        }
        
        zonaJugador.appendChild(tablaRosco);
    });
}


function vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, enJuego) {
    // Poner adentro del formulario?
    var h2Turno = document.getElementById('idTurnoDe');
    h2Turno.innerHTML = jugador.nombreUsuario;

    if (enJuego) {
        // VISTA FORMULARIO JUEGO

        // Iniciar temporizador del jugador
        iniciarTemporizador(jugador.idUsuario);
        
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
            pasapalabra(jugador.idUsuario);
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

function pasapalabra(idUsuario) {
    detenerTemporizador(idUsuario);

    var parametros = "idUsuario=" + idUsuario;
    var peticion = new XMLHttpRequest();
    peticion.open("POST", "php/pasapalabra.php", true); // Relativo a la vista (rosco.php)
    peticion.onreadystatechange = cambiarTurno;
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(parametros);

        function cambiarTurno() {

            if ((peticion.readyState == 4) && (peticion.status==200)) {
                console.log(peticion.responseText);
                resultado = JSON.parse(peticion.responseText);
            
                //enJuego = false;
                vistaInteraccion(resultado.jugador, resultado.pregunta, resultado.pregunta.letraSiguiente, resultado.estadoPartida.turnoActual, resultado.estadoPartida.ayudaAdicional, resultado.estadoPartida.enJuego);
            }
        }
}

function juegoRosco(idUsuario, idPregunta) {

    var respuesta = document.getElementById('idRespuesta').value;

    var parametros = "idUsuario=" + idUsuario
                    + "&idPregunta=" + idPregunta
                    + "&respuesta=" + respuesta
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

                // Actualizar vista rosco para el jugador que respondio
                actualizarVistaRosco(resultado.respuesta.idPregunta, resultado.respuesta.estadoRespuesta, resultado.respuesta.palabra);
                
                // Actualizar puntaje de ambos jugadores
                puntajeJugador1 = resultado.estadoPartida.puntajes.puntajeJugador1;
                actualizarPuntaje(puntajeJugador1.idUsuario, puntajeJugador1.puntaje);
                puntajeJugador2 = resultado.estadoPartida.puntajes.puntajeJugador2;
                actualizarPuntaje(puntajeJugador2.idUsuario, puntajeJugador2.puntaje);
                
                // Verificar si hay ganador
                if (resultado.ganador != null) {
                    mostrarGanador(resultado.ganador);
                } else {
                    // Si la respuesta es correcta y el rosco no esta completo
                    if (resultado.estadoPartida.enJuego) {
                        // Actualizar pregunta
                        actualizarPregunta(resultado.jugador.idUsuario, resultado.pregunta, resultado.pregunta.letraSiguiente)
    
                    } else {
                        // Detener temporizador
                        detenerTemporizador();
                        // Cambiar turno   
                        vistaInteraccion(resultado.jugador, resultado.pregunta, resultado.pregunta.letraSiguiente, resultado.estadoPartida.turnoActual, resultado.estadoPartida.ayudaAdicional, resultado.estadoPartida.enJuego);
                    }
                }
            } catch (e) {
                console.error("Error al parsear JSON:", e);
            }
        } else {
            // Poner mensaje de error
        }
    }
}

function actualizarVistaRosco(idPregunta, estadoRespuesta, palabra) {
    var letra = document.getElementById(idPregunta);
    letra.className = estadoRespuesta;

    var h3palabra = document.getElementById('palabra' + idPregunta);
    h3palabra.innerHTML = palabra;
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
    var textoPuntaje = document.getElementById('puntajeJugador' + idUsuario);
    textoPuntaje.innerHTML = puntaje;
}

function iniciarTemporizador(segundos) {
    //console.log("Iniciar Temporizador");
}

function actualizarTemporizador(idUsuario, tiempo) {
    var temporizador = document.getElementById('tiempoJugador' + idUsuario);
    temporizador.innerHTML = tiempo;
}

function detenerTemporizador(idUsuario) {
    //console.log("Detener Temporizador");
}

function mostrarGanador(jugador) {
    // Esto es temporal hasta acomodar la vista del formulario?
    var h2Turno = document.getElementById('idTurnoDe');
    h2Turno.innerHTML = '';

    var formularioJuego = document.getElementById('idFormularioJuego');
    formularioJuego.innerHTML = '';

    var h1ganador = document.createElement('h1');
    h1ganador.innerHTML = "FELICIDADES " + jugador.nombreUsuario + "!";
    formularioJuego.appendChild(h1ganador);

    var h3puntaje = document.createElement('h3');
    h3puntaje.innerHTML = "Ganaste el juego con un puntaje de X";
    formularioJuego.appendChild(h3puntaje);
}
