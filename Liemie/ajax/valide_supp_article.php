<?php
session_start ();

include_once ('../class/autoload.php');

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	$errors = array ();
	$data = array ();
	$data ['success'] = false;

	$tab = array ();
	$mypdo = new mypdo ();

	$tab ['id_article'] = $_POST ['idarticle'];
	

	$resultat = $mypdo->supprimer_article ( $tab );
	if (isset ( $resultat ) && $resultat) {
		$data ['success'] = true;
	} else {
		$errors ['message'][0] = 'Erreur lors de la suppression de l\'article ! ';
	}

	if (! empty ( $errors )) {
		$data ['success'] = false;
		$data ['errors'] = $errors;
	} else {
		if ($data ['success']) {
			$data ['message'][0] = "Article supprimé avec succés !";
		}
	}
} else {
	$errors ['message'][0] = 'Vous n\'avez pas les droits nécessaires !';
	$data ['errors'] = $errors;
}


echo json_encode ( $data );
?>
