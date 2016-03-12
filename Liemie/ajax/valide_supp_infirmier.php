<?php
session_start ();

include_once ('../class/autoload.php');

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	$errors = array ();
	$data = array ();
	$data ['success'] = false;

	$tab = array ();
	$mypdo = new mypdo ();

	$tab_infirmier ['id_infirmier'] = $_POST ['idinfirmier'];
	$tab_infirmier ['id_adresse'] = $_POST ['idadresse'];
	$tab_infirmier ['nom'] = $_POST ['nom'];
	$tab_infirmier ['prenom'] = $_POST ['prenom'];
	

	$resultat = $mypdo->supprimer_infirmier ( $tab_infirmier );
	if (isset ( $resultat ) && $resultat) {
		$data ['success'] = true;
		$data ['message'][0] = "Infirmier ". $_POST['nom']. " " . $_POST['prenom'] . " supprimé avec succés.";
	} else {
		$errors ['message'][0] = 'Erreur lors de la suppression l\'infirmier : '. $_POST['nom']. " " . $_POST['prenom'];
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
