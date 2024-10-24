<?php
include_once("php/partida.class.php");
session_start();

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
        <h1>Configuraci&oacute;n de Partida</h1>
    </head>
    <section>
        <?php
            if (!isset($_POST['nombre'], $_POST['comboBox'], $_POST['radio'], $_POST['checkbox'])) {
                echo "<br><p>No hay datos</p> </div>";
        ?>
        <form id = "formulario" method = "post" action = "formulario.php">

            <fieldset id = "camposFormulario" class = "camposFormulario">
                <h2>Formulario</h2>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                
                <br><br>
                <label for="comboBox">Selecciona una opción del Combo:</label>
                <br>
                <div class = "itemsCentrados">
                <select id="comboBox" name="comboBox">
                    <option value="---">---</option>
                    <option value="opcion1">Opción Uno</option>
                    <option value="opcion2">Opción Dos</option>
                    <option value="opcion3">Opción Tres</option>
                </select>
                </div>

                <br>
                <label for="radio">Selecciona una opción del Radio:</label>
                <br>
                    <input type="radio" name = "radio" value="opcion1">Opcion 1
                    <br>
                    <input type="radio" name = "radio" value="opcion2">Opcion 2
                    <br>
                    <input type="radio" name = "radio" value="opcion3">Opcion 3

                <br><br>    
                <label for="checkbox">Selecciona una opción del checkbox:</label>
                <br>
                    <input type="checkbox" name = "checkbox[]" value="opcion1">Opcion 1
                    <br>
                    <input type="checkbox" name = "checkbox[]" value="opcion2">Opcion 2
                    <br>
                    <input type="checkbox" name = "checkbox[]" value="opcion3">Opcion 3

                <br>
                <div class = "itemsCentrados">
                    <button type="reset">Borrar</button>
                    <button type="submit">Enviar</button>
                </div>
            </fieldset>
        </form>
        <?php
            } else {
                echo "<p>Nombre: " . $_POST['nombre'] . "<br>";

                echo "<p>Combo: " . $_POST['comboBox'] . "<br>";

                echo "<p>Radio: " . $_POST['radio'] . "<br>";

                $checkbox = $_POST['checkbox'];
                foreach ($checkbox as $opcionSeleccionada) {
                    echo "Opcion: " . htmlspecialchars($opcionSeleccionada) . "<br>";
                }
            }
        ?>

    </section>
    <footer>
        <p>&copy; 2024 Casado Santiago</p>
    </footer>
</body>
</html>