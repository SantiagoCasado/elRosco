function crearVistaJuego(partida) {
    crearVistaRoscos(partida.roscos);

    var enJuego = false;
    jugadorActual = partida.jugadores[partida.turnoActual];
    pregunta = partida.roscos[jugadorActual.idUsuario].preguntasPendientes[0];
    letraSiguiente = partida.roscos[jugadorActual.idUsuario].preguntasPendientes[0].letra
    vistaInteraccion(jugadorActual, pregunta, letraSiguiente, partida.turnoActual, partida.ayuda, partida.tiempoPartida, enJuego);
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

function vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, tiempoRestante, enJuego) {

    var formularioJuego = document.getElementById('idFormularioJuego');
    formularioJuego.innerHTML = '';

    var h2Turno = document.createElement('h2');
    h2Turno.id = 'idTurnoDe'
    h2Turno.innerHTML = 'Turno de ' + jugador.nombreUsuario;
    h2Turno.className = 'turnoDe' + turnoActual;
    formularioJuego.appendChild(h2Turno);

    if (enJuego) {
        // VISTA FORMULARIO JUEGO

        // Iniciar temporizador del jugador
        correrTiempo = true;
        controlTemporizador(jugador.idUsuario, tiempoRestante, correrTiempo);
        
        // Mostrar letra y descripcion del rosco

        // Creo la letra actual
        var h3Letra = document.createElement('h3');
        h3Letra.id = 'idLetra';
        h3Letra.innerHTML = 'Letra ' + pregunta.letra;
        formularioJuego.appendChild(h3Letra);
        
        // Creo el label con la descripcion
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

        var respuesta = document.createElement('input');
        respuesta.type = 'text'; 
        respuesta.id = 'idRespuesta';
        respuesta.name = 'respuesta';
        respuesta.setAttribute('required', 'true');
        formularioJuego.appendChild(respuesta);
        // Escribir directamente sin seleccionar el input
        respuesta.focus();
        // Llamar evento con la tecla enter - Evitar recargar pagina
        respuesta.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                //enJuego = true;
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
        botonPasapalabra.className = 'botonPasapalabra';
        botonPasapalabra.onclick = function () {
            enJuego = false;
            cambiarTurno(jugador.idUsuario);
        }
        formularioJuego.appendChild(botonPasapalabra);
        formularioJuego.appendChild(document.createElement('br'));

        var strLetra = document.createElement('p');
        strLetra.id = 'idLetraSiguiente';
        strLetra.innerHTML = 'Siguiente letra: ' + letraSiguiente;
        formularioJuego.appendChild(strLetra);

    } else {
        // VISTA CAMBIO TURNO

        var strLetra = document.createElement('p');
        strLetra.innerHTML = 'Siguiente letra: ' + pregunta.letra;
        formularioJuego.appendChild(strLetra);

        var botonComenzarTurno = document.createElement('button');
        botonComenzarTurno.innerHTML = 'Comenzar Turno';
        botonComenzarTurno.className = 'botonJuego' + turnoActual;
        botonComenzarTurno.onclick = function () {
            enJuego = true;
            vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, tiempoRestante, enJuego)
        }
        formularioJuego.appendChild(botonComenzarTurno);
        botonComenzarTurno.focus();
        botonComenzarTurno.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                //event.preventDefault();
                enJuego = true;
                vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, tiempoRestante, enJuego)
            }
        });
    }
}

function cambiarTurno(idUsuario) {
    // Detener temporizador
    correrTiempo = false;
    controlTemporizador(idUsuario, null, correrTiempo);

    var tiempoRestante = document.getElementById('tiempoJugador' + idUsuario).innerHTML;

    var parametros = "idUsuario=" + idUsuario
                    + "&tiempoRestante=" + tiempoRestante;

    var peticion = new XMLHttpRequest();
    peticion.open("POST", "php/pasapalabra.php", true); // Relativo a la vista (rosco.php)
    peticion.onreadystatechange = respuestaCambioTurno;
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(parametros);

        function respuestaCambioTurno() {

            if ((peticion.readyState == 4) && (peticion.status==200)) {
                console.log(peticion.responseText);
                resultado = JSON.parse(peticion.responseText);

                // Verificar si hay ganador
                if (resultado.ganador != null) {
                    mostrarGanador(resultado.ganador);
                } else {
                    vistaInteraccion(resultado.jugadorActual,
                        resultado.pregunta, 
                        resultado.pregunta.letraSiguiente, 
                        resultado.estadoPartida.turnoActual, 
                        resultado.estadoPartida.ayudaAdicional, 
                        resultado.jugadorActual.tiempoRestante,
                        resultado.estadoPartida.enJuego);
                }
            }
        }
}

