<?php
include_once("gestionPartidas.php");

$partida = cargarPartidaSesion();

if (isset($_POST['idUsuario'], $_POST['idUsuario'])) {
    $idUsuario = $_POST['idUsuario'];
    $tiempoRestante = $_POST['tiempoRestante'];
    
    // Actualizar el estado de la partida - Cambiar turno y arreglo de las preguntas
    $partida -> pasapalabra($idUsuario, $tiempoRestante);
    $_SESSION['partida'] = serialize($partida);
    
    if (is_null($partida) || empty($partida)) {
        $resultadoJSON = array('error' => 'Sin respuesta del servidor');
    } else {
        $resultadoJSON = generarJSON($partida);
    }
    echo json_encode($resultadoJSON);
}