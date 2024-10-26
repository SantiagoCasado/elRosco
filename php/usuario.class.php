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
		$bd = new BaseDatos('rosco');
		$bd -> conectarBD();

		// Encriptar la contraseña
		$contraseniaHash = password_hash($contrasenia, PASSWORD_DEFAULT);

		$sql = "INSERT INTO usuario (nombre, correo, contrasenia, fechaNacimiento) 
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
			$bd = new BaseDatos('rosco');
			$conexion = $bd -> conectarBD();
		
			$nombreUsuario = $conexion->real_escape_string($nombreUsuario); //Evita inyecciones SQL

			$sql = "SELECT * 
					FROM usuario 
					WHERE nombre = '$nombreUsuario'";

			$resultadoConsulta = $bd -> consulta($sql);
		
			if ($registro = $resultadoConsulta->fetch_object()) {			
				$this->setID($registro->id);
				$this->setNombreUsuario($registro->nombre);
				$this->setCorreo($registro->correo);
				$this->setContrasenia($registro->contrasenia);
				$this->setFechaNacimiento($registro->fechaNacimiento);

				$resultadoConsulta->free();
				$bd->cerrarBD();
				return True;
			}

			$resultadoConsulta->free();
			$bd->cerrarBD();
			return False;
		} catch (Exception $e) {
      	  	error_log("Error al buscar el usuario: " . $e->getMessage());
   		}
	}

    public function validarContrasenia($nombreUsuario, $contraseniaFormulario) {
        //Se obtiene el usuario a partir de su nombre y si existe se guardan los registros encontados en el propio Objeto
        if ($this->getUsuario($nombreUsuario)) {
            return password_verify($contraseniaFormulario, $this-> contraseniaUsuario);
        }   
        return false;
    }
}
?>