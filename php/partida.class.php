<?php
include_once('baseDatos.class.php');
include_once('usuario.class.php');
include_once('rosco.class.php');
include_once('pregunta.class.php');
class Partida {
    private $idPartida;
    private $jugadores;
    private $roscos;
    private $puntajes;
    private $tiemposRestantes;
    private $tiempoPartida;
    private $dificultad;
    private $ayuda;
    private $turnoActual;
    private $ganador;

    public function __construct() {
    }

    public function iniciarNuevaPartida($dificultad, $tiempoPartida, $ayuda, $jugadores) {
        $this -> idPartida = 1; // Por defecto hasta obtener el generado en la BD
        $this->dificultad = $dificultad;
        $this->tiempoPartida = 60 * $tiempoPartida;
        $this->ayuda = $ayuda;
        $this->jugadores = $jugadores;
        $this->roscos = array();
        $this->puntajes = array();
        $this->tiemposRestantes = array();
        $this->turnoActual = rand(0, 1);//Se define aleatoriamente quien inicia la partida
        $this->ganador = null;
        
        //Inicializar datos
        $this->prepararJugadores($jugadores);
        $this->prepararRoscos();
    }

    public function getIdPartida() {
        return $this->idPartida;
    }

    public function getJugadores() {
        return $this->jugadores;
    }

    public function getRoscos() {
        return $this->roscos;
    }

    public function getPuntajes() {
        return $this->puntajes;
    }

    public function getTiemposRestantes() {
        return $this->tiemposRestantes;
    }

    public function getTiempoPartida() {
        return $this->tiempoPartida;
    }

    public function getDificultad() {
        return $this->dificultad;
    }

    public function getAyuda() {
        return $this->ayuda;
    }

    public function getTurnoActual() {
        return $this->turnoActual;
    }

    public function getGanador() {
        return $this->ganador;
    }

    public function setIdPartida($idPartida) {
        $this->idPartida = $idPartida;
    }

    public function setJugadores($jugadores) {
        $this->jugadores = $jugadores;
    }

    public function setRoscos($roscos) {
        $this->roscos = $roscos;
    }

    public function setPuntajes($puntajes) {
        $this->puntajes = $puntajes;
    }

    public function setTiemposRestantes($tiemposRestantes) {
        $this->tiemposRestantes = $tiemposRestantes;
    }

    public function setTiempoPartida($tiempoPartida) {
        $this->tiempoPartida = $tiempoPartida;
    }

    public function setDificultad($dificultad) {
        $this->dificultad = $dificultad;
    }

    public function setAyuda($ayuda) {
        $this->ayuda = $ayuda;
    }

    public function setTurnoActual($turnoActual) {
        $this->turnoActual = $turnoActual;
    }

    public function setGanador($ganador) {
        $this->ganador = $ganador;
    }

    public function prepararJugadores($jugadores) {
        foreach ($jugadores as $jugador) {
            //Inicializar su puntaje en 0
            $this->puntajes[$jugador->getID()] = 0;

            //Inicializar su tiempo = tiempo total
            $this->tiemposRestantes[$jugador->getID()] = $this->tiempoPartida;
        }
    }

    public function prepararRoscos() {
        // Si hubo una partida previa, se limpian las preguntas utilizadas
        unset($_SESSION['idPreguntas']);

        // Crear los roscos y asignarlos a cada jugador
        foreach ($this->jugadores as $jugador) {
            
            $rosco = new Rosco($this -> dificultad);
    
            // Almacenar el rosco en el arreglo de roscos
            $this->roscos[$jugador->getID()] = $rosco;
        }
    }
    
