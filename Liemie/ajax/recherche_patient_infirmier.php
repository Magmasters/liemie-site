<?php
session_start ();

include_once ('../class/autoload.php');

$data = array ();
$data ['incomplete_results'] = false;

/*
 * Seul un compte ADMIN a l'autorisation de faire une recherche de PATIENT ou INFIRMIER
 */
if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	
	$champ = "NOM";
	$critere = $_POST['q'];
	$debut = $_POST['page']*3;
	$fin = $debut + 3;
	
	$data['items'] = array();
	
	if ($_POST['type'] === "infirmier") {
		rechercher_infirmier($data, $champ, $critere, $debut, $fin);
	} elseif ($_POST['type'] === "patient") {
		rechercher_patient($data, $champ, $critere, $debut, $fin);
	}
}

echo json_encode ( $data );

/*
 * fonction de recherche faisant appel à la classe mypdo (PDO)
 * On utilise la référence vers $data pour y ajouter les données
 */
function rechercher_infirmier(&$data, $champ, $critere, $debut, $fin) {

	$mypdo = new mypdo ();

	$liste_infirmiers = $mypdo->liste_infirmiers($champ, $critere, $debut, $fin);

	$infirmier = array();
	$i = 0;

	if ($liste_infirmiers !== false) {
		while ($objInfirmier = $liste_infirmiers->fetchObject()) {
			$infirmier['id'] = $objInfirmier->ID_INFIRMIER;
			$infirmier['name'] = $objInfirmier->NOM;
			$infirmier['full_name'] = $objInfirmier->NOM." ".$objInfirmier->PRENOM;
			$infirmier['avatar_url'] = "image/infirmier/default.png";
			$infirmier['text'] = "texte";

			$data['items'][$i] = $infirmier;
			$i++;
		}

		$data['total_count'] = $liste_infirmiers->rowCount();
	} else {
		$data['total_count'] = 0;
	}
}

/*
 * fonction de recherche faisant appel à la classe mypdo (PDO)
 * On utilise la référence vers $data pour y ajouter les données
 */
function rechercher_patient(&$data, $champ, $critere, $debut, $fin) {

	$mypdo = new mypdo ();

	$liste_patients = $mypdo->liste_patient($champ, $critere, $debut, $fin);

	$patient = array();
	$i = 0;

	if ($liste_patients !== false) {
		while ($objPatient = $liste_patients->fetchObject()) {
			$patient['id'] = $objPatient->ID_PATIENT;
			$patient['name'] = $objPatient->NOM;
			$patient['full_name'] = $objPatient->NOM." ".$objPatient->PRENOM;
			$patient['avatar_url'] = "image/infirmier/default.png";
			$patient['text'] = "texte";

			$data['items'][$i] = $patient;
			$i++;
		}

		$data['total_count'] = $liste_patients->rowCount();
	} else {
		$data['total_count'] = 0;
	}
}