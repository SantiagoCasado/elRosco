<?php
include_once("php/usuario.class.php");
session_start();

if (isset($_POST['botonCerrarSesion'])) {
    $_SESSION = array();
    session_destroy();
}

// // $_SESSION = array();
// // session_destroy();

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script type='text/javascript'>alert('$mensaje');</script>";
    unset($_SESSION['mensaje']);
}

// //Mostrarv los usuario con sesion iniciada
// if (isset($_SESSION['vectorSesion'])) {
//     $vectorSesion = $_SESSION['vectorSesion'];
//     foreach ($vectorSesion as $index => $usuario) {
//         echo "<br>" . $index . ' ' . $usuario -> getNombreUsuario();
//     }
//     }

//Redirigir si hay dos sesiones iniciadas
if (isset($_SESSION['vectorSesion']) && count($_SESSION['vectorSesion']) >= 2) {
header("location:crearPartida.php");
exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>El Rosco</title>
        <link rel="stylesheet" href="Estilos/estilo.css">
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

                $usuario = new Usuario();
                       
                //Comprobar si existe
                $usuario -> getUsuario($nombreFormulario);
                if ($usuario -> getID() != null) {

                    //Comprobar si la contrasenia es correcta
                    $contraseniaFormulario = $_POST['contraseniaFormulario'];
                    if ($usuario -> validarContrasenia($contraseniaFormulario)) {

                        if (!isset($_SESSION['vectorSesion'])) {
                            //Jugador 1
                            $vectorSesion = array();
                        } else {
                            //Jugador 2
                            $vectorSesion = $_SESSION['vectorSesion'];

                            //Verificar si el usuario ya inicio sesion
                            $usuarioSesion = $vectorSesion[0];
                            if ($usuarioSesion  -> getNombreUsuario() == $usuario -> getNombreUsuario()) {
                                $mensaje = $usuario -> getNombreUsuario() . ' ya se encuentra con sesión iniciada.';
                                $_SESSION['mensaje'] = $mensaje;
                                
                                header("location:index.php");
                                exit;

                            }
                        }

                        $mensaje = $usuario -> getNombreUsuario() . ' inicio sesión.';
                        $_SESSION['mensaje'] = $mensaje;
                        
                        array_push($vectorSesion, $usuario);
                        $_SESSION['vectorSesion'] = $vectorSesion;
                        
                        //Redirigir segun la cantidad de jugadores en la sesion
                        if(count($vectorSesion) == 2) {
                            header("location:crearPartida.php");
                            exit;
                        } else {
                            header("location:index.php");
                            exit;
                        }
                    } else {
                        //Alertar que el correo o contraseña incorrecto
                        $mensaje = 'Usuario o contrasenia incorrecta';
                        $_SESSION['mensaje'] = $mensaje;

                        //Recargar pagina
                        header("location:index.php");
                        exit;
                    }
                } else {
                    //Usuario no registrado en la bd
                    $mensaje = 'El usuario no se encuentra registrado';
                    $_SESSION['mensaje'] = $mensaje;

                    //Recargar pagina
                    header("location:index.php");
                    exit;
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