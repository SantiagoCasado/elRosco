<?php
include_once("php/partida.class.php");
session_start();

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script type='text/javascript'>alert('$mensaje');</script>";
    unset($_SESSION['mensaje']);
}

//Redirigir si no hay dos sesiones iniciadas
if (!isset($_SESSION['vectorSesion']) || count($_SESSION['vectorSesion']) < 2) {
    $mensaje = 'Deben haber dos usuarios con sesion iniciada';
    $_SESSION['mensaje'] = $mensaje;
    header("location:index.php");
    exit;
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
        <article id="idZonaInformacionPartida" class="ZonaInformacionPartida">
            <h2>Información de la partida</h2>
            <p><strong>Dificultad: </strong> <?php echo $partida -> getDificultad() ?> </p>
            <p><strong>Tiempo por jugador: </strong> <?php echo $partida -> getTiempoPartida() ?> minutos </p>
            <p><strong>Ayuda Adicional: </strong> <?php echo $partida -> getAyuda() == 1 ? "Si" : "No" ?> </p>
            <p><strong>Turno Actual: </strong> <?php echo $partida -> getTurnoActual() == 0 ? $jugadores[0] -> getNombreUsuario() : $jugadores[1] -> getNombreUsuario() ?> </p>
        </article>

        <div class="zonaJugadores">
            <article class="zonaJugador1" id="idZonaJugador<?php echo $jugadores[0]->getID(); ?>">
                <div class="nombreJugador">
                    <div class="nombreJugador1">
                        <h1>Rosco de <?php echo $partida -> getJugadores()[0] -> getNombreUsuario() ?></h1>
                    </div>
                </div>
                <div id="idLetrasJugador<?php echo $jugadores[0]->getID(); ?>" class="letras"></div>
            </article>
            <article class="zonaJugador2" id="idZonaJugador<?php echo $jugadores[1]->getID(); ?>">
                <div class="nombreJugador">
                    <div class="nombreJugador2">
                        <h1>Rosco de <?php echo $partida -> getJugadores()[1] -> getNombreUsuario() ?></h1>
                    </div>
                </div>
                <div id="idLetrasJugador<?php echo $jugadores[1]->getID(); ?>"></div>
            </article>
        </div>

    </section>
    <section>
    </section>
    <section>
        <article>
            <div class="formularios">
                <form action="crearPartida.php" method = "post">
                    <h1>Agregar logica para definir partida y guardar juego en la bd</h1>
                <button name ="botonCerrarSesion">Abandonar</button>
                </form>
            </div>
        </article>
    </section>
    <script>
        // Objeto JSON generado en iniciarPartida.php
        var roscosData = <?php echo json_encode($roscosJSON); ?>;
        window.onload = function() {
                crearVistaRosco(roscosData);
            }
    </script>
    <script src="Script/script.js"></script>
</body>
<footer>
        <p>&copy; Final Laboratorio de Programacion y Lenguajes - 2024 - Santiago Casado</p>
    </footer>
</html>