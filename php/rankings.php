<?php
include_once("historial.class.php");
$historial = new historial();

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
?>