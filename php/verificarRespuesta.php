<?php
echo json_encode('entro a verificarRespuesta');

// include_once("partida.class.php");
// session_start();

// if (isset($_SESSION['partida'])) {
//     $partida = unserialize($_SESSION['partida']);

//     if (!$partida) {
//         echo json_encode(["error" => "No se pudo deserializar la partida."]);
//         exit;
//     }
// } else {
//     // Si la sesión se perdió, recuperar desde la base de datos
//     $idPartida = $_SESSION['idPartida'];
//     $partida = new Partida();
//     $partida = $partida -> cargarPartidaBD($idPartida); 
// }

// if (isset($_POST['respuesta'])) {
//     $idUsuario = $_POST['idUsuario']; // agregar hidden en formulario
//     $idPregunta = $_POST['idPregunta']; // agregar hidden en formulario
//     $tiempoRestante = $_POST['tiempoRestante']; // agregar hidden en formulario
//     $respuesta = $_POST['respuesta']; 

//     $respuestaCorrecta = $partida -> verificarRespuesta($idUsuario, $idPregunta, $respuesta, $tiempoRestante);
    
//     if (is_null($respuestaCorrecta) || empty($respuestaCorrecta)) {	
//         // Sin respuesta del servidor
//         $resultadoJSON = array();
//     } else {
//         $pregunta = array(
//             'idPregunta' => $idPregunta,
//             'estadoRespuesta' => $estadoRespuesta
//         );

//         $jugador = array(
//             'idUsuario' => $idUsuario,
//             'puntaje' => $partida->getPuntajes()[$idUsuario]
//         );

//         $resultadoJSON = array(
//             'pregunta' => $pregunta,
//             'jugador' => $jugador
//         );
//     } 
//     echo json_encode($resultadoJSON);
// }
?>