function juegoRosco(idUsuario, idPregunta) {

    var respuesta = document.getElementById('idRespuesta').value;
    var tiempoRestante = document.getElementById('tiempoJugador' + idUsuario).innerHTML;

    var parametros = "idUsuario=" + idUsuario
                    + "&idPregunta=" + idPregunta
                    + "&respuesta=" + respuesta
                    + "&tiempoRestante=" + tiempoRestante;
    
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
                    if (resultado.estadoPartida.enJuego) {
                        // Si la respuesta es correcta y el rosco no esta completo
                        
                        //Actualizar pregunta
                        actualizarPregunta(resultado.jugadorActual.idUsuario, resultado.pregunta, resultado.pregunta.letraSiguiente)
    
                    } else {
                        // Respuesta incorrecta

                        // Detener temporizador
                        correrTiempo = false;
                        controlTemporizador(resultado.jugadorAnterior.idUsuario, null, correrTiempo);
                        // Cambiar turno   
                        vistaInteraccion(resultado.jugadorActual, 
                                        resultado.pregunta, 
                                        resultado.pregunta.letraSiguiente, 
                                        resultado.estadoPartida.turnoActual, 
                                        resultado.estadoPartida.ayudaAdicional, 
                                        resultado.jugadorActual.tiempoRestante, 
                                        resultado.estadoPartida.enJuego);
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
    

    // Actualizar los parametros de la funcion a llamar en el boton Arriesgar
    var botonArriesgar = document.getElementById('idBotonArriesgar');
    botonArriesgar.onclick = function () {
        enJuego = true;
        juegoRosco(idUsuario, pregunta.idPregunta);
    }

    // Eliminar el input anterior para actualizar correctamente el idPregunta
    var formularioJuego = document.getElementById('idFormularioJuego');
    formularioJuego.removeChild(document.getElementById('idRespuesta'));

    // Crear un nuevo input
    var respuesta = document.createElement('input');
    respuesta.type = 'text'; 
    respuesta.id = 'idRespuesta';
    respuesta.name = 'respuesta';
    respuesta.setAttribute('required', 'true');
    formularioJuego.insertBefore(respuesta, botonArriesgar);
    respuesta.focus();
    respuesta.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            //enJuego = true;
            juegoRosco(idUsuario, pregunta.idPregunta);
        }
    });    

    // Actualizar la siguiente letra
    var strLetra = document.getElementById('idLetraSiguiente');
    strLetra.innerHTML = 'Siguiente letra: ' + letraSiguiente;
}

function actualizarPuntaje(idUsuario, puntaje) {
    var textoPuntaje = document.getElementById('puntajeJugador' + idUsuario);
    textoPuntaje.innerHTML = puntaje;
}

function controlTemporizador(idUsuario, segundos, correrTiempo) {   
    // Se obtiene el elemento que muestra el tiempo del jugador
    var temporizadorVista = document.getElementById('tiempoJugador' + idUsuario);

    if (correrTiempo) {
        

        tiempo = parseInt(segundos);
        // Se repite la funcion cada un segundo
        temporizador = setInterval (function () {
            actualizarVistaTemporizador(temporizadorVista, tiempo);

            if (--tiempo < 0) {
                // Termino el juego del jugador
                temporizadorVista.innerHTML = 0;
                clearInterval(temporizador);

                cambiarTurno(idUsuario);
            } 
        }, 1000);
        temporizadorVista.dataset.temporizador = temporizador;
    } else {
        clearInterval(temporizadorVista.dataset.temporizador);
    }
}

function actualizarVistaTemporizador(temporizador, tiempo) {
    temporizador.innerHTML = tiempo;
}

function mostrarGanador(jugador) {
    var h2Turno = document.getElementById('idTurnoDe');
    h2Turno.innerHTML = '';

    var formularioJuego = document.getElementById('idFormularioJuego');
    formularioJuego.innerHTML = '';

    var h1ganador = document.createElement('h1');
    h1ganador.innerHTML = "FELICIDADES " + jugador.nombreUsuario + "!";
    formularioJuego.appendChild(h1ganador);

    var h3puntaje = document.createElement('h3');
    h3puntaje.innerHTML = "Ganaste el juego con un puntaje de " + jugador.puntaje;
    formularioJuego.appendChild(h3puntaje);

    var botonJuegoNuevo = document.createElement('button');
    botonJuegoNuevo.id = 'idBotonResultadoPartida';
    botonJuegoNuevo.type = 'button';
    botonJuegoNuevo.innerHTML = 'Jugar de nuevo';
    botonJuegoNuevo.className = 'botonAceptar';
    botonJuegoNuevo.onclick = function () {
        window.location.href = 'crearPartida.php'
    }

    formularioJuego.appendChild(botonJuegoNuevo);
}
