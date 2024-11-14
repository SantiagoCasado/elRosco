<?php
include_once("gestionPartidas.php");

$partida = cargarPartidaSesion();

if (isset($_POST['idUsuario'], $_POST['idUsuario'])) {
    $idUsuario = $_POST['idUsuario'];
    $tiempoRestante = $_POST['tiempoRestante'];
    $abandonar = $_POST['abandonar'];
    
    if ($tiempoRestante > 0) {
        // El jugador paso palabra - sigue en juego
        //Cambiar turno y arreglo de las preguntas
        $partida -> pasapalabra($idUsuario, $tiempoRestante);
    }

    $partida -> actualizarEstadoJuego($idUsuario,
                                        null,
                                        null,
                                        $tiempoRestante,
                                        $abandonar); // no hay respuesta ni estado rosco (null)
    $_SESSION['partida'] = serialize($partida);
    
    if (is_null($partida) || empty($partida)) {
        $resultadoJSON = array('error' => 'Sin respuesta del servidor');
    } else {
        $resultadoJSON = generarJSON($partida);
    }
    echo json_encode($resultadoJSON);
}