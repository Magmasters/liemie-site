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
	$tab ['type'] = $_POST ['type_article'];
	$tab ['contenu'] = $_POST ['contenu'];
	$tab ['titre'] = $_POST ['titre'];
	

	$resultat = $mypdo->maj_article ( $tab );
	if (isset ( $resultat ) && $resultat) {
		$data ['success'] = true;
	} else {
		$errors ['message'][0] = 'Erreur lors de la mise à jour de l\'article ! ';
	}

	if (! empty ( $errors )) {
		$data ['success'] = false;
		$data ['errors'] = $errors;
	} else {
		if ($data ['success']) {
			$data ['message'][0] = "Article modifié avec succés !";
		}
	}
} else {
	$errors ['message'][0] = 'Vous n\'avez pas les droits nécessaires !';
	$data ['errors'] = $errors;
}


echo json_encode ( $data );
?>
