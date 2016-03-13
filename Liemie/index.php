<?php
session_start ();
?>

<?php
include_once ('class/autoload.php');

$site = new page_base ( 'Accueil' );
if (isset ( $_SESSION ['email'] ) && isset ( $_SESSION ['type'] )) {
	if ($_SESSION ['type'] == 'infirmier') {
		$site = new page_base_securisee_infirmier ( 'Accueil' );
	}
	if ($_SESSION ['type'] == 'admin') {
		$site = new page_base_securisee_admin ( 'Accueil' );
	}
	if ($_SESSION ['type'] == 'patient') {
		$site = new page_base_securisee_patient ( 'Accueil' );
	}
}

$controleur = new controleur ();

$site->loginbar = $controleur->retourne_formulaire_login ();
if ($_SESSION ['type'] == 'infirmier') {
	$site->right_sidebar = $controleur->affiche_infos_infirmier();
} else {
	$site->right_sidebar = $site->rempli_right_sidebar ();
}
$site->left_sidebar = $controleur->retourne_article_accueil ();

$site->affiche ();
?>