function crearVistaJuego(partida) {
    console.log(partida);
    crearTablaHistorial(partida.historial, partida.jugadores);

    var enJuego = false;
    jugadorActual = partida.jugadores[partida.turnoActual];
    pregunta = partida.roscos[jugadorActual.idUsuario].preguntasPendientes[0];
    if (partida.roscos[jugadorActual.idUsuario].preguntasPendientes.length > 1) {
        letraSiguiente = partida.roscos[jugadorActual.idUsuario].preguntasPendientes[1].letra;
    } else {
        letraSiguiente = partida.roscos[jugadorActual.idUsuario].preguntasPendientes[0].letra;
    }
    
    if (partida.ganador == null) {
        vistaInteraccion(jugadorActual, pregunta, letraSiguiente, partida.turnoActual, partida.ayuda, jugadorActual.tiempoRestante, enJuego);
    } else {
        mostrarGanador(partida.ganador);
        console.log(partida.ganador);
    }

    crearVistaRoscos(partida.roscos);

    crearBotonAbandonar(jugadorActual.idUsuario);
}

function crearTablaHistorial(historial, jugadores) {
    var tablaHistorial = document.getElementById('idTablaHistorialBody');

    for (i = 0; i < historial.ultimasPartidas.length; i++) {
        var partida = historial.ultimasPartidas[i];
    
        var tr = document.createElement('tr');

        var tdGanador = document.createElement('td');
        if (partida.ganador != null) {
            if (partida.ganador == jugadores[0].idUsuario) {
                tdGanador.innerHTML = jugadores[0].nombreUsuario;
            } else {
                tdGanador.innerHTML = jugadores[1].nombreUsuario;
            }
        } else {
            tdGanador.innerHTML = 'Sin ganador';
        }
        var tdDificultad = document.createElement('td');
        tdDificultad.innerHTML = partida.dificultad;

        var tdTiempoPartida = document.createElement('td');
        tdTiempoPartida.innerHTML = partida.tiempoPartida;

        var tdAyuda = document.createElement('td');
        if (partida.ayuda == 1 ) {
            tdAyuda.innerHTML = 'SI';
        } else {
            tdAyuda.innerHTML = 'NO';
        }

        var tdPuntaje1 = document.createElement('td');
        tdPuntaje1.innerHTML = partida.puntajes[0];
        var tdPuntaje2 = document.createElement('td');
        tdPuntaje2.innerHTML = partida.puntajes[1];

        var tdTiempoRestante1 = document.createElement('td');
        tdTiempoRestante1.innerHTML = partida.tiemposRestantes[0];
        var tdTiempoRestante2 = document.createElement('td');
        tdTiempoRestante2.innerHTML = partida.tiemposRestantes[1];

        tr.appendChild(tdGanador);
        tr.appendChild(tdDificultad);
        tr.appendChild(tdTiempoPartida);
        tr.appendChild(tdAyuda);
        tr.appendChild(tdPuntaje1);
        tr.appendChild(tdTiempoRestante1);
        tr.appendChild(tdPuntaje2);
        tr.appendChild(tdTiempoRestante2);

        tablaHistorial.appendChild(tr);
    }
}

