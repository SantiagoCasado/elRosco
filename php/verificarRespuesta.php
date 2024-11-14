<?php
include_once("gestionPartidas.php");

$partida = cargarPartidaSesion();

if (isset($_POST['respuesta'])) {
    $idUsuario = $_POST['idUsuario'];
    $idPregunta = $_POST['idPregunta'];
    $tiempoRestante = $_POST['tiempoRestante'];
    $respuesta = htmlspecialchars(trim($_POST['respuesta']));

    // Obtengo el estado de respuestas, actualizo el objeto Partida y guardo en sesion
    $preguntaRespondida = $partida->verificarRespuesta($idUsuario, $idPregunta, $respuesta); //FIJARSE DE SACAR O NO EL IDPREGUNTA
    $partida -> actualizarEstadoJuego($idUsuario,
                                        $partida -> getRoscos()[$idUsuario] -> getEstadoRosco(),
                                        $preguntaRespondida -> getEstadoRespuesta(),
                                        $tiempoRestante,
                                        false); // abandonar = false
    $_SESSION['partida'] = serialize($partida);

    if (is_null($preguntaRespondida) || empty($preguntaRespondida)) {
        // Sin respuesta del servidor
        $resultadoJSON = array('error' => 'Sin respuesta del servidor');
    } else {
        $resultadoJSON = generarJSON($partida, $idPregunta, $preguntaRespondida);
    }
    echo json_encode($resultadoJSON);
}
?>
