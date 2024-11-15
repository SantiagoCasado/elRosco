<?php
include_once('php/iniciarPartida.php');
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
                <p><strong>Tiempo por jugador: </strong> <?php echo $partida -> getTiempoPartida() / 60 ?> minutos </p>
                <p><strong>Ayuda adicional: </strong> <?php echo $partida -> getAyuda() == 1 ? "Si" : "No" ?> </p>
                <!-- <p><strong>Primero en jugar: </strong> <?php //echo $partida -> getTurnoActual() == 0 ? $jugadores[0] -> getNombreUsuario() : $jugadores[1] -> getNombreUsuario() ?></p>
                <p><strong>Turno actual: </strong> <?php //echo $partida -> getTurnoActual() == 0 ? $jugadores[0] -> getNombreUsuario() : $jugadores[1] -> getNombreUsuario() ?> </p>         -->
            </article>
            <article class="zonaDerecha" id="idZonaHistorial">
                <div class="itemsCentrados">
                    <h2>Historial entre <?php echo $historial -> getNombreUsuario1() ?> y <?php echo $historial -> getNombreUsuario2() ?> </h2>
                    <table id="idTablaHistorial" class="tablaHistorial">
                        <thead>
                            <tr>
                                <th colspan="4" rowspan="2" class="celdaHistorialP">Detalle de la Partida</th>
                                <th colspan="2" class="celdaHistorialJ1"><?php echo $historial -> getNombreUsuario1() == $jugadores[0] -> getNombreUsuario() ? $historial -> getNombreUsuario1() : $historial -> getNombreUsuario2() ?></th>
                                <th colspan="2" class="celdaHistorialJ2"><?php echo $historial -> getNombreUsuario2() == $jugadores[1] -> getNombreUsuario() ? $historial -> getNombreUsuario2() : $historial -> getNombreUsuario1() ?></th>
                            </tr>
                            <tr>
                                <td colspan="2" class="celdaHistorialD"><?php echo $historial -> getNombreUsuario1() == $jugadores[0] -> getNombreUsuario() ? $historial -> getVictoriasJugador1() : $historial -> getVictoriasJugador2() . ' victorias' ?></td>
                                <td colspan="2" class="celdaHistorialD"><?php echo $historial -> getNombreUsuario2() == $jugadores[1] -> getNombreUsuario() ? $historial -> getVictoriasJugador2() : $historial -> getVictoriasJugador1() . ' victorias' ?></td>
                            </tr>
                            <tr>
                                <th class="celdaHistorialH">Ganador</th>
                                <th class="celdaHistorialH">Dificultad</th>
                                <th class="celdaHistorialH">Tiempo</th>
                                <th class="celdaHistorialH">Ayuda</th>
                                <th class="celdaHistorialH">Puntos</th>
                                <th class="celdaHistorialH">Tiempo</th>
                                <th class="celdaHistorialH">Puntos</th>
                                <th class="celdaHistorialH">Tiempo</th>
                            </tr>
                        </thead>
                        <tbody id="idTablaHistorialBody">
                        </tbody>

                    </table>
            
                </div>
            </article>
        </div>

    </section>
    <section>
        <article>
            <div id="idZonaInteraccion" class="itemsCentrados">
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
                    <h1><?php echo $partida -> getJugadores()[0] -> getNombreUsuario() ?></h1>
                
                    <table class="tablaEstadoJugador">
                            <tr>
                                <td class="celdaEstadoJugador" id="tiempoJugador<?php echo $jugadores[0]->getID(); ?>"><?php echo $partida -> getTiempoPartida(); ?></td>
                                <td class="celdaEstadoJugador" id="puntajeJugador<?php echo $jugadores[0]->getID(); ?>"><?php echo $partida -> getPuntajes()[$jugadores[0]->getID()]; ?></td>
                            </tr>
                    </table>
                </div>
                <div id="idLetrasJugador<?php echo $jugadores[0]->getID(); ?>" class="letrasRosco"></div>
            </article>
            
            <article class="zonaDerecha" id="idZonaJugador<?php echo $jugadores[1]->getID(); ?>">
                <div class="nombreJugador2">
                    <h1><?php echo $partida -> getJugadores()[1] -> getNombreUsuario() ?></h1>
                    <table class="tablaEstadoJugador">
                            <tr>
                                <td class="celdaEstadoJugador" id="tiempoJugador<?php echo $jugadores[1]->getID(); ?>"><?php echo $partida -> getTiempoPartida(); ?></td>
                                <td class="celdaEstadoJugador" id="puntajeJugador<?php echo $jugadores[1]->getID(); ?>"><?php echo $partida -> getPuntajes()[$jugadores[1]->getID()]; ?></td>
                            </tr>
                    </table>
                </div>
                <div id="idLetrasJugador<?php echo $jugadores[1]->getID(); ?>" class="letras"></div>
            </article>
        </div>
    </section>

    <section>
        <article>
            <div class="formularios">
                <form method = "POST">
                    <!-- <h1>Agregar logica para definir partida y guardar juego en la bd</h1> -->
                    <div id="idAbandonar" class="itemsCentrados"></div>
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