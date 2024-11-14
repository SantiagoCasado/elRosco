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

    public function getIdInsertado() {
        $sqlUltimoIDInsertado = "SELECT LAST_INSERT_ID()"; // Consulta para obtener el ultimo id generado en la bd
        $resultadoConsulta = $this->consulta($sqlUltimoIDInsertado);
        
        $registro = $resultadoConsulta->fetch_assoc(); 
        
        $idInsertado = $registro['LAST_INSERT_ID()']; 
        
        $resultadoConsulta->free();
        
        return $idInsertado;
    }
    
    
    public function cerrarBD() {
        $this -> conexion -> close();
    }
}
?>