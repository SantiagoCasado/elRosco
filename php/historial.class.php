<?php
include_once('partida.class.php');

class Historial
{
    private $idUsuario1;
    private $idUsuario2;
    private $nombreUsuario1;
    private $nombreUsuario2;
    private $victoriasJugador1;
    private $victoriasJugador2;
    private $dificultad = ['baja', 'media', 'alta'];
    private $victorias;
    private $puntaje;
    private $tiempoUtilizado;

    public function __construct () {
    }

    public function getUsuario1() {
        return $this -> idUsuario1;
    }
    public function getUsuario2() {
        return $this -> idUsuario2;
    }

    public function getNombreUsuario1() {
        return $this -> nombreUsuario1;
    }
    
    public function getNombreUsuario2() {
        return $this -> nombreUsuario2;
    }

    public function getVictoriasJugador1() {
        return $this -> victoriasJugador1;
    }

    public function getVictoriasJugador2() {
        return $this -> victoriasJugador2;
    }

    public function getDificultad() {
        return $this -> dificultad;
    }

    public function getVictorias() {
        return $this -> victorias;
    }

    public function getPuntaje() {
        return $this -> puntaje;
    }

    public function getTiempoUtilizado() {
        return $this -> tiempoUtilizado;
    }

    public function setUsuario1($idUsuario1) {
        $this -> idUsuario2 = $idUsuario1;
    }

    public function setUsuario2($idUsuario2) {
        $this -> idUsuario2 = ($idUsuario2);
    }

    public function setNombreUsuario1($nombreUsuario1) {
        $this -> nombreUsuario1 = $nombreUsuario1;
    }

    public function setNombreUsuario2($nombreUsuario2) {
        $this -> nombreUsuario2 = $nombreUsuario2;
    }

    public function setVictoriasJugador1($victoriasJugador1) {
        $this -> victoriasJugador1 = $victoriasJugador1;
    }

    public function setVictoriasJugador2($victoriasJugador2) {
        $this -> victoriasJugador2 = $victoriasJugador2;
    }

    public function setVictorias($victorias) {
        $this -> victorias = $victorias;
    }

    public function setPuntaje($puntaje) {
        $this -> puntaje = $puntaje;
    }

    public function setTiempoUtilizado($tiempoUtilizado) {
        $this -> tiempoUtilizado = $tiempoUtilizado;
    }

    public function getHistorialVictorias($idUsuario1, $idUsuario2) {
        try {
			$bd = new BaseDatos();
			$conexion = $bd -> conectarBD();

            // $idUsuario1 = $this -> getUsuario1();
            // $idUsuario2 = $this -> getUsuario2();
            
            $sqlHistorial = "SELECT 
                                u1.nombreUsuario AS nombreUsuario1,
                                u2.nombreUsuario AS nombreUsuario2,
                                h.victoriasJugador1,
                                h.victoriasJugador2
                            FROM 
                                HISTORIAL h
                            JOIN 
                                USUARIO u1 ON h.idUsuario1 = u1.idUsuario
                            JOIN 
                                USUARIO u2 ON h.idUsuario2 = u2.idUsuario
                            WHERE 
                                (h.idUsuario1 = '$idUsuario1' AND h.idUsuario2 = '$idUsuario2')
                            OR 
                            (h.idUsuario1 = '$idUsuario2' AND h.idUsuario2 = '$idUsuario1')"; // Para que sea indiferente en el orden que aparecen los usuarios

			$resultadoConsulta = $bd -> consulta($sqlHistorial);
		
			if ($registro = $resultadoConsulta->fetch_object()) {
                
                $this -> setNombreUsuario1($registro -> nombreUsuario1);
                $this -> setNombreUsuario2($registro -> nombreUsuario2);
                $this -> setVictoriasJugador1($registro -> victoriasJugador1);
                $this -> setVictoriasJugador2($registro -> victoriasJugador2);
			}

			$resultadoConsulta->free();
			$bd->cerrarBD();

		} catch (Exception $e) {
      	  	error_log("Error al buscar el historial: " . $e->getMessage());
   		}
    }

