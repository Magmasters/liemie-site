<?php
session_start ();

include_once ('../class/autoload.php');

$data = array ();
$data ['nombre'] = 0;

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	
	$mypdo = new mypdo();
	
	$data['visites'] = array();
	
	$visites = $mypdo->liste_visites("2015-01-01", "2018-01-01");
	
	if ($visites !== false) {
		$data ['nombre'] = $visites->rowCount();
		$tab_visite = array();
		while($objVisite = $visites->fetchObject()) {
			$tab_visite['id'] = $objVisite->ID_VISITE;
			$tab_visite['title'] = "Visite";
			$tab_visite['start'] = $objVisite->DATE_VISITE;
			$tab_visite['end'] = $objVisite->DATE_VISITE;
			$tab_visite['allDay'] = false;
			array_push($data['visites'], $tab_visite);
		}
	} else {
		$data['errors'][0] = "Aucune visite";
	}
}

echo json_encode ( $data );