    public function guardarPartidaBD() {
        //Guardar partida en la base de datos
        try {
            $bd = new BaseDatos();
            $conexion = $bd->conectarBD();

            // Iniciar la transacción
            $conexion->begin_transaction();
            
            // Guardar la partida (PARTIDA)
            $tiempoPartidaSQL = sprintf('%02d:%02d:%02d', 0, $this->tiempoPartida, 0); // Se pasa de entero a minutos
            $sqlPartida = "INSERT INTO partida (tiempo, dificultadPartida, ayudaAdicional) 
                           VALUES ('$tiempoPartidaSQL', '$this->dificultad', '$this->ayuda')";
            $bd -> consulta($sqlPartida);

            //Obtener el ID de la partida guardada y asignarselo al objeto
            $idPartida = $conexion->insert_id;
            $this -> setIdPartida($idPartida);
    
            // Guardar cada usuario y su respectivo rosco para la partida (PARTIDA_USUARIO)
            foreach ($this->jugadores as $jugador) {
                // Obtener rosco para cada usuario
                $rosco = $this -> roscos[$jugador->getID()];
                
                $sqlRosco = "INSERT INTO rosco (estadoRosco) 
                            VALUES ('" . $rosco -> getEstadoRosco() . "')";
                $bd -> consulta($sqlRosco);

                // Obtener el idRosco generado y asignarselo al objeto
                $idRosco = $conexion -> insert_id;
                $rosco -> setIdRosco($idRosco);

                $sqlPartidaUsuario = "INSERT INTO partida_usuario (idPartida, idUsuario, tiempoRestante, idRosco) 
                                      VALUES ('$idPartida', '" . $jugador->getID() . "', '$tiempoPartidaSQL', '$idRosco')";
                $bd -> consulta($sqlPartidaUsuario);
    
                // Guardar las preguntas asignadas al rosco (ROSCO_PREGUNTA)
                $resultadoRoscoPregunta = array();
                foreach ($rosco->getPreguntasPendientes() as $pregunta) {
                    $sqlPartidaUsuario = "INSERT INTO rosco_pregunta (idRosco, idPregunta, estadoRespuesta) 
                                        VALUES ('$idRosco', '" . $pregunta->getIdPregunta() . "', 'sinResponder')";
                    $bd -> consulta($sqlPartidaUsuario);
                }
            }
            // Confirmar la transacción
            $conexion->commit();
            $bd->cerrarBD();

            return $idPartida;
            
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conexion->rollback();
            return error_log("Error al guardar la partida: " . $e->getMessage());
            //return "Hubo un error al guardar la partida.";
        }
    }

    public function cargarPartidaBD($idPartida) {
        $bd = new BaseDatos();
        $conexion = $bd->conectarBD();

        $sqlPartida = "SELECT *
                FROM partida
                WHERE idPartida = $idPartida";

        $resultadoPartida = $bd -> consulta($sqlPartida);

        if ($resultadoPartida) {
            // Cargar atributos de Partida
            $this -> idPartida = $resultadoPartida['idPartida'];
            $this -> dificultad = $resultadoPartida['dificultad'];
            $this -> tiempoPartida = $resultadoPartida['tiempoPartida'];
            $this -> ayuda = $resultadoPartida['ayuda'];
            $this -> turnoActual = $resultadoPartida['turnoActual'];
            $this -> ganador = $resultadoPartida['ganador'];

            $this -> cargarJugadores($idPartida);

            $this -> cargarEstadoPartida($idPartida, $this -> jugadores);
        }
    }

    public function cargarJugadores($idPartida) {
        $jugador1 = new Usuario();
        $jugador1 -> cargarJugadorBD($idPartida);
        $jugador2 = new Usuario();
        $jugador2 -> cargarJugadorBD($idPartida);
        $this -> setJugadores([$jugador1, $jugador2]);
    }

    public function cargarEstadoPartida($idPartida, $jugadores) {
        // Obtener puntaje, tiempoRestante y el idRosco por jugador
    }

    public function cargarRoscos() {
        // Obtener Rosco
    }
    

    //FIJARSE DE SACAR O NO EL IDPARTIDA
    public function verificarRespuesta($idUsuario, $idPregunta, $respuesta, $tiempoRestante) {
        
        // Obtengo el rosco correspondiente para el jugador
        $rosco = $this-> getRoscos()[$idUsuario];
        // Obtengo la pregunta con el estado de la respuestas (correcto o incorrecto)
        $preguntaRespondida = $rosco -> verificarRespuestaRosco($respuesta);

        // Si coincide y la respuesta es correcta
        if ($preguntaRespondida -> getEstadoRespuesta() == 'correcto') {
            // Si la respuesta es correcta
            $this -> incrementarPuntaje($idUsuario);

            // Verificar el estado del rosco
            if ($rosco -> getEstadoRosco() == 'completo') {
                // Rosco completo - No quedan preguntas por responder
                $this -> verificarJuegoFinalizado();
                if ($this -> getGanador() == null) {
                    // Juego no finalizado
                    $this -> cambiarTurno();
                }
            }
        } else {
            // Respuesta incorrecta
            $this -> cambiarTurno();
            $this -> verificarCambioTurno();
            $this -> actualizarEstadoPartida($this->getIdPartida(), $idUsuario, $tiempoRestante, $this->getPuntajes()[$idUsuario]);
        }
        return $preguntaRespondida;
    }


