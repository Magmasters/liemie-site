<?php
session_start ();

include_once ('../class/autoload.php');

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	$errors = array ();
	$data = array ();
	$data ['success'] = false;

	$tab = array ();
	$mypdo = new mypdo ();

	$tab ['type'] = $_POST ['type_article'];
	$tab ['contenu'] = $_POST ['contenu'];
	$tab ['titre'] = $_POST ['titre'];
	

	$resultat = $mypdo->ajouter_article ( $tab );
	if (isset ( $resultat ) && $resultat) {
		$data ['success'] = true;
	} else {
		$errors ['message'][0] = 'Erreur lors de l\'ajout de l\'article ! ';
	}

	if (! empty ( $errors )) {
		$data ['success'] = false;
		$data ['errors'] = $errors;
	} else {
		if ($data ['success']) {
			$data ['message'][0] = "Article ajouté avec succés !";
		}
	}
} else {
	$errors ['message'][0] = 'Vous n\'avez pas les droits nécessaires !';
	$data ['errors'] = $errors;
}


echo json_encode ( $data );
?>