function crearVistaRoscos(roscos) {
    Object.entries(roscos).forEach(([idUsuario, rosco]) => {
        // Crear la tabla para las letras y palabras del rosco
        var zonaJugador = document.getElementById('idLetrasJugador' + idUsuario);
        var tablaRosco = document.createElement('table');
        tablaRosco.className = 'tablaRosco';

        abecedario = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
            'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        // Agregar las filas y las celdas (9x3)
        for (i = 0; i < 9; i++) {
            var fila = document.createElement('tr');
            
            // Cada fila contiene tres letra/palabra
            for (j = 0; j < 3; j++) {
                var celdaLetra = document.createElement('td');
                var celdaPalabra = document.createElement('td');
                celdaLetra.className = 'celdaLetra';
                celdaPalabra.className = 'celdaPalabra';
                
                // Ubicacion en la matriz: numero de fila = i * 3, numero de columna= j
                letra = abecedario[i * 3 + j]; // Obtengo la letra correspondiente
                
                // Se asigna la pregunta correspondiente si existe la letra en Preguntas Pendientes
                // Caso contrario, se adquiero de Preguntas Arriesgadas
                pregunta = rosco.preguntasPendientes.find(pregunta => pregunta.letra == letra);
                preguntaArriesgada = false;
                if (pregunta == null) {
                    pregunta = rosco.preguntasArriesgadas.find(pregunta => pregunta.letra == letra);
                    preguntaArriesgada = true;
                }
                
                if (pregunta) {
                    var label = document.createElement("label");
                    label.id = pregunta.idPregunta;
                    label.className = pregunta.estadoRespuesta;
                    label.innerHTML = pregunta.letra;
                    celdaLetra.appendChild(label);
                    
                    var h3palabra = document.createElement("h3");
                    h3palabra.id = 'palabra' + pregunta.idPregunta;
                    if (preguntaArriesgada) {
                        h3palabra.innerHTML = pregunta.palabra;
                    }
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

function crearBotonAbandonar(idUsuario) {
    var formularioAbandonar = document.getElementById('idAbandonar');
    formularioAbandonar.innerHTML = '';

    var botonAbandonar = document.createElement('button');
    botonAbandonar.type = 'button';
    botonAbandonar.innerHTML = 'Abandonar';
    botonAbandonar.className = 'botonCancelar';
    botonAbandonar.onclick = function () {
        abandonar = true;
        cambiarTurno(idUsuario, abandonar);
    }
    formularioAbandonar.appendChild(botonAbandonar);
}

function vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, tiempoRestante, enJuego) {

    var formularioJuego = document.getElementById('idFormularioJuego');
    formularioJuego.innerHTML = '';

    var turno = document.createElement('div');
    turno.className = 'turnoDe' + turnoActual;

    var h2Turno = document.createElement('h2');
    h2Turno.innerHTML = 'Turno de ' + jugador.nombreUsuario;
    turno.appendChild(h2Turno);
    formularioJuego.appendChild(turno);

    if (enJuego) {
        // VISTA FORMULARIO JUEGO
        
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
        
        if (ayudaAdicional == 1) {
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
            abandonar = false;
            cambiarTurno(jugador.idUsuario, abandonar);
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
            vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, tiempoRestante, enJuego);

            correrTiempo = true;
            controlTemporizador(jugador.idUsuario, tiempoRestante, correrTiempo);
        }
        formularioJuego.appendChild(botonComenzarTurno);
        botonComenzarTurno.focus();
        botonComenzarTurno.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                //event.preventDefault();
                enJuego = true;
                vistaInteraccion(jugador, pregunta, letraSiguiente, turnoActual, ayudaAdicional, tiempoRestante, enJuego);

                correrTiempo = true;
                controlTemporizador(jugador.idUsuario, tiempoRestante, correrTiempo);
            }
        });

        crearBotonAbandonar(jugador.idUsuario);
    } 
}

function cambiarTurno(idUsuario, abandonar) {

    var abandonarParametro;
    if (abandonar) {
        abandonarParametro = 1;
        src = 'audioabandonar';
    } else {
        abandonarParametro = 0;
        src = 'audioincorrecto';
    }

    reproducirAudio(src);
    correrTiempo = false;
    controlTemporizador(idUsuario, null, correrTiempo);

    var tiempoRestante = document.getElementById('tiempoJugador' + idUsuario).innerHTML;

    var parametros = "idUsuario=" + idUsuario
                    + "&tiempoRestante=" + tiempoRestante
                    + "&abandonar=" + abandonarParametro;

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

                // Reproducir audio segun la respuesta
                src = 'audio' + resultado.respuesta.estadoRespuesta;
                reproducirAudio(src);

                // Actualizar vista rosco para el jugador que respondio
                actualizarVistaRosco(resultado.respuesta.idPregunta, resultado.respuesta.estadoRespuesta, resultado.respuesta.palabra);
                
                // Actualizar puntaje de ambos jugadores
                puntajeJugador1 = resultado.estadoPartida.puntajes.puntajeJugador1;
                actualizarPuntaje(puntajeJugador1.idUsuario, puntajeJugador1.puntaje);
                puntajeJugador2 = resultado.estadoPartida.puntajes.puntajeJugador2;
                actualizarPuntaje(puntajeJugador2.idUsuario, puntajeJugador2.puntaje);
                
                // Verificar si hay ganador
                if (resultado.ganador != null) {
                    // Detener temporizador
                    correrTiempo = false;
                    controlTemporizador(resultado.jugadorAnterior.idUsuario, null, correrTiempo);
                    controlTemporizador(resultado.jugadorActual.idUsuario, null, correrTiempo);
                         
                    reproducirAudio('audioganador');
                    
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
    
    if (labelAyuda = document.getElementById('idAyudaAdicional')) {
        // //Actualizar la cantidad de letras de la palabra
        // var labelAyuda = document.getElementById('idAyudaAdicional');
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

                cambiarTurno(idUsuario, false);
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



    var formularioJuego = document.getElementById('idFormularioJuego');
    formularioJuego.innerHTML = '';

    var ganador = document.createElement('div');
    ganador.className = 'ganador';

    var h1ganador = document.createElement('h1');
    h1ganador.innerHTML = "FELICIDADES " + jugador.nombreUsuario + "!";
    ganador.appendChild(h1ganador);

    var h3puntaje = document.createElement('h2');
    h3puntaje.innerHTML = "Ganaste el juego con un puntaje de " + jugador.puntaje;
    ganador.appendChild(h3puntaje);

    formularioJuego.appendChild(ganador);

    var botonJuegoNuevo = document.createElement('button');
    botonJuegoNuevo.id = 'idbotonJuegoNuevo';
    botonJuegoNuevo.type = 'button';
    botonJuegoNuevo.name = 'botonNuevaPartida';
    botonJuegoNuevo.innerHTML = 'Jugar de nuevo';
    botonJuegoNuevo.className = 'botonAceptar';
    botonJuegoNuevo.onclick = function () {
        window.location.href = 'crearPartida.php'
    }

    formularioJuego.appendChild(botonJuegoNuevo);
    botonJuegoNuevo.focus();

    formularioJuego.appendChild(document.createElement('br'));
    formularioJuego.appendChild(document.createElement('br'));
    var botonSalir = document.createElement('button');
    botonSalir.id = 'idBotonSalir';
    botonSalir.type = 'submit';
    botonSalir.method = 'POST';
    botonSalir.name = 'botonSalir';
    botonSalir.innerHTML = 'Salir';
    botonSalir.className = 'botonCancelar';
    // botonSalir.onclick = function () {
    //     window.location.href = 'index.php';
    // }
    formularioJuego.appendChild(botonSalir);
}

function reproducirAudio(srcAudio) {
    var audio = document.getElementById('idAudio');
    if (!audio.paused) {
        // Si se esta reproduciendo pausarlo
        audio.pause();
    }

    // Reiniciarlo
    audio.currentTime = 0;
    var source = document.getElementById(srcAudio);
    audio.src = source.src;
    audio.play();
}