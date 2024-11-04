<?php
if (isset($_POST['botonComenzarPartida'])) {

    //Obtener caracteristicas de la partida
    $dificultad = $_POST['radioNivelPartida'];
    $tiempoPartida = $_POST['radioDuracionPartida'];
    if(isset($_POST['checkboxAyuda'])) {
        $ayudaAdicional = 1;
    } else {
        $ayudaAdicional = 0;
    }

    //Obtener los jugadores
    $jugadores = $_SESSION['vectorSesion'];

    //Instanciar la partida
    $partida = new Partida($dificultad, $tiempoPartida, $ayudaAdicional, $jugadores);

    // Guardar la partida en la base de datos
    //$partida -> guardarPartidaBD();

    //Preparar los objetos JSON para el juego
    $partidaJSON = array(
        'idPartida' => $partida->getIdPartida(),
        'dificultad' => $partida->getDificultad(),
        'tiempoPartida' => $partida->getTiempoPartida(),
        'ayuda' => $partida->getAyuda(),
        'turnoActual' => $partida->getTurnoActual(),
        'jugadores' => array(),
        'roscos' => array()
    );
    
    // Agregar jugadores al JSON
    foreach ($partida->getJugadores() as $jugador) {
        $partidaJSON['jugadores'][] = array(
            'id' => $jugador->getID(),
            'nombreUsuario' => $jugador->getNombreUsuario(),
        );
    }
    
    // Agregar roscos al JSON
    foreach ($partida->getRoscos() as $idJugador => $rosco) {
        // Crear un nuevo arreglo temporal para cada rosco
        $roscoJSON = array(
            'idRosco' => $rosco->getIdRosco(),
            'estadoRosco' => $rosco->getEstadoRosco(),
            'preguntasPendientes' => array(),
            'preguntasArriesgadas' => array()
        );
    
        // Agregar preguntas pendientes al rosco
        foreach ($rosco->getPreguntasPendientes() as $pregunta) {
            $roscoJSON['preguntasPendientes'][] = array(
                'idPregunta' => $pregunta->getIdPregunta(),
                'letra' => $pregunta->getLetra(),
                'palabra' => $pregunta->getPalabra(),
                'descripcion' => $pregunta->getDescripcion(),
                'dificultadPregunta' => $pregunta->getDificultadPregunta(),
                'estadoRespuesta' => $pregunta->getEstadoRespuesta()
            );
        }

        // Agregar preguntas arriesgadas al rosco
        foreach ($rosco->getPreguntasArriesgadas() as $pregunta) {
            $roscoJSON['preguntasArriesgadas'][] = array(
                'idPregunta' => $pregunta->getIdPregunta(),
                'letra' => $pregunta->getLetra(),
                'estadoRespuesta' => $pregunta->getEstadoRespuesta()
            );
        }

        // Asignar el rosco al jugador en partidaJSON
        $partidaJSON['roscos'][$idJugador] = $roscoJSON;
    }
    

    // // Preparar los roscos a objetos JSON
    // $roscosJSON = array();
    // foreach ($jugadores as $jugador) {
    //     $rosco = $partida->getRoscos()[$jugador->getID()];

    //     $preguntasPendientes = array();
    //     foreach ($rosco -> getPreguntasPendientes() as $pregunta) {
    //         $arregloTemporal = array(
    //             'idPregunta' => $pregunta->getIdPregunta(), // id del Label
    //             'letra' => $pregunta->getLetra(), // valor del Label
    //             'estadoRespuesta' => $pregunta->getEstadoRespuesta() // clase del Label
    //         );
    //         $preguntasPendientes[] = $arregloTemporal;
    //     }

    //     $preguntasArriesgadas = array(
    //         'idPregunta' => 0,
    //         'letra' => '',
    //         'estadoRespuesta');

    // $roscosJSON[] = array(
    //     'idJugador' => $jugador->getID(),
    //     'preguntasPendientes' => $preguntasPendientes,
    //     'preguntasArriesgadas' => $preguntasArriesgadas
    // );
    // }

    // $roscosDatos = json_encode($partidaJSON);

}
?>