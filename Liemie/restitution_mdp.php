<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base ( 'Mot de passe oublié' );

$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->css = 'tooltipster';
$site->css = 'modal';

if (isset ( $_SESSION ['email'] ) && isset ( $_SESSION ['type'] )) {
	if (isset($_SESSION ['type'])) {
		echo '<script>document.location.href="index.php"; </script>';
	}

}

$controleur = new controleur ();
$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->retourne_formulaire_mdp_oublie ();

$site->affiche ();
?>