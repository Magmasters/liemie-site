<?php
session_start ();

include_once ('../class/autoload.php');

$data = array ();

if (isset($_SESSION ['type']) && ($_SESSION ['type'] === "admin" || $_SESSION['type'] === "infirmier")) {
	
	$mypdo = new mypdo();
	
	/*
	 * On regarde si la requete attend en réponse une liste de visites ou
	 * une visite spécifique (dont l'ID_VISITE - $_POST['idvisite'] - est fourni par la page
	 * effectuant la requete
	 * 
	 * Si "$_POST['idvisite']" n'est pas définit on renvoit une liste de visites
	 * dans l'intervalle - $_POST["date_debut"] - $_POST["date_fin"] -
	 */
	if (isset($_POST['idvisite'])) {
		
		$idvisite = $_POST['idvisite'];
		
		$data['visite'] = array();
		
		if ($_SESSION['type'] === "infirmier") {
			/*
			 * Pour un (utilisateur connecté) INFIRMIER on ne liste que
			 * les visites dont ID_INFIRMIER est égal à l'ID_INFIRMIER (identifiant stocké dans un cookie de session)
			 */
			$visite = $mypdo->infos_visite($idvisite, $_SESSION['id_utilisateur']);
		} else {
			$visite = $mypdo->infos_visite($idvisite);
		}
		
		if ($visite !== false) {
			
			$tab_visite = array();
			$objVisite = $visite->fetchObject();
			
			$tab_visite['id'] = $objVisite->ID_VISITE;
				
			//"trouve_infirmier" & "trouve_patient" Renvoient des objets de classe "Infirmier" & "Patient"
			$infirmier = $mypdo->trouve_infirmier($objVisite->ID_INFIRMIER);
			$patient = $mypdo->trouve_patient($objVisite->ID_PATIENT);
					
			$tab_visite['title'] = "Visite : ".$infirmier->nom." ".$infirmier->prenom;
			$tab_visite['description'] = "Infirmier : ".$infirmier->nom." ".$infirmier->prenom;
			$tab_visite['description'] .= " Patient : ".$patient->nom." ".$patient->prenom;
			$tab_visite['description'] .= " Date : ".$objVisite->DATE_VISITE;
			$tab_visite['start'] = $objVisite->DATE_VISITE;
			$tab_visite['end'] = $objVisite->DATE_VISITE;
			$tab_visite['allDay'] = false;
			
			$data['visite'] = $tab_visite;
				
		} else {
			$data['errors'][0] = "Visite non trouvée";
		}
	}
	else
	{
		$data ['nombre'] = 0;
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

}

echo json_encode ( $data );