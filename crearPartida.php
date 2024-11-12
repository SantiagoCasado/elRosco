<?php
include_once("php/partida.class.php");
session_start();
//Redirigir si no hay dos sesiones iniciadas
if (!isset($_SESSION['vectorSesion']) || count($_SESSION['vectorSesion']) < 2) {
    $mensaje = 'Deben haber dos usuarios con sesion iniciada';
    $_SESSION['mensaje'] = $mensaje;
    header("location:index.php");
    exit;
    }

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script type='text/javascript'>alert('$mensaje');</script>";
    unset($_SESSION['mensaje']);
}


unset($_SESSION['partida']);
if (isset($_SESSION['partida'])) {
    // Hay partida en juego
    $partida = unserialize($_SESSION['partida']); 
    if ($partida -> getGanador() == null) {
        // No hay ganador, se redirige al juego
        header("location:rosco.php");
        exit;
    } else {
        // Hay ganador, se crea una nueva partida
        unset($_SESSION['partida']);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        El Rosco
    </title>
    <link rel="stylesheet" href="Estilos/estilo.css">
</head>
<body>
    <head>
        <h1>El Rosco</h1>
    </head>
    <section>
        <article>
        <!-- Formulario de Partida -->
        <div class="formularios">
            <form id = "idFormularioPartida" method = "POST" action = "rosco.php">

                <fieldset id = "camposFormulario" class = "camposFormulario">
                    <div class="itemsCentrados"><h2>Configuración de la partida</h2></div>

                    <label class="labelFormulario" for="radioNivelPartida">Seleccioná el <strong>nivel de dificultad</strong></label>
                    <!-- <select id="comboBox" name="comboBoxNivelPartida">
                        <option value="---">---</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select> -->
                    <input type="radio" name = "radioNivelPartida" value="baja" required><span class="radioLabel">Baja</span>
                    <br>
                    <input type="radio" name = "radioNivelPartida" value="media" required><span class="radioLabel">Media</span>
                    <br>
                    <input type="radio" name = "radioNivelPartida" value="alta" required><span class="radioLabel">Alta</span>
                    <br><br>
                    <label class="labelFormulario" for="radioDuracionPartida">Seleccioná el <strong>tiempo</strong></label>
                    <!-- <select id="comboBox" name="comboBoxTiempoPartida">
                        <option value="---">---</option>
                        <option value="2">2 minutos</option>
                        <option value="3">3 minutos</option>
                        <option value="5">5 minutos</option>
                    </select>  -->
                    <input type="radio" name = "radioDuracionPartida" value="2" required><span class="radioLabel">2 minutos</span>
                    <br>
                    <input type="radio" name = "radioDuracionPartida" value="3" required><span class="radioLabel">3 minutos</span>
                    <br>
                    <input type="radio" name = "radioDuracionPartida" value="5" required><span class="radioLabel">5 minutos</span>
                    
                    <br><br>
                    <label class="labelFormulario" for="checkboxAyuda">Marcá esta opción si deseas <strong>ayuda adicional</strong></label>
                    <input type="checkbox" name = "checkboxAyuda[]" value="1"><span class="radioLabel">Ayuda adicional</span>
                    <br><br><br>

                    <div class="itemsCentrados"><button class="botonAceptar" type="submit" name="botonComenzarPartida"><strong>Comenzar a jugar</strong></button></div>
                </fieldset>
            </form>
        </div>
        </article>
    </section>
    <section>
        <article>
            <div class="formularios">
                <form action="index.php" method = "post">
                <div class="itemsCentrados"><button name ="botonCerrarSesion">Cerrar sesión</button></div>
                </form>
            </div>
        </article>
    </section>
    <footer>
        <p>&copy; Final Laboratorio de Programacion y Lenguajes - 2024 - Santiago Casado</p>
    </footer>
</body>
</html>