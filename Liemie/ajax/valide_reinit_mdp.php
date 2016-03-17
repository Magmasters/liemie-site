<?php
session_start ();

include_once ('../class/autoload.php');

$errors = array ();
$data = array ();
$data ['success'] = false;

$tab = array ();
$mypdo = new mypdo ();

$tab ['user'] = $_POST ['user'];
$tab ['idjeton'] = $_POST ['idjeton'];
$tab ['mdp'] = $_POST ['mdp'];
$tab ['mdp2'] = $_POST ['mdp2'];
$tab ['categ'] = $_POST ['categ'];

$resultat = $mypdo->reinit_mdp ( $tab );
if (isset ( $resultat ) && $resultat == true) {
	$data ['success'] = true;
} else {
	$errors ['message'] = 'Erreur, veuillez réessayer ! ';
}

if (! empty ( $errors )) {
	$data ['success'] = false;
	$data ['errors'] = $errors;
} else {
	if ($data ['success']) {
		$data ['message'] = "Votre mot de passe a été réinitialisé !";
	}
}
echo json_encode ( $data );
?>