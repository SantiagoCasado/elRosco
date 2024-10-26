<?php
include_once("php/partida.class.php");
session_start();
//Redirigir si no hay dos sesiones iniciadas
if (!isset($_SESSION['vectorSesion']) || count($_SESSION['vectorSesion']) < 2) {
header("location:index.php");
exit;
}

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script type='text/javascript'>alert('$mensaje');</script>";
    unset($_SESSION['mensaje']);
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
            <form id = "idFormularioPartida" method = "post" action = "rosco.php">

                <fieldset id = "camposFormulario" class = "camposFormulario">
                    <h2>Configuracion de la Partida</h2>

                    <label for="comboBoxNivelPartida">Selecciona el nivel de dificultad de la Partida</label>
                    <select id="comboBox" name="comboBoxNivelPartida">
                        <option value="---">---</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>

                    <label for="comboBoxDuracionPartida">Selecciona el tiempo de la Partida</label>
                    <select id="comboBox" name="comboBoxTiempoPartida">
                        <option value="---">---</option>
                        <option value="2">2 minutos</option>
                        <option value="3">3 minutos</option>
                        <option value="5">5 minutos</option>
                    </select> 
                    
                    <label for="checkboxAyuda">Marca esta opcion si deseas ayuda adicional</label>
                    <input type="checkbox" name = "checkboxAyuda[]" value="1">Ayuda adicional
                    <br><br><br>

                    <button type="submit" name="botonComenzarPartida">Comenzar a Jugar</button>
                    <br><br> 
                    <button type="reset">Borrar</button>
                </fieldset>
            </form>
        </div>
        </article>
    </section>
    <section>
        <article>
            <div class="formularios">
                <form action="index.php" method = "post">
                <button name ="botonCerrarSesion">Cerrar Sesión</button>
                </form>
            </div>
        </article>
    </section>
    <footer>
        <p>&copy; Final Laboratorio de Programacion y Lenguajes - 2024 - Santiago Casado</p>
    </footer>
</body>
</html>