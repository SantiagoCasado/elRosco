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
    private $enJuego;

    public function __construct() {
    }

    public function iniciarNuevaPartida($dificultad, $tiempoPartida, $ayuda, $jugadores) {
        $this->dificultad = $dificultad;
        $this->tiempoPartida = 60 * $tiempoPartida;
        $this->ayuda = $ayuda;
        $this->jugadores = $jugadores;
        $this->roscos = array();
        $this->puntajes = array();
        $this->tiemposRestantes = array();
        $this->turnoActual = rand(0, 1);//Se define aleatoriamente quien inicia la partida
        $this->ganador = null;
        $this->enJuego = false;
        
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

    public function getEnJuego() {
        return $this->enJuego;
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

    public function setEnJuego($enJuego) {
        $this -> enJuego = $enJuego;
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
        if (isset($_SESSION['idPreguntas'])) {
            unset($_SESSION['idPreguntas']);
        }
        
        // Crear los roscos y asignarlos a cada jugador
        foreach ($this->jugadores as $jugador) {
            
            $rosco = new Rosco($this -> dificultad);
    
            // Almacenar el rosco en el arreglo de roscos
            $this->roscos[$jugador->getID()] = $rosco;
        }
    }
    
    public function crearPartidaBD() {
        //Crear partida en la base de datos
        try {
            $bd = new BaseDatos();
            $conexion = $bd->conectarBD();

            // Iniciar la transacción
            $conexion->begin_transaction();
            
            // Guardar la partida (PARTIDA)
            $minutos = $this -> tiempoPartida / 60;
            $segundos = $this -> tiempoPartida % 60;
            $tiempoPartidaSQL = sprintf('%02d:%02d:%02d', 0, $minutos, $segundos); // Se pasa de entero a minutos y segundos
            $sqlPartida = "INSERT INTO partida (tiempo, dificultadPartida, ayudaAdicional) 
                           VALUES ('$tiempoPartidaSQL', '$this->dificultad', '$this->ayuda')";
            if (!$bd->consulta($sqlPartida)) {
                throw new Exception("Error al guardar la partida");
            }

            //Obtener el ID de la partida guardada y asignarselo al objeto
            $idPartida = $bd -> getIdInsertado();
            $this -> setIdPartida($idPartida);

            // Guardar cada usuario y su respectivo rosco para la partida (PARTIDA_USUARIO)
            foreach ($this->jugadores as $jugador) {
                // Obtener rosco para cada usuario
                $rosco = $this -> roscos[$jugador->getID()];
                
                $sqlRosco = "INSERT INTO rosco (estadoRosco) 
                            VALUES ('" . $rosco -> getEstadoRosco() . "')";
                if (!$bd->consulta($sqlRosco)) {
                    throw new Exception("Error al guardar el rosco para el jugador con ID: " . $jugador->getID());
                }

                // Obtener el idRosco generado y asignarselo al objeto
                $idRosco = $bd -> getIdInsertado();
                $rosco -> setIdRosco($idRosco);

                $sqlPartidaUsuario = "INSERT INTO partida_usuario (idPartida, idUsuario, tiempoRestante, idRosco) 
                                      VALUES ('$idPartida', '" . $jugador->getID() . "', '$tiempoPartidaSQL', '$idRosco')";
                if (!$bd->consulta($sqlPartidaUsuario)) {
                    throw new Exception("Error al guardar partida-usuario para el jugador con id=" . $jugador->getID());
                }
    
                // Guardar las preguntas asignadas al rosco (ROSCO_PREGUNTA)
                foreach ($rosco->getPreguntasPendientes() as $pregunta) {
                    $sqlRoscoPregunta = "INSERT INTO rosco_pregunta (idRosco, idPregunta, estadoRespuesta) 
                                        VALUES ('$idRosco', '" . $pregunta->getIdPregunta() . "', 'sinResponder')";
                    if (!$bd->consulta($sqlRoscoPregunta)) {
                        throw new Exception("Error al guardar la pregunta con id=" . $pregunta->getIdPregunta() . " en rosco con id=" . $idRosco);
                    }
                }
            }

            // HISTORIAL
            $idUsuario1 = $this -> getJugadores()[0] -> getID();
            $idUsuario2 = $this -> getJugadores()[1] -> getID();

            $sqlExisteHistorial = "SELECT * FROM HISTORIAL
                                WHERE (idUsuario1 = '$idUsuario1' AND idUsuario2 = '$idUsuario2')
                                OR (idUsuario1 = '$idUsuario2' AND idUsuario2 = '$idUsuario1')";
            $resultadoConsulta = $bd -> consulta($sqlExisteHistorial);
            if (!$resultadoConsulta) {
                throw new Exception("Error al consultar el historial");
            }
            // Si es la primera vez que los jugadores juegan entre si, se crea un nuevo registro
            if ($resultadoConsulta->num_rows == 0) {
                $sqlHistorial = "INSERT INTO HISTORIAL (idUsuario1, idUsuario2, victoriasJugador1, victoriasJugador2)
                                VALUES ('$idUsuario1', '$idUsuario2', 0, 0);";
                if (!$bd->consulta($sqlHistorial)) {
                    throw new Exception("Error al crear el historial");
                }
            }
            $resultadoConsulta -> free();

            // Confirmar la transacción
            $conexion->commit();
            $bd->cerrarBD();

            return $idPartida;
            
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conexion->rollback();
            $bd->cerrarBD();
            return error_log("Error al crear la partida en la base de datos: " . $e->getMessage());
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
            $idGanador = $this -> getGanador() -> getID();
            $idPartida = $this -> getIdPartida();
            $sqlPartida = "UPDATE partida 
                            SET ganador = '$idGanador'
                            WHERE idPartida = '$idPartida'";
            if (!$bd->consulta($sqlPartida)) {
                throw new Exception("Error al guardar la partida");
            }
            error_log("Partida actualizada con id: $idPartida y ganador: $idGanador");
            
            // Guardar cada usuario y su respectivo rosco para la partida (PARTIDA_USUARIO)
            foreach ($this->jugadores as $jugador) {
                // Obtener rosco para cada usuario
                $idUsuario = $jugador -> getID();
                $rosco = $this -> roscos[$idUsuario];
                
                $estadoRosco = $rosco -> getEstadoRosco();
                // ROSCO
                $idRosco = $rosco -> getIdRosco();
                $sqlRosco = "UPDATE rosco 
                            SET estadoRosco = '$estadoRosco'
                            WHERE idRosco = '$idRosco'";
                if (!$bd->consulta($sqlRosco)) {
                    throw new Exception("Error al guardar el rosco para el jugador con ID: " . $jugador->getID());
                }
                error_log("Rosco actualizado con id: $idRosco");
                
                $puntaje = $this -> getPuntajes()[$idUsuario];
                $tiempoRestante = $this -> getTiemposRestantes()[$idUsuario];
                $minutos = $tiempoRestante / 60;
                $segundos = $tiempoRestante % 60;

                // PARTIDA_USUARI0
                $tiempoRestanteSQL = sprintf('%02d:%02d:%02d', 0, $minutos, $segundos);
                $sqlPartidaUsuario = "UPDATE partida_usuario
                                    SET 
                                        puntaje = '$puntaje',
                                        tiempoRestante = '$tiempoRestanteSQL'
                                    WHERE
                                        idPartida = '$idPartida' AND
                                        idUsuario = '$idUsuario'";
                if (!$bd->consulta($sqlPartidaUsuario)) {
                    throw new Exception("Error al guardar partida-usuario para el jugador con id=" . $jugador->getID());
                }
                error_log("Partida_usuario actualizada para usuario: $idUsuario");

                // Guardar las preguntas asignadas al rosco (ROSCO_PREGUNTA)
                // Se actualizan solo las preguntas arriesgadas por que son las que cambiaron de estado (correcto o incorrecto)
                foreach ($rosco->getPreguntasArriesgadas() as $pregunta) {
                    $estadoRespuesta = $pregunta -> getEstadoRespuesta();
                    $idPregunta = $pregunta -> getIdPregunta();

                    // ROSCO_PREGUNTA
                    $sqlRoscoPregunta = "UPDATE rosco_pregunta
                                        SET estadoRespuesta = '$estadoRespuesta'
                                        WHERE
                                            idPregunta = '$idPregunta' AND
                                            idRosco = '$idRosco'";
                    if (!$bd->consulta($sqlRoscoPregunta)) {
                        throw new Exception("Error al guardar la pregunta con id=" . $pregunta->getIdPregunta() . " en rosco con id=" . $idRosco);
                    }
                    error_log("Rosco_pregunta actualizada con idPregunta: $idPregunta para idRosco: $idRosco");
                }
            }

            // HISTORIAL
            $idGanador = $this -> getGanador() -> getID();
            $idUsuario1 = $this -> getJugadores()[0] -> getID();
            $idUsuario2 = $this -> getJugadores()[1] -> getID();


            $sqlHistorial = "UPDATE HISTORIAL 
                            SET victoriasJugador1 = CASE
                                WHEN idUsuario1 = '$idGanador' THEN victoriasJugador1 + 1
                                ELSE victoriasJugador1
                                END,
                                victoriasJugador2 = CASE
                                WHEN idUsuario2 = '$idGanador' THEN victoriasJugador2 + 1
                                ELSE victoriasJugador2
                                END
                            WHERE (idUsuario1 = '$idUsuario1' AND idUsuario2 = '$idUsuario2') 
                                OR (idUsuario1 = '$idUsuario2' AND idUsuario2 = '$idUsuario1');";
            if (!$bd->consulta($sqlHistorial)) {
                throw new Exception("Error al crear el historial");
            }
            error_log("Historial actualizado para usuarios: $idUsuario1 y $idUsuario2");

            // Confirmar la transacción
            $conexion->commit();
            error_log("Transacción completada");
            $bd->cerrarBD();                 
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conexion->rollback();
            $bd->cerrarBD();  
            
            return error_log("Error al guardar la partida en la base de datos: " . $e->getMessage());
        }
                
    }

    public function cargarHistorialPartidas($jugadores) {
        // Se obtienen las ultimas partidas jugadas
        try {
            $bd = new BaseDatos();
            $conexion = $bd->conectarBD();
            
            $idUsuario1 = $jugadores[0] -> getID();
            $idUsuario2 = $jugadores[1] -> getID();
            // Ganador, dificultad, tiempo, ayuda, nombreUsuario1, puntos1, tiempo1, nombreUsuario2, puntos2, tiempo2
            $sqlHistorialPartidas = "SELECT p.ganador, p.dificultadPartida,
                                            p.tiempo, p.ayudaAdicional,
                                            u1.puntaje AS puntos1, u1.tiempoRestante AS tiempo1,
                                            u2.puntaje AS puntos2, u2.tiempoRestante AS tiempo2 
                                    FROM partida_usuario u1 
                                        INNER JOIN partida_usuario u2 ON u1.idPartida = u2.idPartida 
                                        INNER JOIN partida p ON p.idPartida = u2.idPartida 
                                    WHERE (u1.idUsuario = '$idUsuario1' AND u2.idUsuario = '$idUsuario2')
                                    ORDER BY p.idPartida DESC 
                                    LIMIT 5;";
    
            $resultadoPartida = $bd -> consulta($sqlHistorialPartidas);
    		
            $listadoPartidas = array();
            while ($registro = $resultadoPartida->fetch_object()) {	
                $partida = new Partida();
                $partida->setGanador($registro->ganador);
                $partida->setDificultad($registro->dificultadPartida);
                
                // Pasar el tiempo de time a entero (segundos)
                $tiempo = $registro -> tiempo;
                [$horas, $minutos, $segundos] = explode(":", $tiempo);
                $segundos = ($horas * 3600) + ($minutos * 60) + $segundos;
                $partida->setTiempoPartida($segundos);

                $partida->setAyuda($registro->ayudaAdicional);
                $partida->setJugadores($jugadores);

                $puntajes = array();
                $puntajes[$idUsuario1] = $registro->puntos1;
                $puntajes[$idUsuario2] = $registro->puntos2;
                $partida->setPuntajes($puntajes);

                $tiemposRestantes = array();

                $tiempo = $registro -> tiempo1;
                [$horas, $minutos, $segundos] = explode(":", $tiempo);
                $segundos1 = ($horas * 3600) + ($minutos * 60) + $segundos;
                $tiemposRestantes[$idUsuario1] = $segundos1;
                
                $tiempo = $registro -> tiempo2;
                [$horas, $minutos, $segundos] = explode(":", $tiempo);
                $segundos2 = ($horas * 3600) + ($minutos * 60) + $segundos;
                $tiemposRestantes[$idUsuario2] = $segundos2;

                $partida->setTiemposRestantes($tiemposRestantes);

                $listadoPartidas[]=$partida;
            }
            $resultadoPartida->free();
            $bd->cerrarBD();
            
            error_log('Se cargó correctamente el historial de partidas');
            return $listadoPartidas;
        } catch (Exception $e) {
            $bd->cerrarBD();
            return error_log("Error al cargar las ultimas partidas: " . $e->getMessage());
        }
    }
    
    //FIJARSE DE SACAR O NO EL IDPregunta
    public function verificarRespuesta($idUsuario, $idPregunta, $respuesta) {
        
        // Obtener el rosco correspondiente para el jugador
        $rosco = $this-> getRoscos()[$idUsuario];
        // Obtener la pregunta con el estado de la respuestas (correcto o incorrecto)
        $preguntaRespondida = $rosco -> verificarRespuestaRosco($respuesta);

        return $preguntaRespondida;
    }

    public function pasapalabra($idUsuarioActual) {
        // Actualizar el rosco del jugador que paso la palabra
        $roscoActual = $this -> getRoscos()[$idUsuarioActual];
        $roscoActual -> pasapalabra();
    }

    public function actualizarEstadoJuego($idUsuario, $estadoRosco, $estadoRespuesta, $tiempoRestante, $abandonar) {
            // Si coincide y la respuesta es correcta
            if ($estadoRespuesta == 'correcto') {
                // Si la respuesta es correcta
                $this -> incrementarPuntaje($idUsuario);
                
                // Verificar el estado del rosco
                if ($estadoRosco == 'completo') {
                    // Rosco completo - No quedan preguntas por responder
                    // Se debe detener el juego del jugador actual
                    $this -> setEnJuego(false);
                    $this -> actualizarTiempoJugador($idUsuario, $tiempoRestante);

                    if (!($this -> verificarJuegoFinalizado($abandonar))) {
                        // Juego no finalizado
                        $this -> cambiarTurno();
                    }
                } else {
                    // Contnua el jugador actual
                    $this -> setEnjuego(true);
                }
            } else {
            // Respuesta incorrecta, jugador sin tiempo o abandono
            // Se debe detener el juego
            $this -> setEnJuego(false);
            $this -> actualizarTiempoJugador($idUsuario, $tiempoRestante);
            
            if (!$this -> verificarJuegoFinalizado($abandonar)) {
                $this -> cambiarTurno();
                $this -> verificarCambioTurno();
                        
            }
        }
    }    

    public function incrementarPuntaje($idUsuario) {
        $this -> puntajes[$idUsuario]++;
    }

    public function actualizarTiempoJugador($idUsuario, $tiempoRestante) {
        $this -> tiemposRestantes[$idUsuario] = $tiempoRestante;
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
        $tiempoSiguiente = $this -> getTiemposRestantes()[$idUsuarioSiguiente];
        
        // Si el siguiente jugador no tiene preguntas pendientes, el turno vuelve al jugador actual
        if ($roscoSiguiente -> getEstadoRosco() == 'completo' || $tiempoSiguiente == 0) {
            // No tiene preguntas pendientes o se le termino el tiempo: termino su juego
            // Sigue el jugador actual
            $this -> cambiarTurno();
        } 
    }

    public function verificarJuegoFinalizado($abandonar) {
        // Ver los estados de los roscos y el tiempo por jugador
        $jugador1 = $this->getJugadores()[0];
        $roscoJugador1 = $this->getRoscos()[$jugador1->getID()]->getEstadoRosco();
        $tiempoJugador1 = $this->getTiemposRestantes()[$jugador1->getID()];

        $jugador2 = $this->getJugadores()[1];
        $roscoJugador2 = $this->getRoscos()[$jugador2->getID()]->getEstadoRosco();
        $tiempoJugador2 = $this->getTiemposRestantes()[$jugador2->getID()];


        // Si el rosco esta completo => verdadero
        // Rosco Jugador 1
        $r1 = $roscoJugador1 == 'completo';
        // Rosco Jugador 2
        $r2 = $roscoJugador2 == 'completo';
        
        // Si el tiempo finalizo => verdadero
        // Tiempo Jugador 1
        $t1 = $tiempoJugador1 == 0;
        // Tiempo Jugador 2
        $t2 = $tiempoJugador2 == 0;

        // Para que la partida este finalizada:
        // F = R1*R2 + R1*T1 + R2*T1 + T1*T2 + abandonar
        if (($r1 && $r2) || ($r1 && $t1) || ($r2 && $t1) || ($t1 && $t2) || $abandonar) {
            $this->definirGanador($jugador1, $jugador2);
            
            return true;
        } else {
            return false;
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
                $arregloJugadores = [$jugador1, $jugador2];
                shuffle($arregloJugadores);
                $ganador = $arregloJugadores[0];
                $this -> setGanador($ganador);
            }
        }
        $this -> guardarPartidaBD();
    }
}
?>