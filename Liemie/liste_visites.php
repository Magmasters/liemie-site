<?php
session_start ();
include_once ('class/autoload.php');

if ($_SESSION['type'] === "admin") {
	$site = new page_base_securisee_admin ( 'Liste des visites' );
} elseif ($_SESSION['type'] === "infirmier") {
	$site = new page_base_securisee_infirmier( 'Liste des visites' );
}

$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->js = 'moment.min';
$site->js = 'jquery-ui.min';
$site->js = 'fullcalendar.min';
$site->js = 'tipped.min';
$site->js = 'select2.min';
$site->css = 'tooltipster';
$site->css = 'modal';
$site->css = 'fullcalendar';
$site->css = 'tipped';
$site->css = 'select2.min';

$controleur = new controleur_visite ();

$site->right_sidebar = $site->rempli_right_sidebar ();
/*
 * Si l'utilisateur connecté est un ADMIN il peut visualiser TOUTES les visites
 * 					//			 un INFIRMIER il ne peut visualiser que les visites LE CONCERNANT
 * 					//			 un PATIENT il ne peut visualiser que les visites LE CONCERNANT
 */
$site->left_sidebar = $controleur->retourne_liste_visites ();

$site->affiche ();
