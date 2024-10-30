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
    header("location:index.php");
    exit;
    }

//Crear Partida
if (isset($_POST['botonComenzarPartida'])) {

    //Obtener caracteristicas de la partida
    $dificultad = $_POST['comboBoxNivelPartida'];
    $tiempoPartida = $_POST['comboBoxTiempoPartida'];
    if(isset($_POST['checkboxAyuda'])) {
        $ayudaAdicional = 1;
    } else {
        $ayudaAdicional = 0;
    }
    
    //Obtener los jugadores
    $jugadores = $_SESSION['vectorSesion'];

    //Instancio la partida
    $partida = new Partida($dificultad, $tiempoPartida, $ayudaAdicional, $jugadores);
    echo $partida -> guardarPartidaBD();
    

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
            <h2>Información de la partida</h2>
        </article>
    </section>
    <section>
        <article class='zonaJugador1'>
            <h3>Zona Jugador 1</h3>
        </article>
        <article class='zonaJugador2'>
            <h3>Zona Jugador 2</h3>
        </article>
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
    <footer>
        <p>&copy; Final Laboratorio de Programacion y Lenguajes - 2024 - Santiago Casado</p>
    </footer>
</body>
</html>