<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base_securisee_admin ( 'Liste des visites' );
$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->js = 'moment.min';
$site->js = 'jquery-ui.min';
$site->js = 'fullcalendar.min';
$site->css = 'tooltipster';
$site->css = 'modal';
$site->css = 'fullcalendar';

$controleur = new controleur_visite ();

$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->retourne_liste_visites ( 'Ajout' );

$site->affiche ();
