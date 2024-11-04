<?php
include_once('baseDatos.class.php');
include_once('usuario.class.php');
class Historial
{
    private $usuarios;

    public function __construct ($usuarios) {
        $this -> usuarios = $usuarios;
    }

    public function getHistorial() {
        // Historial
    }
}
?>