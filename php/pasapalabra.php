<?php
include_once("partida.class.php");
session_start();

if (isset($_SESSION['partida'])) {
    $partida = unserialize($_SESSION['partida']);

    if (!$partida) {
        echo json_encode(["error" => "No se pudo deserializar la partida."]);
        exit;
    }
} else {
    // Si la sesión se perdió, recuperar desde la base de datos
    // $idPartida = $_SESSION['idPartida'];
    // $partida = new Partida();
    // $partida = $partida -> cargarPartidaBD($idPartida); 
    echo json_encode(['error' => 'No existe la sesión partida']);
    exit;
}

if (isset($_POST['idUsuario'])) {
    $idUsuario = $_POST['idUsuario'];
    
    // Actualizar el estado de la partida - Cambiar turno y arreglo de las preguntas
    $partida -> pasapalabra($idUsuario);
    $_SESSION['partida'] = serialize($partida);
    
    if (is_null($partida) || empty($partida)) {
        $resultadoJSON = array('error' => 'Sin respuesta del servidor');
    } else {
        $turnoActual = $partida->getTurnoActual();
        $estadoPartida = array(
            'turnoActual' => $turnoActual,
            'ayudaAdicional' => $partida -> getAyuda()
        );
        
        // Actualizar el usuario
        $usuario = $partida -> getJugadores()[$turnoActual];
        
        $jugador = array(
            'idUsuario' => $usuario -> getId(),
            'nombreUsuario' => $usuario -> getNombreUsuario(),
        );
        
        // Proxima pregunta
        $pregunta = array(
            'idPregunta' => $partida->getRoscos()[$usuario -> getId()]->getPreguntasPendientes()[0]->getidPregunta(),
            'letra' => $partida->getRoscos()[$usuario -> getId()]->getPreguntasPendientes()[0]->getLetra(),
            'descripcion' => $partida->getRoscos()[$usuario -> getId()]->getPreguntasPendientes()[0]->getDescripcion(),
            'letraSiguiente' => $partida->getRoscos()[$usuario -> getId()]->getPreguntasPendientes()[1]->getLetra()
        );
        
        $resultadoJSON = array(
            'estadoPartida' => $estadoPartida,
            'pregunta' => $pregunta,
            'jugador' => $jugador
        );
    }
    echo json_encode($resultadoJSON);
}