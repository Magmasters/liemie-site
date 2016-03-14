<?php
session_start ();

include_once ('../class/autoload.php');

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	$errors = array ();
	$data = array ();
	$data ['success'] = false;

	$tab = array ();
	$mypdo = new mypdo ();

	$tab ['email'] = $_POST ['email'];
	$tab ['mdp'] = md5 ( $_POST ['mp'] );
	$tab ['nom'] = $_POST ['nom'];
	$tab ['prenom'] = $_POST ['prenom'];
	$tab ['date_naiss'] = $_POST ['date_naiss'];
	$tab ['tel1'] = $_POST ['tel1'];
	$tab ['tel2'] = $_POST ['tel2'];
	$tab ['tel3'] = $_POST ['tel3'];
	
	//Adresse
	$tab ['num_rue'] = $_POST ['adresse1'];
	$tab ['nom_rue'] = $_POST ['adresse2'];
	$tab ['cp'] = $_POST ['cp'];
	$tab ['ville'] = $_POST ['ville'];
	
	$tab ['lien_photo'] = ''; // Pas de lien photo
	

	$resultat = $mypdo->ajouter_patient ( $tab );
	if (isset ( $resultat ) && $resultat) {
		$data ['success'] = true;
	} else {
		$errors ['message'][0] = 'Erreur lors de l\'ajout du patient ! ';
	}

	if (! empty ( $errors )) {
		$data ['success'] = false;
		$data ['errors'] = $errors;
	} else {
		if ($data ['success']) {
			$data ['message'][0] = "Patient ajouté avec succés, identifiant (email) : ". $_POST['email']." !";
		}
	}
} else {
	$errors ['message'][0] = 'Vous n\'avez pas les droits nécessaires !';
	$data ['errors'] = $errors;
}


echo json_encode ( $data );
?>