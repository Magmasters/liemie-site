<?php
session_start ();

include_once ('../class/autoload.php');

$errors = array ();
$data = array ();
$data ['success'] = false;

$tab = array ();
$mypdo = new mypdo ();

$tab ['email'] = $_POST ['email'];
$tab ['categ'] = $_POST ['categ'];

$resultat = $mypdo->restitution_mdp ( $tab );
if (isset ( $resultat ) && $resultat == true) {
	$data ['success'] = true;
} else {
	$errors ['message'] = 'Identifiant ou catégorie incorrects ! ';
}

if (! empty ( $errors )) {
	$data ['success'] = false;
	$data ['errors'] = $errors;
} else {
	if ($data ['success']) {
		$data ['message'] = "Un email contenant les instructions de réinitialisation vous a été envoyé !";
	}
}
echo json_encode ( $data );
?>