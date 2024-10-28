<?php
include_once('baseDatos.class.php');
class Pregunta
{
    private $idPregunta;
    private $letra;
    private $palabra;
    private $descripcion;
    private $dificultadPregunta;


    public function __construct () {
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

    public function getPreguntaBD($letra, $dificultadPregunta, $idPreguntas) {
        try {
			//Buscar Preguntas en la base de datos por letra y dificultad
			$bd = new BaseDatos();
			$conexion = $bd -> conectarBD();

			//idPreguntas son los IDs que se encuentran en la sesion
            $sql = "SELECT * 
					FROM pregunta 
					WHERE letra = '$letra' 
                        AND dificultad = '$dificultadPregunta'
                        AND IS NOT IN idPregunta = ($idPreguntas)";

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

    public function verificarRespuesta($respuesta) {
        //
    }
}
?>