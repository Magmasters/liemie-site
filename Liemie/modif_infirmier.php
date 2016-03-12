<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base_securisee_admin ( 'Gestion des infirmiers (modification)' );
$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->css = 'tooltipster';
$site->css = 'modal';

$controleur = new controleur ();
$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->affiche_liste_infirmiers ( 'Modif' );

if (isset ( $_POST ["nom_checkbox"] )) {
	foreach ( $_POST ["nom_checkbox"] as $index => $value ) {
		$site->left_sidebar = $controleur->retourne_formulaire_infirmier( 'Modif', $value );
		//$_SESSION ['id_eleve'] = $value;
		//break;
	}
}

$site->affiche ();

?>
