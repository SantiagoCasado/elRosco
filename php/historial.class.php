<?php
include_once('baseDatos.class.php');

class Historial
{
    private $idUsuario1;
    private $idUsuario2;
    private $nombreUsuario1;
    private $nombreUsuario2;
    private $victoriasJugador1;
    private $victoriasJugador2;

    public function __construct ($idUsuario1, $idUsuario2) {
        $this -> idUsuario1 = $idUsuario1;
        $this -> idUsuario2 = $idUsuario2;
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

    public function getHistorial() {
        try {
			$bd = new BaseDatos();
			$conexion = $bd -> conectarBD();

            $idUsuario1 = $this -> getUsuario1();
            $idUsuario2 = $this -> getUsuario2();
            
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
}
?>