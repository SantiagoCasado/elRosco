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

if (isset($_POST['respuesta'])) {
    $idUsuario = $_POST['idUsuario'];
    $idPregunta = $_POST['idPregunta'];
    $tiempoRestante = $_POST['tiempoRestante'];
    $respuesta = $_POST['respuesta'];

    // Obtengo el estado de respuestas, actualizo el objeto Partida y guardo en sesion
    $preguntaRespondida = $partida->verificarRespuesta($idUsuario, $idPregunta, $respuesta, $tiempoRestante); //FIJARSE DE SACAR O NO EL IDPARTIDA
    $_SESSION['partida'] = serialize($partida);

    if (is_null($preguntaRespondida) || empty($preguntaRespondida)) {
        // Sin respuesta del servidor
        $resultadoJSON = array('error' => 'Sin respuesta del servidor');
    } else {

        $turnoActual = $partida->getTurnoActual();

        $estadoPartida = array(
            'turnoActual' => $turnoActual,
            'ayudaAdicional' => $partida -> getAyuda()
        );

        // Actualizar la vistsa del rosco
        $respuesta = array(
            'idPregunta' => $idPregunta,
            'estadoRespuesta' => $preguntaRespondida -> getEstadoRespuesta(),
            'palabra' => $preguntaRespondida -> getPalabra()
        );

        // Actualizar el usuario
        $usuario = $partida -> getJugadores()[$turnoActual];

        $jugador = array(
            'idUsuario' => $usuario -> getId(),
            'nombreUsuario' => $usuario -> getNombreUsuario(),
            'puntaje' => $partida->getPuntajes()[$usuario -> getId()]
        );

        // Actualizar la pregunta
        $preguntaNueva = array(
            'idPregunta' => $partida->getRoscos()[$usuario -> getId()]->getPreguntasPendientes()[0]->getidPregunta(),
            'letra' => $partida->getRoscos()[$usuario -> getId()]->getPreguntasPendientes()[0]->getLetra(),
            'descripcion' => $partida->getRoscos()[$usuario -> getId()]->getPreguntasPendientes()[0]->getDescripcion(),
            'letraSiguiente' => $partida->getRoscos()[$usuario -> getId()]->getPreguntasPendientes()[1]->getLetra()
        );

        $resultadoJSON = array(
            'estadoPartida' => $estadoPartida,
            'respuesta' => $respuesta,
            'pregunta' => $preguntaNueva,
            'jugador' => $jugador
        );

    }
    echo json_encode($resultadoJSON);
}
