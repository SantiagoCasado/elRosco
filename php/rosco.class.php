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
        $this->preguntasPendientes = array(); // Preguntas sin responder
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

                $mensaje = 'No hay más preguntas para la letra '. $letra;
            }
        }
    }

    public function verificarRespuestaRosco($respuesta) {
        // Obtener la pregunta a evaluar, insertarla en el otro arreglo y actualizarlos
        $preguntasPendientes = $this -> getPreguntasPendientes();
        $pregunta = array_shift($preguntasPendientes);
        $this -> setPreguntasPendientes($preguntasPendientes);
        
        $pregunta -> actualizarEstadoPregunta($respuesta);

        $preguntasArriesgadas = $this -> getPreguntasArriesgadas();
        array_push($preguntasArriesgadas, $pregunta);
        $this -> setPreguntasArriesgadas($preguntasArriesgadas);

        // Fijarse la cantidad de preguntas pendiente (=0 se respondió el rosco completo)
        if (count($preguntasPendientes) == 0) {
            // No quedan preguntas por responder
            $this -> setEstadoRosco('completo');
        }

        // Retornar la pregunta con el estado de respuesta
        return $pregunta;
    }

    public function pasapalabra() {
        // Llevar la primer pregunta del arreglo al final
        $preguntasPendientes = $this -> getPreguntasPendientes();
        $pregunta = array_shift($preguntasPendientes);
        array_push($preguntasPendientes, $pregunta);
        $this -> setPreguntasPendientes($preguntasPendientes);
    }
}
?>