<?php
session_start ();

include_once ('../class/autoload.php');

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	$errors = array ();
	$data = array ();
	$data ['success'] = false;

	$tab = array ();
	$mypdo = new mypdo ();

	$tab_patient ['id_patient'] = $_POST ['idpatient'];
	$tab_patient ['id_adresse'] = $_POST ['idadresse'];
	$tab_patient ['nom'] = $_POST ['nom'];
	$tab_patient ['prenom'] = $_POST ['prenom'];
	

	$resultat = $mypdo->supprimer_patient ( $tab_patient );
	if (isset ( $resultat ) && $resultat) {
		$data ['success'] = true;
		$data ['message'][0] = "Patient ". $_POST['nom']. " " . $_POST['prenom'] . " supprimé avec succés.";
	} else {
		$errors ['message'][0] = 'Erreur lors de la suppression du patient : '. $_POST['nom']. " " . $_POST['prenom'];
	}
	
	if (! empty ( $errors )) {
		$data ['success'] = false;
		$data ['errors'] = $errors;
	}
} else {
	$errors ['message'] = 'Vous n\'avez pas les droits nécessaires !';
	$data ['errors'] = $errors;
}


echo json_encode ( $data );
?>