    public function getMejoresJugadores($puntaje, $tiempoUtilizado) {
            try {
                $bd = new BaseDatos();
                $conexion = $bd -> conectarBD();
    
                if ($puntaje != '-----' && $tiempoUtilizado == '-----') {
                    $sqlMejoresJugadores = "SELECT u.nombreUsuario, pu.puntaje,
                                                    TIMEDIFF(p.tiempo, pu.tiempoRestante) AS tiempoUtilizado
                                                    FROM partida p 
                                                    INNER JOIN PARTIDA_USUARIO pu ON p.idPartida = pu.idPartida
                                                    INNER JOIN USUARIO u ON pu.idUsuario = u.idUsuario
                                                    ORDER BY pu.puntaje DESC
                                                    LIMIT 5;";

                } elseif ($puntaje == "-----" && $tiempoUtilizado != "-----") {
                    $sqlMejoresJugadores = "SELECT u.nombreUsuario, pu.puntaje, TIMEDIFF(p.tiempo, pu.tiempoRestante) AS tiempoUtilizado
                                            FROM partida p 
                                            INNER JOIN PARTIDA_USUARIO pu ON p.idPartida = pu.idPartida
                                            INNER JOIN USUARIO u ON pu.idUsuario = p.ganador
                                            WHERE TIMEDIFF(p.tiempo, pu.tiempoRestante) != '00:00:00'
                                            ORDER BY pu.tiempoRestante DESC, pu.puntaje DESC
                                            LIMIT 5;";
                                            // Se filtra por los ganadores y tiempo utilizado != 0 para evitar las partidas abandonadas desde el inicio
                } elseif ($puntaje != "-----" && $tiempoUtilizado != "-----") {
                    $sqlMejoresJugadores = "SELECT u.nombreUsuario, pu.puntaje, TIMEDIFF(p.tiempo, pu.tiempoRestante) AS tiempoUtilizado
                                            FROM partida p 
                                            INNER JOIN PARTIDA_USUARIO pu ON p.idPartida = pu.idPartida
                                            INNER JOIN USUARIO u ON pu.idUsuario = u.idUsuario
                                            ORDER BY pu.puntaje DESC, pu.tiempoRestante DESC
                                            LIMIT 5;";
                }
    
                $resultadoConsulta = $bd -> consulta($sqlMejoresJugadores);

                $listadoMejoresJugadores = array();
                while ($registro = $resultadoConsulta->fetch_object()) {	
                    $mejorJugador = new Historial();
                    $mejorJugador -> setNombreUsuario1($registro -> nombreUsuario);
                    $mejorJugador -> setPuntaje($registro -> puntaje);

                    $tiempo = $registro -> tiempoUtilizado;
                    [$horas, $minutos, $segundos] = explode(":", $tiempo);
                    $tiempoUtilizado = ($horas * 3600) + ($minutos * 60) + $segundos;
                    $mejorJugador -> setTiempoUtilizado($tiempoUtilizado);

                    $listadoMejoresJugadores[]=$mejorJugador;
                }
    
                $resultadoConsulta->free();
                $bd->cerrarBD();
                error_log('Se buscaron los mejores jugadores');
                return $listadoMejoresJugadores;
    
            } catch (Exception $e) {
                    error_log("Error al buscar a los mejores jugadores " . $e->getMessage());
               }
    }

    public function getMasGanadores($dificultad) {
        try {
            $bd = new BaseDatos();
            $conexion = $bd -> conectarBD();

            if ($dificultad == '-----') {
                $sqlMasGanadores = "SELECT u.nombreUsuario, COUNT(p.idPartida) AS victorias
                                    FROM PARTIDA p 
                                    INNER JOIN USUARIO u ON p.ganador = u.idUsuario
                                    GROUP BY u.nombreUsuario
                                    ORDER BY victorias DESC
                                    LIMIT 5";
            } else {
                $sqlMasGanadores = "SELECT u.nombreUsuario, COUNT(p.idPartida) AS victorias
                                    FROM PARTIDA p 
                                    INNER JOIN USUARIO u ON p.ganador = u.idUsuario
                                    WHERE p.dificultadPartida = '$dificultad'
                                    GROUP BY u.nombreUsuario 
                                    ORDER BY victorias DESC
                                    LIMIT 5;";
            }

            $resultadoConsulta = $bd -> consulta($sqlMasGanadores);

            $listadoVictorias = array();
            while ($registro = $resultadoConsulta->fetch_object()) {	
                $victoria = new Historial();
                $victoria -> setNombreUsuario1($registro -> nombreUsuario);
                $victoria -> setVictorias($registro -> victorias);
                $listadoVictorias[]=$victoria;
            }

            $resultadoConsulta->free();
            $bd->cerrarBD();

            return $listadoVictorias;

        } catch (Exception $e) {
                error_log("Error al buscar a los jugadores mas ganadores: " . $e->getMessage());
           }
}
}
?>