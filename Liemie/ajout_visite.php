<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base_securisee_admin ( 'Ajout visite' );
$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->js = 'select2.min';
$site->css = 'tooltipster';
$site->css = 'modal';
$site->css = 'select2.min';

echo '	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>';

$controleur = new controleur_visite ();

$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->retourne_formulaire_visite ( 'Ajout' );

$site->affiche ();
