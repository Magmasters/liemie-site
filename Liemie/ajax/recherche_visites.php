<?php
session_start ();

include_once ('../class/autoload.php');

$data = array ();
$data ['nombre'] = 0;

if (isset($_SESSION ['type']) && ($_SESSION ['type'] === "admin" || $_SESSION['type'] === "infirmier")) {
	
	$mypdo = new mypdo();
	
	$data['visites'] = array();
	
	$date_debut = $_POST["date_debut"];
	$date_fin = $_POST["date_fin"];
	
	if ($_SESSION['type'] === "infirmier") {
		/*
		 * Pour un (utilisateur connecté) INFIRMIER on ne liste que
		 * les visites dont ID_INFIRMIER est égal à l'ID_INFIRMIER (identifiant stocké dans un cookie de session)
		 */
		$visites = $mypdo->liste_visites($date_debut, $date_fin, $_SESSION ['id_utilisateur']);
	} else {
		$visites = $mypdo->liste_visites($date_debut, $date_fin);
	}
	
	if ($visites !== false) {
		$data ['nombre'] = $visites->rowCount();
		$tab_visite = array();
		while($objVisite = $visites->fetchObject()) {
			$tab_visite['id'] = $objVisite->ID_VISITE;
			
			$infirmier = $mypdo->trouve_infirmier($objVisite->ID_INFIRMIER);
			$patient = $mypdo->trouve_patient($objVisite->ID_PATIENT);
			
			$tab_visite['title'] = "Visite : ".$infirmier->nom." ".$infirmier->prenom;
			$tab_visite['description'] = "Infirmier : ".$infirmier->nom." ".$infirmier->prenom;
			$tab_visite['description'] .= " Patient : ".$patient->nom." ".$patient->prenom;
			$tab_visite['description'] .= " Date : ".$objVisite->DATE_VISITE;
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