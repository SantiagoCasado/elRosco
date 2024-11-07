<?php
include_once('baseDatos.class.php');
class Pregunta
{
    private $idPregunta;
    private $letra;
    private $palabra;
    private $descripcion;
    private $dificultadPregunta;
    private $estadoRespuesta;


    public function __construct () {
        $this->estadoRespuesta = 'sinResponder';
    }

        public function getIdPregunta() {
            return $this->idPregunta;
        }
    
        public function getLetra() {
            return $this->letra;
        }
    
        public function getPalabra() {
            return $this->palabra;
        }
    
        public function getDescripcion() {
            return $this->descripcion;
        }
    
        public function getDificultadPregunta() {
            return $this->dificultadPregunta;
        }

        public function getEstadoRespuesta() {
            return $this->estadoRespuesta;
        }
    
        public function setIdPregunta($idPregunta) {
            $this->idPregunta = $idPregunta;
        }
    
        public function setLetra($letra) {
            $this->letra = $letra;
        }
    
        public function setPalabra($palabra) {
            $this->palabra = $palabra;
        }
    
        public function setDescripcion($descripcion) {
            $this->descripcion = $descripcion;
        }
    
        public function setDificultadPregunta($dificultadPregunta) {
            $this->dificultadPregunta = $dificultadPregunta;
        }

        public function setEstadoRespuesta($estadoRespuesta) {
            $this->estadoRespuesta = $estadoRespuesta;
        }

    public function getPreguntaBD($letra, $dificultadPregunta, $idPreguntas) {
        try {
			//Buscar Preguntas en la base de datos por letra y dificultad
			$bd = new BaseDatos();
			$conexion = $bd -> conectarBD();
            if ($idPreguntas == '0') {
                $sql = "SELECT * 
                        FROM pregunta 
                        WHERE letra = '$letra' 
                            AND dificultadPregunta = '$dificultadPregunta'
                        ORDER BY RAND()
                        LIMIT 1";
            } else {
                //idPreguntas son los IDs que se encuentran en la sesion
                $sql = "SELECT * 
                        FROM pregunta 
                        WHERE letra = '$letra' 
                            AND dificultadPregunta = '$dificultadPregunta'
                            AND idPregunta NOT IN ($idPreguntas)
                        ORDER BY RAND()
                        LIMIT 1";
            }

			$resultadoConsulta = $bd -> consulta($sql);
		
			if ($registro = $resultadoConsulta->fetch_object()) {
                
                $pregunta = new Pregunta();

                $pregunta -> setIdPregunta($registro -> idPregunta);
                $pregunta -> setLetra($registro -> letra);
                $pregunta -> setDescripcion($registro -> descripcion);
                $pregunta -> setDificultadPregunta($registro -> dificultadPregunta);

			}

			$resultadoConsulta->free();
			$bd->cerrarBD();
			return $pregunta;
		} catch (Exception $e) {
      	  	error_log("Error al buscar la pregunta: " . $e->getMessage());
   		}
    }

    public function actualizarEstadoPregunta($respuesta) {
        if ($this -> palabra == $respuesta) {
            $estadoRespuesta = 'correcto';
        } else {
            $estadoRespuesta = 'incorrecto';
        }
        $this -> setEstadoRespuesta($estadoRespuesta);
        return $estadoRespuesta;
    }

    public function cargarPreguntaBD($idPregunta) {
        //
    }

    // public function verificarRespuesta($idPregunta, $respuesta) {
    //     try {
	// 		//Buscar en la base de datos si la respuesta coincide con la palabra segun el idPregunta
	// 		$bd = new BaseDatos();
	// 		$conexion = $bd -> conectarBD();
    //         $sql = "SELECT palabra 
    //                 FROM pregunta
    //                 WHERE idPregunta = '$idPregunta' AND palabra = '$respuesta'";

	// 		$palabra = $bd -> consulta($sql);
		
    //         $respuestaCorrecta = $palabra->num_rows > 0;

	// 		$palabra->free();
	// 		$bd->cerrarBD();

	// 		return $respuestaCorrecta;
	// 	} catch (Exception $e) {
    //   	  	error_log("Error al buscar la pregunta: " . $e->getMessage());
   	// 	}
    // }
}
?>