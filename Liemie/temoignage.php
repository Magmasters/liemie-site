<?php
session_start ();
include_once ('class/autoload.php');

$controleur = new controleur ();

$site = new page_base ( 'Témoignages' );
if (isset ( $_SESSION ['email'] ) && isset ( $_SESSION ['type'] )) {
	if ($_SESSION ['type'] == 'infirmier') {
		$site = new page_base_securisee_infirmier ( 'Témoignages' );
	}
	if ($_SESSION ['type'] == 'admin') {
		$site = new page_base_securisee_admin ( 'Témoignages' );
	}
	if ($_SESSION ['type'] == 'patient') {
		$site = new page_base_securisee_patient ( 'Témoignages' );
	}
}
/*
 * Si l'utilisateur n'est pas connecté on crée
 * le formulaire de connexion modal
 */
else {

	$site->js = 'jquery.validate.min';
	$site->js = 'messages_fr';
	$site->js = 'jquery.tooltipster.min';
	$site->css = 'tooltipster';

	$site->modal_login = $controleur->retourne_formulaire_login();
}

$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->retourne_temoignage ();

$site->affiche ();
?>