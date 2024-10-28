<?php

class BaseDatos
{
    private $servidor;
    private $usuario;
    private $contrasenia;
    private $baseDatos;
    private $errorConexion;
    private $errorConsulta;
    private $conexion;

    public function __construct () {
        $this -> servidor = 'localhost';
        $this -> usuario = 'root';
        $this -> contrasenia = 'root';
        $this -> baseDatos = 'elrosco';
        $this -> errorConexion = 'No se pudo conectar al servidor';
        $this -> errorConsulta = 'No se pudo realizar la consulta';
    }

    public function conexion() {
        return $this -> conexion;
    }

    public function conectarBD() {
        $this -> conexion = new mysqli(
            $this -> servidor,
            $this -> usuario,
            $this -> contrasenia,
            $this -> baseDatos
            ) or die($this -> errorConexion);

            return $this -> conexion;
    }

    public function consulta($sql) {
        $resultado = $this -> conexion -> query($sql);
        if ($resultado === false) {
            return die($this -> errorConsulta);
        }
        return $resultado;
    }


    public function cerrarBD() {
        $this -> conexion -> close();
    }
}
?>