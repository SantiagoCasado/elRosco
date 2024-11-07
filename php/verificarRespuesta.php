<?php
include_once("partida.class.php");
session_start();

if (isset($_SESSION['partida'])) {
    $partida = unserialize($_SESSION['partida']);

    // $partidaJSON = array(
    //     'idPartida' => $partida->getIdPartida(), //
    //     'dificultad' => $partida->getDificultad(), //
    //     'tiempoPartida' => $partida->getTiempoPartida(), //
    //     'ayuda' => $partida->getAyuda(), //
    //     'turnoActual' => $partida->getTurnoActual(), //
    //     'jugadores' => array(), //
    //     'roscos' => array() //
    // );
    
    // // Agregar jugadores al JSON
    // foreach ($partida->getJugadores() as $jugador) {
    //     $partidaJSON['jugadores'][] = array(
    //         'id' => $jugador->getID(),
    //         'nombreUsuario' => $jugador->getNombreUsuario(),
    //     );
    // }
    
    // // Agregar roscos al JSON
    // foreach ($partida->getRoscos() as $idJugador => $rosco) {
    //     // Crear un nuevo arreglo temporal para cada rosco
    //     $roscoJSON = array(
    //         'idRosco' => $rosco->getIdRosco(),
    //         'estadoRosco' => $rosco->getEstadoRosco(),
    //         'preguntasPendientes' => array(),
    //         'preguntasArriesgadas' => array()
    //     );
    
    //     // Agregar preguntas pendientes al rosco
    //     foreach ($rosco->getPreguntasPendientes() as $pregunta) {
    //         $roscoJSON['preguntasPendientes'][] = array(
    //             'idPregunta' => $pregunta->getIdPregunta(),
    //             'letra' => $pregunta->getLetra(),
    //             'palabra' => $pregunta->getPalabra(),
    //             'descripcion' => $pregunta->getDescripcion(),
    //             'dificultadPregunta' => $pregunta->getDificultadPregunta(),
    //             'estadoRespuesta' => $pregunta->getEstadoRespuesta()
    //         );
    //     }

    //     // Agregar preguntas arriesgadas al rosco
    //     foreach ($rosco->getPreguntasArriesgadas() as $pregunta) {
    //         $roscoJSON['preguntasArriesgadas'][] = array(
    //             'idPregunta' => $pregunta->getIdPregunta(),
    //             'letra' => $pregunta->getLetra(),
    //             'estadoRespuesta' => $pregunta->getEstadoRespuesta()
    //         );
    //     }

    //     // Asignar el rosco al jugador en partidaJSON
    //     $partidaJSON['roscos'][$idJugador] = $roscoJSON;
    // }
    // echo json_encode(["partida" => $partidaJSON]);
    // exit;

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
    $idPartida = $_POST['idPartida']; // ??? Puede servir para cargar la aprtida de la bd pero la estoy almacenando en sesion
    $idUsuario = $_POST['idUsuario'];
    $idPregunta = $_POST['idPregunta'];
    $tiempoRestante = $_POST['tiempoRestante'];
    $respuesta = $_POST['respuesta']; 

    $estadoRespuesta = $partida -> verificarRespuesta($idUsuario, $idPregunta, $respuesta, $tiempoRestante);
    
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

        $pregunta = array(
            'idPregunta' => $idPregunta,
            'estadoRespuesta' => $estadoRespuesta,
            'letraPendiente' => $partida -> getRoscos()[$idUsuario] -> getPreguntasPendientes()[0] -> getLetra(),
            'letraSiguiente' => $partida -> getRoscos()[$idUsuario] -> getPreguntasPendientes()[1] -> getLetra()

        );

        $jugador = array(
            'idUsuario' => $idUsuario,
            'puntaje' => $partida->getPuntajes()[$idUsuario]
        );

        $resultadoJSON = array(
            'pregunta' => $pregunta,
            'jugador' => $jugador
        );
    } 
    echo json_encode($resultadoJSON);
}
?>