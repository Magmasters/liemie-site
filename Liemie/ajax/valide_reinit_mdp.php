<?php
session_start ();

include_once ('../class/autoload.php');

$errors = array ();
$errors ['message'] = array();
$data = array ();
$data ['success'] = false;

$tab = array ();
$mypdo = new mypdo ();

$tab ['user'] = $_POST ['user'];
$tab ['idjeton'] = $_POST ['idjeton'];
$tab ['mdp'] = $_POST ['mdp'];
$tab ['mdp2'] = $_POST ['mdp2'];
$tab ['categ'] = $_POST ['categ'];

if (strlen($tab['mdp']) < 8) {
	array_push($errors ['message'], "Le mot de passe doit comporter au moins 8 caractères.");	
}

if (strcmp($tab['mdp'], $tab['mdp2']) != 0)
{
	array_push($errors ['message'], "Les mots de passe saisis doivent être identiques ! ");
}

if (empty ( $errors['message'] ) && !$mypdo->reinit_mdp ( $tab )) {
	array_push($errors ['message'], "Erreur lors de la réinitialisation.");
}


if (! empty ( $errors['message'] )) {
	$data ['success'] = false;
	$data ['errors'] = $errors;
} else {
	$data ['success'] = true;
	$data ['message'] = "Votre mot de passe a été réinitialisé !";
}
echo json_encode ( $data );
?>