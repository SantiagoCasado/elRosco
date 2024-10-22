<?php
include_once('baseDatos.class.php');
class Usuario
{
	private $idUsuario; //Autoincremental en la bd
	private $nombreUsuario;
	private $correoUsuario;
	private $contraseniaUsuario;
	private $fechaNacimientoUsuario;
	
	public function __construct()
	{								}

	public function getID() {
		return $this->idUsuario;
	}

	public function setID($id) {
		$this -> idUsuario = $id;
	}

	public function getNombreUsuario() {
		return $this -> nombreUsuario;
	}

	public function setNombreUsuario($nombreUsuario) {
		$this -> nombreUsuario = $nombreUsuario;
	}

	public function getCorreo()
	{	return $this -> correoUsuario;	}
	
	public function setCorreo($correo) {	
        $this -> correoUsuario	= $correo;
    }

	public function getContrasenia()
	{	return $this -> contraseniaUsuario;	}
	
	public function setContrasenia($contrasenia) {
		$this -> contraseniaUsuario = $contrasenia;
	}

	public function getFechaNacimiento()
	{	return $this -> fechaNacimientoUsuario;	}
	
	public function setFechaNacimiento($fechaNacimiento) {
		$this -> fechaNacimientoUsuario = $fechaNacimiento;
	}

	private function buscarUsuario($nombreUsuario) {
		try {
			//Buscar usuario en la base de datos
			$bd = new BaseDatos('rosco');
			$conexion = $bd -> conectarBD();
		
			$nombreUsuario = $conexion->real_escape_string($nombreUsuario); //Evita inyecciones SQL

			$sql = "SELECT * 
					FROM usuario 
					WHERE nombre = '$nombreUsuario'";

			$resultadoConsulta = $bd -> consulta($sql);
		
			if ($registro = $resultadoConsulta->fetch_object()) {
				$resultadoConsulta->free();
				$bd->cerrarBD();
				return $registro;
			}

			$resultadoConsulta->free();
			$bd->cerrarBD();
			return null;

		} catch (Exception $e) {
      	  	error_log("Error al buscar el usuario: " . $e->getMessage());
      	  	return false;
   		}
	}

    public function existeUsuario($nombreUsuario) {
        $registro = $this->buscarUsuario($nombreUsuario);
        return $registro !== null; // Retorna true si existe, false si no
    }

	public function getUsuario($nombreUsuario) {

		//Si existe se asigna los atributos encontrado al Objeto Usuario
		if ($registro = $this-> buscarUsuario($nombreUsuario)) {
			$this->setID($registro->id);
			$this->setNombreUsuario($registro->nombre);
			$this->setCorreo($registro->correo);
			$this->setContrasenia($registro->contrasenia);
			$this->setFechaNacimiento($registro->FechaNacimiento);
		}
		
	}

	// public function getUsuario($nombreUsuario) {
		
	// 	try {
	// 		//Buscar usuario en la base de datos
	// 		$bd = new BaseDatos('rosco');
	// 		$conexion = $bd -> conectarBD();
		
	// 		$nombreUsuario = $conexion->real_escape_string($nombreUsuario); //Evita inyecciones SQL

	// 		$sql = "SELECT * 
	// 				FROM usuario 
	// 				WHERE nombre = '$nombreUsuario'";

	// 		$resultadoConsulta = $bd -> consulta($sql);
	
	// 		$encontrado = false;
		
	// 		//Si existe se asigna los atributos encontrado al Objeto Usuario
	// 		if ($registro = $resultadoConsulta->fetch_object()) {
	// 			$encontrado = true;

	// 			$this->setID($registro->id);
	// 			$this->setNombreUsuario($registro->nombre);
	// 			$this->setCorreo($registro->correo);
	// 			$this->setContrasenia($registro->contrasenia);
	// 			$this->setFechaNacimiento($registro->FechaNacimiento);
	// 		}
		
	// 		$resultadoConsulta->free();
	// 		$bd->cerrarBD();

	// 		return $encontrado;

	// 	} catch (Exception $e) {
    //   	  	error_log("Error al buscar el usuario: " . $e->getMessage());
    //   	  	return false;
   	// 	}
	// }

	public function guardarUsuario($nombre, $correo, $contrasenia, $fechaNacimiento) {
		$bd = new BaseDatos('rosco');
		$conexion = $bd -> conectarBD();

		$sql = "INSERT INTO usuario (nombre, correo, contrasenia, fechaNacimiento) 
                VALUES ('$nombre', '$correo', '$contrasenia', '$fechaNacimiento')";    
		
		$resultadoConsulta = $bd ->consulta($sql);

		if ($resultadoConsulta) {
			return $mensaje = 'Usuario registrado exitosamente.';
		} else {
			return $mensaje = 'Error: no se pudo registrar al usuario.';
		}
		
		$bd->cerrarBD();

		return $mensaje;
	}
}
?>