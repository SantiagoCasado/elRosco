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

	public function guardarUsuario($nombre, $correo, $contrasenia, $fechaNacimiento) {
		$bd = new BaseDatos();
		$bd -> conectarBD();

		// Encriptar la contraseña
		$contraseniaHash = password_hash($contrasenia, PASSWORD_DEFAULT);

		$sql = "INSERT INTO usuario (nombreUsuario, correoUsuario, contrasenia, fechaNacimiento) 
                VALUES ('$nombre', '$correo', '$contraseniaHash', '$fechaNacimiento')";    
		
		$resultadoConsulta = $bd ->consulta($sql);

		if ($resultadoConsulta) {
			$bd->cerrarBD();
			return $mensaje = 'Usuario registrado exitosamente.';
		} else {
			$bd->cerrarBD();
			return $mensaje = 'Error: no se pudo registrar al usuario.';
		}
	}

	public function getUsuario($nombreUsuario) {
		try {
			//Buscar usuario en la base de datos
			$bd = new BaseDatos();
			$conexion = $bd -> conectarBD();
		
			$nombreUsuario = $conexion->real_escape_string($nombreUsuario); //Evita inyecciones SQL

			$sql = "SELECT * 
					FROM USUARIO 
					WHERE nombreUsuario = '$nombreUsuario'";

			$resultadoConsulta = $bd -> consulta($sql);
		
			if ($registro = $resultadoConsulta->fetch_object()) {			
				$this->setID($registro->idUsuario);
				$this->setNombreUsuario($registro->nombreUsuario);
				$this->setCorreo($registro->correoUsuario);
				$this->setContrasenia($registro->contrasenia);
				$this->setFechaNacimiento($registro->fechaNacimiento);

			}

			$resultadoConsulta->free();
			$bd->cerrarBD();

		} catch (Exception $e) {
			$bd->cerrarBD();
      	  	error_log("Error al buscar el usuario: " . $e->getMessage());
   		}
	}

    public function validarContrasenia($contraseniaFormulario) {
		return password_verify($contraseniaFormulario, $this-> contraseniaUsuario);
    }

}
?>