    public function pasapalabra($idUsuarioActual) {
        // Actualizar el rosco del jugador que paso la palabra
        $roscoActual = $this -> getRoscos()[$idUsuarioActual];
        $roscoActual -> pasapalabra();
        
        // Cambiar turno
        $this -> cambiarTurno();
        $this -> verificarCambioTurno();
    }

    public function incrementarPuntaje($idUsuario) {
        $this -> puntajes[$idUsuario]++;
    }

    public function actualizarEstadoPartida($idPartida, $idUsuario, $tiempoRestante, $puntaje) {
        // Antes de cambiar turno se actualiza la BD para tener un back up
        try {
            $bd = new BaseDatos();
            $conexion = $bd->conectarBD();
            
            // Actualizar tiempo restante y puntaje (PARTIDA_USUARIO)
            $minutos = $tiempoRestante / 60;
            $segundos = $tiempoRestante % 60;
            $tiempoRestanteSQL = sprintf('%02d:%02d:%02d', 0, $minutos, $segundos); // Se pasa de entero a minutos y segundos
            $sqlPartidaUsuario = 
                                "UPDATE partida_usuario 
                                SET tiempoRestante = '$tiempoRestanteSQL', puntaje = $puntaje
                                WHERE idPartida = $idPartida AND idUsuario = $idUsuario";
            $bd -> consulta($sqlPartidaUsuario);

            $bd->cerrarBD();
            
        } catch (Exception $e) {
            $bd->cerrarBD();
            return error_log("Error al guardar la partida: " . $e->getMessage());
        } // Agregar finally para cerrar siempre la bd
    }

    public function cambiarTurno() {
        if ($this->turnoActual == 0) {
            $this -> turnoActual = 1;
        } else {
            $this -> turnoActual = 0;
        }
    }

    public function verificarCambioTurno() {
        $turnoActual = $this->getTurnoActual();

        // Si el rosco del otro jugador tiene preguntas pendientes, se hace efectivo el cambio de turno
        $usuarioSiguiente = $this -> getJugadores()[$turnoActual];
        $idUsuarioSiguiente = $usuarioSiguiente -> getID();

        $roscoSiguiente = $this -> getRoscos()[$idUsuarioSiguiente];
        
        // Si el siguiente jugador no tiene preguntas pendientes, el turno vuelve al jugador actual
        // if (count($roscoSiguiente->getPreguntasPendientes()) == 0) {
        if ($roscoSiguiente -> getEstadoRosco() == 'completo') {
            // No tiene preguntas pendientes, termino su juego
            // Sigue el jugador actual
            $this -> cambiarTurno();
        } 
    }

    public function verificarJuegoFinalizado() {
        // Ver los estados de los roscos
        $jugador1 = $this->getJugadores()[0];
        $jugador2 = $this->getJugadores()[1];
        $roscoJugador1 = $this->getRoscos()[$jugador1->getID()];
        $roscoJugador2 = $this->getRoscos()[$jugador2->getID()];

        if ($roscoJugador1->getEstadoRosco() == 'completo' && $roscoJugador2->getEstadoRosco() == 'completo') {
            $this->definirGanador($jugador1, $jugador2);
        }
    }

    public function definirGanador($jugador1, $jugador2) {
        $puntosJugador1 = $this -> getPuntajes()[$jugador1 -> getID()];
        $puntosJugador2 = $this -> getPuntajes()[$jugador2 -> getID()];

        if ($puntosJugador1 > $puntosJugador2) {
            $this -> setGanador($jugador1);
        } elseif ($puntosJugador2 > $puntosJugador1) {
            $this -> setGanador($jugador2);
        } else {
            $tiempoJugador1 = $this -> getTiemposRestantes()[$jugador1 -> getID()];
            $tiempoJugador2 = $this -> getTiemposRestantes()[$jugador2 -> getID()];

            if ($tiempoJugador1 > $tiempoJugador2) {
                $this -> setGanador($jugador1);
            } elseif ($tiempoJugador2 > $tiempoJugador1) {
                $this -> setGanador($jugador2);
            } else {
                // Si estan empatados en puntos y tiempo, se decide aleatoriamente
                $arregloJugadores = shuffle([$jugador1, $jugador2]);
                $ganador = $arregloJugadores[0];
                $this -> setGanador($ganador);
            }
        }
    }
}
?>