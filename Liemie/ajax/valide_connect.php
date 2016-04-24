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
$tab ['id_utilisateur'] = -1;

$resultat = $mypdo->connect ( $tab );
if ($resultat != null) {
	$_SESSION ['email'] = $tab ['email'];
	$_SESSION ['type'] = $tab ['categ'];
	
	$objUtilisateur = $resultat->fetchObject();
	if ($objUtilisateur != null) {
		if ($tab ['categ'] === "admin") { $_SESSION ['id_utilisateur'] = $objUtilisateur->ID_ADMIN; }
		if ($tab ['categ'] === "infirmier") { $_SESSION ['id_utilisateur'] = $objUtilisateur->ID_INFIRMIER; }
		if ($tab ['categ'] === "patient") { $_SESSION ['id_utilisateur'] = $objUtilisateur->ID_PATIENT; }
	}
	
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