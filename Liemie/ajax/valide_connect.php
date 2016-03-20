<?php
session_start ();

include_once ('../class/autoload.php');

$errors = array ();
$data = array ();
$data ['success'] = false;

$tab = array ();
$mypdo = new mypdo ();

$tab ['email'] = $_POST ['email'];
$tab ['mdp'] = md5 ( $_POST ['mdp'] );
$tab ['categ'] = $_POST ['categ'];

$resultat = $mypdo->connect ( $tab );
if (isset ( $resultat )) {
	$_SESSION ['email'] = $tab ['email'];
	$_SESSION ['type'] = $tab ['categ'];
	$data ['success'] = true;
} else {
	$errors ['message'] = 'Identifiant,mot de passe,catégorie invalide ! ' . $tab ['email'];
}

if (! empty ( $errors )) {
	$data ['success'] = false;
	$data ['errors'] = $errors;
} else {
	if ($data ['success']) {
		$data ['message'] = "Connexion réussie, vous serez redirigé dans quelques secondes !";
	}
}
echo json_encode ( $data );
?>