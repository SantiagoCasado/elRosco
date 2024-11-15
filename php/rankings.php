<?php
include_once("historial.class.php");
$historial = new historial();

if (isset($_GET['dificultad'])) {
	$listadoVictorias = $historial -> getMasGanadores($_GET['dificultad']);

	$listaVictoriasJSON = array();
	if (is_null($listadoVictorias) || empty($listadoVictorias)) {	

		$objTemp = new StdClass();
		$objTemp->nombreUsuario = 'No hay jugadores con victorias';
    	$objTemp->victorias = '';
		$listaVictoriasJSON[] = $objTemp;

	} else {	
		foreach ($listadoVictorias as $victoria) {
			$arregloTemp = array('nombreUsuario' => $victoria->getNombreUsuario1(),
						'victorias' => $victoria -> getVictorias()
                	    );
			$listaVictoriasJSON[] = $arregloTemp;
		}
	}
echo json_encode($listaVictoriasJSON);
}

if (isset($_GET['puntaje'], $_GET['tiempoUtilizado'])) {

	$listadoMejoresJugadores = $historial -> getMejoresJugadores($_GET['puntaje'], $_GET['tiempoUtilizado']);

	$listadoMejoresJugadoresJSON = array();
	if (is_null($listadoMejoresJugadores) || empty($listadoMejoresJugadores)) {	

		$objTemp = new StdClass();
		$objTemp->nombreUsuario = 'No hay jugadores buenos';
    	$objTemp->puntaje = '';
		$objTemp->tiempoUtilizado = '';
		$listadoMejoresJugadoresJSON[] = $objTemp;

	} else {	
		foreach ($listadoMejoresJugadores as $mejorJugador) {
			$arregloTemp = array('nombreUsuario' => $mejorJugador->getNombreUsuario1(),
						'puntaje' => $mejorJugador -> getPuntaje(),
						'tiempoUtilizado' => $mejorJugador -> getTiempoUtilizado()
                	    );
			$listadoMejoresJugadoresJSON[] = $arregloTemp;
		}
	}
	echo json_encode($listadoMejoresJugadoresJSON);
}
?>