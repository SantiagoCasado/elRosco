<?php
include_once("php/usuario.class.php");
session_start();

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script type='text/javascript'>alert('$mensaje');</script>";
    unset($_SESSION['mensaje']);
}

//Redirigir si hay dos sesiones iniciadas
if (isset($_SESSION['vectorSesion']) && count($_SESSION['vectorSesion']) >= 2) {
    header("location:crearPartida.php");
    exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>El Rosco</title>
        <link rel="stylesheet" href="Estilos/estilo.css">
        <!-- <script type="text/javascript"  src="Script/script.js"></script> -->
    </head>
    <header>
        <div class="itemsCentrados">
            <h1>Bienvenido a El Rosco!</h1>
        </div>
    </header>
<body>
    <section>
        <article>
        <?php
            if (isset($_POST['botonRegistro'])) {
                //Obtener los datos ingresados en el formulario
                $nombreUsuario = htmlspecialchars(trim($_POST['nombreUsuario'])); //Evitar inyecciones HTML y espacios
                $correoUsuario = htmlspecialchars(trim($_POST['correoUsuario']));
                $contraseniaUsuario = htmlspecialchars(trim($_POST['contraseniaUsuario']));
                $fechaNacimiento = htmlspecialchars(trim($_POST['fechaNacimiento']));

                $usuario = new Usuario();

                //Verificar si el usuario ya existe
                $usuario -> getUsuario($nombreUsuario);
                if ($usuario -> getID() != null) {
                    //Alertar que el usuario ya existe
                    $mensaje = 'El usuario ya existe';
                    $_SESSION['mensaje'] = $mensaje;

                    //Recargar pagina
                    header("location:registro.php");
                    exit;

                } else {
                    //Guardar en base de datos
                    $mensaje = $usuario -> guardarUsuario($nombreUsuario, $correoUsuario, $contraseniaUsuario, $fechaNacimiento);
                    $_SESSION['mensaje'] = $mensaje;

                    //Guardarlo en la sesion
                    if (!isset($_SESSION['vectorSesion'])) {
                        //Jugador 1
                        $vectorSesion = array();
                    } else {
                        echo '<br>jugador 2';
                        //Jugador 2
                        $vectorSesion = $_SESSION['vectorSesion'];
                    }
                    $usuario -> getUsuario($nombreUsuario);
                    array_push($vectorSesion, $usuario);
                    $_SESSION['vectorSesion'] = $vectorSesion;

                    header("location:index.php");
                    exit;

                }
            } else {
                    ?>   
                    <!--Formulario de Registro-->
                    <div class="formularios">
                        <form id = "idFormularioRegistro" method = "post" action = "registro.php">
            
                            <fieldset id = "camposFormularioRegistro" class = "form">
                                    <div class="itemsCentrados"><h2>Crea una Cuenta</h2></div>
            
                                    <input type="text" id="idNombreUsuario" name="nombreUsuario" placeholder="Nombre Usuario" autofocus required>
                                    <br>
                                    <input type="mail" id="idCorreoUsuario" name="correoUsuario" placeholder="Correo electr&oacute;nico" required>
                                    <br>
                                    <input 
                                        type="password"
                                        id="idContraseniaUsuario" 
                                        name="contraseniaUsuario"                 
                                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[@#$?%*^&+=!-_])(?!.*\s).{8,}$"
                                        title="Mínimo 8 caracteres, una mayúscula, un número y un  caracter especial" 
                                        placeholder="Contrase&ntilde;a" 
                                        required>
                                    <br>
                                    <label class="labelFormulario" for="fechaNacimiento">Fecha de nacimiento</label>
                                    <input type="date" id="idFechaNacimiento" name="fechaNacimiento" title="Ingresa tu fecha de nacimiento" required>
                                    <br><br>


                                    <!-- <br><br>
                                    <input type="checkbox" name = "checkboxRecordarme" value="True"><label for="checkboxRecordarme">Recordarme</label>
                                    -->
                                    <div class="itemsCentrados"><button class="botonAceptar" type="submit" name = "botonRegistro">Registrarse</button></div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="formularios">
                        <div class="itemsCentrados"><button onclick="location.href='index.php'">Ya tengo Cuenta</button></div>
                    </div>

                    <?php 
                }
        ?>
        </article>
    </section>
    <footer>
        <p>&copy; Final Laboratorio de Programacion y Lenguajes - 2024 - Santiago Casado</p>
    </footer>
</body>
</html>