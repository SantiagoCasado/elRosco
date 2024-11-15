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
            include_once("php/historial.class.php");

            $historial = new Historial();
            $nivelDificultad = $historial -> getDificultad();
            ?>
            <div class="formularioJuego">
                <div class="itemsCentrados"><h2>Mas Ganadores</h2></div>
                <label for="idComboMasGanadores">Selecciona el nivel de dificultad</label><br>
                <select name="comboMasGanadores" id="idComboMasGanadores" onChange="listarMasGanadores();">
                    <option value="-----">Todos los niveles</option>
                    <?php

                    if (count($nivelDificultad)>0) {	
                        foreach($nivelDificultad as $dificultad) {	
                            echo "<option value=" . $dificultad . ">" . $dificultad . "</option>";	
                        }
                    }
                    ?>
                </select>
                <br><br>
                <table id="idMasGanadores" class="tablaHistorial">
                    <th class="celdaHistorialP">Nombre Jugador</th>
                    <th class="celdaHistorialP">Cantidad de Victorias</th>
                </table>
            </div>

        </article>
        <article>
        <div class="formularioJuego">
                <div class="itemsCentrados"><h2>Mejores Jugadores</h2></div>
                <label for="idComboMejoresPuntajes">Selecciona por mayor puntaje y/o tiempo utilizado</label><br>
                <select name="comboMejoresPuntajes" id="idComboMejoresPuntajes" onChange="listaMejoresJugadores();">
                    <option value="-----">-----</option>
                    <option value="puntaje">Con mas puntos</option>
                </select>
                <select name="comboMejoresTiempo" id="idComboMejoresTiempo" onChange="listaMejoresJugadores();">
                    <option value="-----">-----</option>
                    <option value="tiempoUtilizado">Con menor tiempo</option>
                </select>
                <br><br>
                <table id="idMejoresJugadores" class="tablaHistorial">
                    <th class="celdaHistorialP">Nombre Jugador</th>
                    <th class="celdaHistorialP">Puntos</th>
                    <th class="celdaHistorialP">Tiempo Utilizado</th>
                </table>
            </div>
        </article>
    </section>
    <section>
        <article>
            <div class="formularioJuego">
                <form method = "GET" action="index.php">
                    <div class="itemsCentrados">
                        <button type="submit">Volver al inicio</button>
                    </div>
                </form>
            </div>
        </article>
    </section>

    <script src="Script/ranking.js"></script>
</body>
<footer>
        <p>&copy; Final Laboratorio de Programacion y Lenguajes - 2024 - Santiago Casado</p>
    </footer>
</html>