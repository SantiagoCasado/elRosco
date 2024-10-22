<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Formulario
    </title>
    <link rel="stylesheet" href="Estilos/estilo.css">
</head>
<body>
    <head>
        <h1>Formulario</h1>
    </head>
    <section>
        <?php
        if (!isset($_SESSION['sesionJugador1']) && !isset($_SESSION['sesionJugador2'])) {
            //Redirigir al inicio de sesion
        } else {
            if (!isset($_POST['nombre'], $_POST['comboBox'], $_POST['radio'], $_POST['checkbox'])) {
                echo "<br><p>No hay datos</p> </div>";
        ?>
        <form id = "formularioPartida" method = "get" action = "rosco.php">

            <fieldset id = "camposFormulario" class = "camposFormulario">
                <h2>Configuracion de la Partida</h2>

                <label for="comboBoxNivelPartida">Selecciona el nivel de dificultad de la Partida</label>
                <br>
                <div class = "itemsCentrados">
                <select id="comboBox" name="comboBoxNivelPartida">
                    <option value="---">---</option>
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                </select>
                </div>

                <label for="comboBoxDuracionPartida">Selecciona el tiempo de la Partida</label>
                <br>
                <div class = "itemsCentrados">
                <select id="comboBox" name="comboBoxTiempoPartida">
                    <option value="---">---</option>
                    <option value="2">2 minutos</option>
                    <option value="3">3 minutos</option>
                    <option value="5">5 minutos</option>
                </select>
                </div>

                <br>

                <br><br>    
                <label for="checkboxAyuda">Marca esta opcion si deseas ayuda adicional</label>
                <br>
                    <input type="checkbox" name = "checkboxAyuda[]" value="1">Ayuda adicional

                <br>
                <div class = "itemsCentrados">
                    <button type="reset">Borrar</button>
                    <button type="submit">Comenzar a Jugar</button>
                </div>
            </fieldset>
        </form>
        <?php
            } else {

                // Comienzo de partida
                echo "<p>Nombre: " . $_POST['nombre'] . "<br>";

                echo "<p>Combo: " . $_POST['comboBox'] . "<br>";

                echo "<p>Radio: " . $_POST['radio'] . "<br>";

                $checkbox = $_POST['checkbox'];
                foreach ($checkbox as $opcionSeleccionada) {
                    echo "Opcion: " . htmlspecialchars($opcionSeleccionada) . "<br>";
                }
            }
        }
        ?>
        

    </section>
    <footer>
        <p>&copy; 2024 Casado Santiago</p>
    </footer>
</body>
</html>