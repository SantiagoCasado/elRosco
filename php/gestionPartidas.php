<?php
// partida_utils.php
include_once("partida.class.php");
session_start();

function cargarPartidaSesion() {
    if (isset($_SESSION['partida'])) {
        $partida = unserialize($_SESSION['partida']);
    
        if ($partida) {
            return $partida;
        }
        echo json_encode(["error" => "No se pudo deserializar la partida"]);
        exit;
    } else {
        // Si la sesión se perdió, recuperar desde la base de datos
        // $idPartida = $_SESSION['idPartida'];
        // $partida = new Partida();
        // $partida = $partida -> cargarPartidaBD($idPartida); 
        echo json_encode(['error' => 'No existe la sesión partida']);
        exit;
    }
}

function generarJSON($partida, $idPregunta = null, $preguntaRespondida = null) {
    $turnoActual = $partida->getTurnoActual();
    $turnoAnterior = $turnoActual == 1 ? 0 : 1; // Se pasa el jugador contrario al jugador actual
    
    // Se pasa el puntaje de ambos jugadores para que no dependa del jugador que tiene el turno
    $puntajeJugador1 = array(
        'idUsuario' => $partida -> getJugadores()[0] -> getID(),
        'puntaje' => $partida -> getPuntajes()[$partida -> getJugadores()[0] -> getID()]
    );
    $puntajeJugador2 = array(
        'idUsuario' => $partida -> getJugadores()[1] -> getID(),
        'puntaje' => $partida -> getPuntajes()[$partida -> getJugadores()[1] -> getID()]
    );
    $puntajes = array(
        'puntajeJugador1' => $puntajeJugador1,
        'puntajeJugador2' => $puntajeJugador2,
    );

    // $tiempoJugador1 = array(
    //     'idUsuario' => $partida -> getJugadores()[0] -> getID(),
    //     'tiempo' => $partida -> getTiemposRestantes()[$partida -> getJugadores()[0] -> getID()]
    // );
    // $tiempoJugador2 = array(
    //     'idUsuario' => $partida -> getJugadores()[1] -> getID(),
    //     'tiempo' => $partida -> getTiemposRestantes()[$partida -> getJugadores()[1] -> getID()]
    // );
    
    // $tiempoRestantes = array(
    //     'tiempoJugador1' => $tiempoJugador1,
    //     'tiempoJugador2' => $tiempoJugador2
    // );

    $enJuego = $partida -> getEnJuego();

    $estadoPartida = array(
        'turnoActual' => $turnoActual,
        'ayudaAdicional' => $partida -> getAyuda(),
        'puntajes' => $puntajes,
        'enJuego' => $enJuego
    );

    // Actualizar el usuario
    $usuario = $partida->getJugadores()[$turnoActual];
    $jugadorActual = array(
        'idUsuario' => $usuario -> getId(),
        'nombreUsuario' => $usuario -> getNombreUsuario(),
        'tiempoRestante' => $partida -> getTiemposRestantes()[$usuario -> getID()]
    );

    $jugadorAnterior = array(
        'idUsuario' => $partida->getJugadores()[$turnoAnterior] -> getID()
    );
    
    // Actualizar la pregunta
    $rosco = $partida->getRoscos()[$usuario -> getId()];
    if ($rosco -> getEstadoRosco() != 'completo') {
        $preguntaNueva = array(
            'idPregunta' => $rosco->getPreguntasPendientes()[0]->getidPregunta(),
            'letra' => $rosco->getPreguntasPendientes()[0]->getLetra(),
            'descripcion' => $rosco->getPreguntasPendientes()[0]->getDescripcion(),
            // Vericar cuantas palabras quedan pendientes para asignar siguiente letra
            'letraSiguiente' => count($rosco->getPreguntasPendientes()) > 1 ? $rosco->getPreguntasPendientes()[1]->getLetra() : $rosco->getPreguntasPendientes()[0]->getLetra()
        );
    } else {
        $preguntaNueva = array(
            'idPregunta' => '',
            'letra' => '',
            'descripcion' => 'El jugador ya respondio todas las preguntas',
            'letraSiguiente' => ''
        );
    }

    if ($partida -> getGanador() != null) {
        // Termino el juego - Obtener el ganador
        $ganador = $partida -> getGanador();
        $ganador = array(
            'idUsuario' => $ganador -> getId(),
            'nombreUsuario' => $ganador -> getNombreUsuario(),
            'puntaje' => $partida -> getPuntajes()[$ganador -> getID()],
            'tiempo' => $partida -> getTIemposRestantes()[$ganador -> getID()]
        );
    } else {
        $ganador = null;    
    }

    if ($idPregunta == null && $preguntaRespondida == null) {
        // PASAPALABRA O FIN DE TIEMPO (SIN RESPUESTA DEL JUGADOR)
        $resultadoJSON = array(
            'estadoPartida' => $estadoPartida,
            'pregunta' => $preguntaNueva,
            'jugadorActual' => $jugadorActual,
            'jugadorAnterior' => $jugadorAnterior,
            'ganador' => $ganador
        );
    } else {
        // RESPUESTA
        // Actualizar la vistsa del rosco
        $respuesta = array(
            'idPregunta' => $idPregunta,
            'estadoRespuesta' => $preguntaRespondida -> getEstadoRespuesta(),
            'palabra' => $preguntaRespondida -> getPalabra()
        );

        $resultadoJSON = array(
            'estadoPartida' => $estadoPartida,
            'respuesta' => $respuesta,
            'pregunta' => $preguntaNueva,
            'jugadorActual' => $jugadorActual,
            'jugadorAnterior' => $jugadorAnterior,
            'ganador' => $ganador
        );
    } 
    return $resultadoJSON;
}
?>
