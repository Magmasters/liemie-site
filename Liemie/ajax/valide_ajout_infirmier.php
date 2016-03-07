<?php
session_start ();

include_once ('../class/autoload.php');

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	$errors = array ();
	$data = array ();
	$data ['success'] = false;

	$tab = array ();
	$mypdo = new mypdo ();

	$tab ['identifiant'] = $_POST ['identifiant'];
	$tab ['mdp'] = md5 ( $_POST ['mp'] );
	$tab ['nom'] = $_POST ['nom'];
	$tab ['prenom'] = $_POST ['prenom'];
	$tab ['date_naiss'] = $_POST ['date_naiss'];
	$tab ['lien_photo'] = ''; // Pas de lien photo
	

	$resultat = $mypdo->ajouter_infirmier ( $tab );
	if (isset ( $resultat )) {
		$data ['success'] = true;
	} else {
		$errors ['message'] = 'Identifiant,mot de passe,catégorie invalide ! ' . $tab ['email'];
	}

	if (! empty ( $errors )) {
		$data ['success'] = false;
		$data ['errors'] = $errors;
	} else {
		if ($data ['success']) {
			$data ['message'] = "Vous êtes bien connecté  !";
		}
	}
} else {
	$errors ['message'] = 'Vous n\'avez pas les droits nécessaires !';
	$data ['errors'] = $errors;
}


echo json_encode ( $data );
?>
