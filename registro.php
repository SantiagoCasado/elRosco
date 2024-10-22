<?php
include_once("php/baseDatos.class.php");
include_once("php/usuario.class.php");
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="iso-8859-1"/>
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
            
            //Redirigir si hay dos sesiones iniciadas
// ]        if (isset($_SESSION['vectorSesion']) && count($_SESSION['vectorSesion']) >= 2) {
//             header("location:index.php");
//             exit;
//         }

            if (isset($_POST['botonRegistro'])) {
                //Obtener los datos ingresados en el formulario
                $nombreUsuario = htmlspecialchars(trim($_POST['nombreUsuario'])); //Evitar inyecciones HTML y espacios
                $correoUsuario = htmlspecialchars(trim($_POST['correoUsuario']));
                $contraseniaUsuario = htmlspecialchars(trim($_POST['contraseniaUsuario']));
                $fechaNacimiento = htmlspecialchars(trim($_POST['fechaNacimiento']));

                $usuario = new Usuario();
                //Verificar si el usuario ya existe
                $existe = $usuario -> existeUsuario($nombreUsuario);
                if ($existe) {
                    //Alertar que el usuario ya existe
                    $mensaje = 'El usuario ya existe';
                    echo $mensaje;

                    //Recargar pagina

                } else {
                    //Guardar en base de datos
                    $mensaje = $usuario -> guardarUsuario($nombreUsuario, $correoUsuario, $contraseniaUsuario, $fechaNacimiento);

                    echo $mensaje;

                }
            } else {
                    ?>   
                    <!--Formulario de Registro-->
                    <div class="formularios">
                        <form id = "idFormularioRegistro" method = "post" action = "registro.php">
            
                            <fieldset id = "camposFormularioRegistro" class = "form">
                                    <h2>Crea una Cuenta</h2>
            
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
                                    <label for="fechaNacimiento">Fecha de nacimiento</label>
                                    <input type="date" id="idFechaNacimiento" name="fechaNacimiento" title="Ingresa tu fecha de nacimiento" required>
                                    <br><br>


                                    <!-- <br><br>
                                    <input type="checkbox" name = "checkboxRecordarme" value="True"><label for="checkboxRecordarme">Recordarme</label>
                                    -->
                                    <button type="submit" name = "botonRegistro">Registrarse</button>
                            </fieldset>
                        </form>
                    </div>
                    <div class="formularios">
                        <button onclick="location.href='index.php'">Ya tengo Cuenta</button>
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