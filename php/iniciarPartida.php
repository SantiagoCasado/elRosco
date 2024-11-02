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

    //Preparar los objetos JSON para generar la vista de los roscos
    $roscosJSON = array();
    foreach ($jugadores as $jugador) {
        $rosco = $partida->getRoscos()[$jugador->getID()];

        $preguntasPendientes = array();
        foreach ($rosco -> getPreguntasPendientes() as $pregunta) {
            $arregloTemporal = array(
                'idPregunta' => $pregunta->getIdPregunta(), // id del Label
                'letra' => $pregunta->getLetra(), // valor del Label
                'estadoRespuesta' => $pregunta->getEstadoRespuesta() // clase del Label
            );
            $preguntasPendientes[] = $arregloTemporal;
        }
    $roscosJSON[] = array(
        'idJugador' => $jugador->getID(),
        'preguntasPendientes' => $preguntasPendientes
    );
    }
}
?>