<?php
include_once('pregunta.class.php');
class Rosco
{
    private $idRosco;
    private $estadoRosco;
    private $dificultadRosco;
    private $preguntasPendientes;
    private $preguntasArriesgadas;

    public function __construct($dificultadRosco)
    {
        $this->estadoRosco = 'incompleto';
        $this->preguntasPendientes = array();
        $this->preguntasArriesgadas = array();
        $this->dificultadRosco = $dificultadRosco;

        $this->asignarPreguntas();
    }

    public function getIdRosco() {
        return $this->idRosco;
    }

    public function getEstadoRosco() {
        return $this->estadoRosco;
    }

    public function getPreguntasPendientes() {
        return $this->preguntasPendientes;
    }

    public function getPreguntasArriesgadas() {
        return $this->preguntasArriesgadas;
    }

    public function setIdRosco($idRosco) {
        $this->idRosco = $idRosco;
    }

    public function setEstadoRosco($estadoRosco) {
        $this->estadoRosco = $estadoRosco;
    }

    public function setPreguntasPendientes($preguntasPendientes) {
        $this->preguntasPendientes = $preguntasPendientes;
    }

    public function setPreguntasArriesgadas($preguntasArriesgadas) {
        $this->preguntasArriesgadas = $preguntasArriesgadas;
    }

    public function asignarPreguntas() {
        //Se obtenienen las preguntas para cada letra del abecedario desde la base de datos
        $abecedario = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
            'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];

        foreach ($abecedario as $letra) {

            //Se obtienen los IDs de las preguntas que se encuentran en la sesion para evitar duplicidad
            if (isset($_SESSION['idPreguntas'])) {
            //Arreglo a string
                $idPreguntas = implode(',', $_SESSION['idPreguntas']);
            } else {
            //Si todavia no hay preguntas en la sesion, se le asigna id = 0
                $idPreguntas = '0';
            }

            $pregunta = new Pregunta();
            $pregunta = $pregunta -> getPreguntaBD($letra, $this->dificultadRosco, $idPreguntas);

            if ($pregunta -> getIdPregunta() != null) {
                //string a arreglo
                $idPreguntas = explode(',', $idPreguntas);
                array_push($idPreguntas, $pregunta -> getIdPregunta());

                // Actualizar la sesion con las idPreguntas
                $_SESSION['idPreguntas'] = $idPreguntas;

                // Asignar pregunta al rosco
                array_push($this->preguntasPendientes, $pregunta);            
            } else {

                $mensaje = 'No hay más preguntas para la letra '. $letra; //Ver la manera de usar este mensaje
            }
        }
    }
}
?>