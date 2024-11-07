<?php
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script type='text/javascript'>alert('$mensaje');</script>";
    unset($_SESSION['mensaje']);
}

//Crear Partida
include_once("php/iniciarPartida.php");
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
        <div class="zonaSeccion">
            <article class="zonaIzquierda" id="idZonaInformacionPartida" class="ZonaInformacionPartida">
                <div class="itemsCentrados"><h2>Información de la partida</h2></div>
                <p><strong>Dificultad: </strong> <?php echo $partida -> getDificultad() ?> </p>
                <p><strong>Tiempo por jugador: </strong> <?php echo $partida -> getTiempoPartida() ?> minutos </p>
                <p><strong>Ayuda adicional: </strong> <?php echo $partida -> getAyuda() == 1 ? "Si" : "No" ?> </p>
                <p><strong>Primero en jugar: </strong> <?php echo $partida -> getTurnoActual() == 0 ? $jugadores[0] -> getNombreUsuario() : $jugadores[1] -> getNombreUsuario() ?></p>
                <p><strong>Turno actual: </strong> <?php echo $partida -> getTurnoActual() == 0 ? $jugadores[0] -> getNombreUsuario() : $jugadores[1] -> getNombreUsuario() ?> </p>        
            </article>
            <article class="zonaDerecha" id="idZonaHistorial">
                <div class="itemsCentrados"><h2>Historal entre <?php echo $jugadores[0] -> getNombreUsuario() ?> y <?php echo $jugadores[1] -> getNombreUsuario() ?> </h2></div>
            </article>
        </div>

    </section>
    <section>
        <article>
            <div id="idZonaInteraccion" class="itemsCentrados">
                <h2>TURNO DE <strong id="idTurnoDe"></strong></h2>
                <div>
                    <form id="idFormularioJuego" class="formularioJuego" method="POST">
                        <!-- formulario generado en JS -->
                    </form>
                </div>
            </div>                
        </article>
        <div class="zonaSeccion">
            <article class="zonaIzquierda" id="idZonaJugador<?php echo $jugadores[0]->getID(); ?>">
                <div class="nombreJugador1">
                    <h1>Rosco de <?php echo $partida -> getJugadores()[0] -> getNombreUsuario() ?></h1>
                </div>
                <div id="idLetrasJugador<?php echo $jugadores[0]->getID(); ?>" class="letras"></div>
            </article>
            
            <article class="zonaDerecha" id="idZonaJugador<?php echo $jugadores[1]->getID(); ?>">
                <div class="nombreJugador2">
                    <h1>Rosco de <?php echo $partida -> getJugadores()[1] -> getNombreUsuario() ?></h1>
                </div>
                <div id="idLetrasJugador<?php echo $jugadores[1]->getID(); ?>" class="letras"></div>
            </article>
        </div>
    </section>

    <section>
        <article>
            <div class="formularios">
                <form action="crearPartida.php" method = "post">
                    <h1>Agregar logica para definir partida y guardar juego en la bd</h1>
                    <div class="itemsCentrados"><button name ="botonAbandonar">Abandonar</button></div>
                </form>
            </div>
        </article>
    </section>
    <script>
        var partidaDatos = <?php echo json_encode($partidaJSON); ?>; // Objeto JSON generado en iniciarPartida.php
        window.onload = function() {
                crearVistaJuego(partidaDatos);
            }
    </script>
    <script src="Script/script.js"></script>
</body>
<footer>
        <p>&copy; Final Laboratorio de Programacion y Lenguajes - 2024 - Santiago Casado</p>
    </footer>
</html>