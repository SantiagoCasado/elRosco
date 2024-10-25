<?php
include_once('baseDatos.class.php');
include_once('usuario.class.php');
class Partida {
    private $idPartida;
    private $ganador;
    private $tiempo;
    private $dificultad;
    private $ayuda;
    private $turno;

    public function __construct($idPartida, $ganador, $tiempo, $dificultad, $ayuda, $turno) {
        $this->idPartida = $idPartida;
        $this->ganador = $ganador;
        $this->tiempo = $tiempo;
        $this->dificultad = $dificultad;
        $this->ayuda = $ayuda;
        $this->turno = $turno;
    }

    public function getIdPartida() {
        return $this->idPartida;
    }

    public function setIdPartida($idPartida) {
        $this->idPartida = $idPartida;
    }

    public function getGanador() {
        return $this->ganador;
    }

    public function setGanador($ganador) {
        $this->ganador = $ganador;
    }

    public function getTiempo() {
        return $this->tiempo;
    }

    public function setTiempo($tiempo) {
        $this->tiempo = $tiempo;
    }

    public function getDificultad() {
        return $this->dificultad;
    }

    public function setDificultad($dificultad) {
        $this->dificultad = $dificultad;
    }

    public function getAyuda() {
        return $this->ayuda;
    }

    public function setAyuda($ayuda) {
        $this->ayuda = $ayuda;
    }

    public function getTurno() {
        return $this->turno;
    }

    public function setTurno($turno) {
        $this->turno = $turno;
    }

    public function asignarPrimerTurno() {
        //return $this->turno = random;
    }
}
?>