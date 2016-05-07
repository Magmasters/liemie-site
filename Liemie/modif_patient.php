<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base_securisee_admin ( 'Gestion des patients (modification)' );
$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->js = 'select2.min';
$site->css = 'tooltipster';
$site->css = 'modal';
$site->css = 'select2.min';

$controleur = new controleur_patient ();
$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->affiche_liste_patients ( 'Modif' );

if (isset ( $_POST ["idpatient"] )) {
	$idpatient = $_POST ["idpatient"];
	$site->left_sidebar = $controleur->retourne_formulaire_patient( 'Modif', $idpatient );
}

$site->affiche ();

?>
