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
    $estadoPartida = array(
        'turnoActual' => $turnoActual,
        'ayudaAdicional' => $partida -> getAyuda()
    );

    // Actualizar el usuario
    $usuario = $partida->getJugadores()[$turnoActual];
    $jugador = array(
        'idUsuario' => $usuario -> getId(),
        'nombreUsuario' => $usuario -> getNombreUsuario(),
        'puntaje' => $partida->getPuntajes()[$usuario -> getId()]
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

    if ($idPregunta == null && $preguntaRespondida == null) {
        // Pasapalabra
        $resultadoJSON = array(
            'estadoPartida' => $estadoPartida,
            'pregunta' => $preguntaNueva,
            'jugador' => $jugador
        );
    } else {
        // Respuesta
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
            'jugador' => $jugador
        );
    }

    return $resultadoJSON;
}
?>
