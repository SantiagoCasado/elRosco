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

    $estadoRespuesta = $partida->verificarRespuesta($idUsuario, $idPregunta, $respuesta, $tiempoRestante);
    $_SESSION['partida'] = $partida;

    if (is_null($estadoRespuesta) || empty($estadoRespuesta)) {
        // Sin respuesta del servidor
        $resultadoJSON = array('error' => 'Sin respuesta del servidor');
    } else {
        // Datos necesarios para actualizar la vista del juego:

        // idPregunta y estadoRespuesta para el label de la letra

        // SI LA RESPUESTA ES CORRECTA
        // Nueva letra, palabra a responder, decripcion y letra siguiente
        // idUsuario y puntaje del usuario

        // SI LA RESPUESTA ES INCORRECTA

        $respuesta = array(
            'idPregunta' => $idPregunta, // Si o si
            'estadoRespuesta' => $estadoRespuesta, // si o si 
        );

        $jugador = array(
            'idUsuario' => $idUsuario,
            'nombreUsuario' => $partida -> getJugadores()[$partida -> getTurnoActual()] -> getNombreUsuario(),
            'puntaje' => $partida->getPuntajes()[$idUsuario]
        );

        
        if ($estadoRespuesta == 'correcto') {
            $preguntaNueva = array(
                'idPregunta' => $partida->getRoscos()[$idUsuario]->getPreguntasPendientes()[0]->getidPregunta(),
                'letra' => $partida->getRoscos()[$idUsuario]->getPreguntasPendientes()[0]->getLetra(),
                'descripcion' => $partida->getRoscos()[$idUsuario]->getPreguntasPendientes()[0]->getDescripcion(),
            );

            $pregunta = array(
                'preguntaNueva' => $preguntaNueva,
                'letraSiguiente' => $partida->getRoscos()[$idUsuario]->getPreguntasPendientes()[1]->getLetra()
            );
            $resultadoJSON = array(
                'respuesta' => $respuesta,
                'pregunta' => $pregunta,
                'jugador' => $jugador
            );
        } else {
            $resultadoJSON = array(
                'respuesta' => $respuesta,
                'jugador' => $jugador
            );
        }
    }
    echo json_encode($resultadoJSON);
}
