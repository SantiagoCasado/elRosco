<?php
include_once("php/usuario.class.php");
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="iso-8859-1" />
        <title>El Rosco</title>
        <link rel="stylesheet" href="Estilos/estilo.css">
        <!-- <script type="text/javascript"  src="Script/script.js"></script> -->
    </head>
    <header>
        <div class="itemsCentrados">
            <h1>El Rosco</h1>
        </div>
    </header>
<body>
    <section>
        <article>
        <?php
            if (isset($_POST['botonInicio'])) {
                $nombreFormulario = $_POST['nombreFormulario'];
                $contraseniaFormulario = $_POST['contraseniaFormulario'];

                $usuario = new Usuario();

                //Buscar usuario de la bd
                $usuario -> getUsuario($nombreFormulario);
                       
                //Comprobar si existe y si los datos ingresados son validos
                if ($usuario -> getID() != null) {
                    echo 'existe el usuario';
                    $contraseniaUsuario = $usuario -> getContrasenia();
                    
                    if ($contraseniaFormulario == $contraseniaUsuario) {
                        echo 'contrasenia valida';
                        if (!isset($_SESSION['vectorSesion'])) {
                            echo 'jugador1';
                            //Jugador 1
                            $vectorSesion = array();
                        } else {
                            echo 'jugador 2';
                            //Jugador 2
                            $vectorSesion = $_SESSION['vectorSesion'];
                        }
                        
                        array_push($vectorSesion, $usuario);
                        foreach ($vectorSesion as $usuario1) {
                                echo "<p>" . $usuario1 -> getNombreUsuario() . "</p>";
                        }
                        $_SESSION['vectorSesion'] = $vectorSesion;

                        if(count($vectorSesion) == 2) {
                            //Los dos jugadores iniciaron la sesion
                            // Formulario de inicio de partida
                            echo "formulario de inicio de partida";
                        } else {
                            //Un jugador inicio la partida
                            echo 'un jugador inicio la partida';
                            //Recargar Pagina
                            // header("location:index.php");
                            // exit;
                        }
                    } else {
                        //Alertar que el correo o contraseña incorrecto
                        $mensaje = 'Correo o contrasenia incorrecta';
                        echo $mensaje;
                        //Recargar pagina
                        // header("location:index.php");
                        // exit;
                    }
                } else {
                    //Usuario no registrado en la bd

                    $mensaje = 'El usuario no se encuentra registrado';
                    echo $mensaje;

                    //Recargar pagina
                    // header("location:index.php");
                    // exit;
                }
            } else {
                ?>
                <!--Formulario de Inicio de Sesion-->
                <div class="formularios">
                    <form id = "idFormularioIngreso" method = "post" action = "index.php">  
                        <fieldset id = "camposFormularioIngreso" class = "form">
                        
                            <h2>Ingresa a tu Cuenta</h2>
        
                            <input type="text" id="idNombreUsuarioIngreso" name="nombreFormulario" placeholder="Nombre Usuario" autofocus required>
                            <br>
                            <input 
                                type="password"
                                id="idContraseniaUsuarioIngresi" 
                                name="contraseniaFormulario"                 
                                placeholder="Contrase&ntilde;a" 
                                required>
                            <br><br>

                            <!-- Darle funcionalidad  -->
                            <!-- <input type="checkbox" name = "checkboxRecordarme" value="True"><label for="checkboxRecordarme">Recordarme</label>
                            <br><br> -->
                        
                            <button type="submit" name = "botonInicio">Ingresar</button>
                        
                        </fieldset>
                    </form>
                </div>
                <div class="formularios">
                    <button onclick="location.href='registro.php'">Registrarse</button>
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