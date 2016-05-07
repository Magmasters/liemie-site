<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base_securisee_admin ( 'Gestion des infirmiers (modification)' );
$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->js = 'select2.min';
$site->css = 'tooltipster';
$site->css = 'modal';
$site->css = 'select2.min';

$controleur = new controleur ();
$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->affiche_liste_infirmiers ( 'Modif' );

if (isset ( $_POST ["idinfirmier"] )) {
	$idinfirmier = $_POST ["idinfirmier"];
	$site->left_sidebar = $controleur->retourne_formulaire_infirmier( 'Modif', $idinfirmier );
}

$site->affiche ();

?>
