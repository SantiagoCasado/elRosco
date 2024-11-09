<?php
include_once("php/partida.class.php");
session_start();

if (isset($_POST['botonComenzarPartida'])) {

    //Obtener caracteristicas de la partida
    $dificultad = $_POST['radioNivelPartida'];
    $tiempoPartida = $_POST['radioDuracionPartida'];
    if(isset($_POST['checkboxAyuda'])) {
        $ayudaAdicional = 1;
    } else {
        $ayudaAdicional = 0;
    }

    
    if (!isset($_SESSION['vectorSesion']) || count($_SESSION['vectorSesion']) < 2) {
        //Redirigir si no hay dos sesiones iniciadas
        $mensaje = 'Deben haber dos usuarios con sesion iniciada';
        $_SESSION['mensaje'] = $mensaje;
        header("location:index.php");
        exit;
    } else {
        //Obtener los jugadores
        $jugadores = $_SESSION['vectorSesion'];
    }

    unset($_SESSION['partida']);
    if (!isset($_SESSION['partida'])) {
        //Instanciar la partida
        $partida = new Partida();
        $partida -> iniciarNuevaPartida($dificultad, $tiempoPartida, $ayudaAdicional, $jugadores);

        // Guardar partida en sesion
        $_SESSION['partida'] = serialize($partida);

        // Guardar la partida y el idPartida generado en la base de datos y en sesion respectivamente
        //$idPartida = $partida -> guardarPartidaBD();
        //$_SESSION['idPartida'] = $idPartida;
    }


    //Agragar un if por si la partida no se puedo crear
    
    //Preparar los objetos JSON para el juego
    $partidaJSON = array(
        'idPartida' => $partida->getIdPartida(), //
        'dificultad' => $partida->getDificultad(), //
        'tiempoPartida' => $partida->getTiempoPartida(), //
        'ayuda' => $partida->getAyuda(), //
        'turnoActual' => $partida->getTurnoActual(), //
        'jugadores' => array(), //
        'roscos' => array() //
    );
    
    // Agregar jugadores al JSON
    foreach ($partida->getJugadores() as $jugador) {
        $partidaJSON['jugadores'][] = array(
            'idUsuario' => $jugador->getID(), //
            'nombreUsuario' => $jugador->getNombreUsuario(), //
        );
    }
    
    // Agregar roscos al JSON
    foreach ($partida->getRoscos() as $idJugador => $rosco) {
        // Crear un nuevo arreglo temporal para cada rosco
        $roscoJSON = array(
            'idRosco' => $rosco->getIdRosco(),
            'estadoRosco' => $rosco->getEstadoRosco(), //
            'preguntasPendientes' => array(),
            'preguntasArriesgadas' => array()
        );
    
        // Agregar preguntas pendientes al rosco
        foreach ($rosco->getPreguntasPendientes() as $pregunta) {
            $roscoJSON['preguntasPendientes'][] = array(
                'idPregunta' => $pregunta->getIdPregunta(), //
                'letra' => $pregunta->getLetra(), //
                'palabra' => $pregunta->getPalabra(), //
                'descripcion' => $pregunta->getDescripcion(), //
                'dificultadPregunta' => $pregunta->getDificultadPregunta(),
                'estadoRespuesta' => $pregunta->getEstadoRespuesta() //
            );
        }

        // // Agregar preguntas arriesgadas al rosco
        // foreach ($rosco->getPreguntasArriesgadas() as $pregunta) {
        //     $roscoJSON['preguntasArriesgadas'][] = array(
        //         'idPregunta' => $pregunta->getIdPregunta(),
        //         'letra' => $pregunta->getLetra(),
        //         'estadoRespuesta' => $pregunta->getEstadoRespuesta()
        //     );
        // }

        // Asignar el rosco al jugador en partidaJSON
        $partidaJSON['roscos'][$idJugador] = $roscoJSON;
    }
}
?>