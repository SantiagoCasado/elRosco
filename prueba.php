<?php

require_once 'BaseDatos.php';  // Asegúrate de tener tu clase BaseDatos incluida

class Usuario {
    // Atributos privados
    private $idUsuario;
    private $nombreUsuario;
    private $correoUsuario;
    private $contraseniaUsuario;
    private $fechaNacimientoUsuario;
    private $db;  // Instancia de BaseDatos

    // Constructor: Inicializa la conexión con la base de datos
    public function __construct() {
        $this->db = new BaseDatos('tu_base_de_datos');
    }

    // Método para guardar un usuario en la base de datos
    public function guardarUsuario($nombre, $correo, $contrasenia, $fechaNacimiento) {
        // Encripta la contraseña
        $hash = password_hash($contrasenia, PASSWORD_DEFAULT);

        $this->db->conectarBD();

        $sql = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasenia, fecha_nacimiento)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->conexion()->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $correo, $hash, $fechaNacimiento);

        if ($stmt->execute()) {
            echo "Usuario registrado exitosamente.";
        } else {
            echo "Error al registrar usuario: " . $stmt->error;
        }

        $stmt->close();
        $this->db->cerrarBD();
    }

    // Método para verificar si un usuario ya existe por su nombre
    public function existeUsuario($nombreUsuario) {
        $usuario = $this->getUsuario($nombreUsuario);
        return $usuario !== null;  // Devuelve true si el usuario existe
    }

    // Método para obtener un usuario por nombre
    public function getUsuario($nombreUsuario) {
        $this->db->conectarBD();

        $sql = "SELECT id_usuario, nombre_usuario, correo_usuario, contrasenia, fecha_nacimiento 
                FROM usuarios WHERE nombre_usuario = ?";
        
        $stmt = $this->db->conexion()->prepare($sql);
        $stmt->bind_param("s", $nombreUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $usuario = $resultado->num_rows > 0 ? $resultado->fetch_assoc() : null;

        $stmt->close();
        $this->db->cerrarBD();

        return $usuario;
    }

    // Método para validar la contraseña ingresada desde un formulario
    public function validarContrasenia($nombreUsuario, $contraseniaFormulario) {
        $usuario = $this->getUsuario($nombreUsuario);
        
        if ($usuario) {
            return password_verify($contraseniaFormulario, $usuario['contrasenia']);
        }
        
        return false;  // Si el usuario no existe o la contraseña es incorrecta
    }
}
?>