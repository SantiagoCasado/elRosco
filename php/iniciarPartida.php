<?php
include_once("php/partida.class.php");
include_once("php/historial.class.php");
session_start();

if (isset($_POST['botonSalir'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit;
}

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script type='text/javascript'>alert('$mensaje');</script>";
    unset($_SESSION['mensaje']);
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

if (!isset($_SESSION['partida'])) {
    // PARTIDA NUEVA
    if (isset($_POST['botonComenzarPartida'])) {
        
        //Obtener caracteristicas de la partida
        $dificultad = $_POST['radioNivelPartida'];
        $tiempoPartida = $_POST['radioDuracionPartida'];
        if(isset($_POST['checkboxAyuda'])) {
            $ayudaAdicional = 1;
        } else {
            $ayudaAdicional = 0;
        }
        
        //Instanciar la partida
        $partida = new Partida();
        $partida -> iniciarNuevaPartida($dificultad, $tiempoPartida, $ayudaAdicional, $jugadores);

        // Cargar el historial entre los jugadores - Se carga el historial antes de crear crear la partida en BD
        // para que no aparezca en la tabla el nuevo registro
        $historial = new Historial();
        $historial -> getHistorialVictorias($partida -> getJugadores()[0] -> getID(), $partida -> getJugadores()[1] -> getID());
        $_SESSION['historial'] = serialize($historial);

        $partidaHistorial = new Partida();
        $listadoPartidas = $partidaHistorial -> cargarHistorialPartidas($jugadores);
        if ($listadoPartidas != null) {
            $_SESSION['historialPartidas'] = serialize($listadoPartidas);
        }
            
        //Guardar la partida y el idPartida generado en la base de datos y en sesion respectivamente
        $idPartida = $partida -> crearPartidaBD();
        $_SESSION['idPartida'] = $idPartida;

        // Guardar partida en sesion
        $_SESSION['partida'] = serialize($partida);

    } else {
        $mensaje = 'Debe crear una partida para jugar';
        $_SESSION['mensaje'] = $mensaje;
        header("location:crearPartida.php");
        exit;
    }         
} else {
    // PARTIDA EN SESION
    $partida = unserialize($_SESSION['partida']);
    $historial = unserialize($_SESSION['historial']);
    $listadoPartidas = unserialize($_SESSION['historialPartidas']);
}

//Preparar los objetos JSON para la vista del juego
$partidaJSON = array(
    'idPartida' => $partida->getIdPartida(), //
    'dificultad' => $partida->getDificultad(), //
    'tiempoPartida' => $partida->getTiempoPartida(), //
    'ayuda' => $partida->getAyuda(), //
    'turnoActual' => $partida->getTurnoActual(), //
    'jugadores' => array(),
    'ganador' => '', //
    'roscos' => array(),
    'historial' => array()
);
        
// Agregar jugadores al JSON
foreach ($partida->getJugadores() as $jugador) {
    $partidaJSON['jugadores'][] = array(
        'idUsuario' => $jugador->getID(), //
        'nombreUsuario' => $jugador->getNombreUsuario(), //
        'tiempoRestante' => $partida->getTiemposRestantes()[$jugador->getID()]
        );
}

if ($partida -> getGanador() != null) {
    // Termino el juego - Obtener el ganador
    $ganadorPartida = $partida -> getGanador();
    $ganador = array(
        'idUsuario' => $ganadorPartida -> getId(),
        'nombreUsuario' => $ganadorPartida -> getNombreUsuario(),
        'puntaje' => $partida -> getPuntajes()[$ganadorPartida -> getID()],
        'tiempo' => $partida -> getTIemposRestantes()[$ganadorPartida -> getID()]
    );
} else {
    $ganador = null;    
}
$partidaJSON['ganador'] = $ganador;



        
// Agregar roscos al JSON
foreach ($partida->getRoscos() as $idJugador => $rosco) {
    // Crear un nuevo arreglo temporal para cada rosco
    $roscoJSON = array(
        'idRosco' => $rosco->getIdRosco(),
        'estadoRosco' => $rosco->getEstadoRosco(), //
        'preguntasPendientes' => array(),
        'preguntasArriesgadas' => array(),
    );
    
    // Agregar preguntas pendientes al rosco
    foreach ($rosco->getPreguntasPendientes() as $pregunta) {
        $roscoJSON['preguntasPendientes'][] = array(
            'idPregunta' => $pregunta->getIdPregunta(), //
            'letra' => $pregunta->getLetra(), //
            'palabra' => $pregunta->getPalabra(), //
            'descripcion' => $pregunta->getDescripcion(), //
            'estadoRespuesta' => $pregunta->getEstadoRespuesta() //
        );
    }

    // Agregar preguntas arriesgadas al rosco
    foreach ($rosco->getPreguntasArriesgadas() as $pregunta) {
        $roscoJSON['preguntasArriesgadas'][] = array(
            'idPregunta' => $pregunta->getIdPregunta(), //
            'letra' => $pregunta->getLetra(), //
            'palabra' => $pregunta->getPalabra(), //
            'estadoRespuesta' => $pregunta->getEstadoRespuesta() //
        );
    }

// Asignar el rosco al jugador en partidaJSON
$partidaJSON['roscos'][$idJugador] = $roscoJSON;
}



// Agregar el historial al JSON
if (is_null($listadoPartidas) || empty($listadoPartidas) || is_null($historial) || empty($historial)){	

    $victorias = array(
        'nombreUsuario1' => $jugadores[0] -> getNombreUsuario(),
        'victoriasJugador1' => '0',
        'nombreUsuario2' => $jugadores[1] -> getNombreUsuario(),
        'victoriasJugador2' => '0',
    );

    $ultimasPartidas = new StdClass();
	$objTemp->ganador = 'No hay historial entre los jugadores';
	$objTemp->tiempoPartida = '';
	$objTemp->ayuda = '';
	$objTemp->jugadores = $jugadores;
	$objTemp->puntajes = '';
	$objTemp->tiemposRestantes = '';

} else {
    $victorias = array(
        'nombreUsuario1' => $historial -> getNombreUsuario1(),
        'victoriasJugador1' => $historial -> getVictoriasJugador1(),
        'nombreUsuario2' => $historial -> getNombreUsuario2(),
        'victoriasJugador2' => $historial -> getVictoriasJugador2()
    );
    
    $ultimasPartidas = array();
	foreach ($listadoPartidas as $partidaHistorial) {
        $puntajes = array();
        foreach ($partidaHistorial -> getPuntajes() as $puntaje) {
            $puntajes[] = $puntaje;
        }

        $tiemposRestantes = array();
        foreach ($partidaHistorial -> getTiemposRestantes() as $tiempoRestante) {
            $tiemposRestantes[] = $tiempoRestante;
        }

		$arregloTemp = array('ganador' => $partidaHistorial->getGanador(),
                        'dificultad' => $partidaHistorial->getDificultad(),
						'tiempoPartida' => $partidaHistorial->getTiempoPartida(), //
						'ayuda' => $partidaHistorial->getAyuda(), //
						'puntajes' => $puntajes, //
						'tiemposRestantes' => $tiemposRestantes //
                        );
		$ultimasPartidas[] = $arregloTemp;
	}
}
$historialJSON = array(
    'victorias' => $victorias,
    'ultimasPartidas' => $ultimasPartidas
);

$partidaJSON['historial'] = $historialJSON;
?>            