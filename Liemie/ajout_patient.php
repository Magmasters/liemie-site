<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base_securisee_admin ( 'Ajout patient' );
$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->css = 'tooltipster';
$site->css = 'modal';

$controleur = new controleur_patient();

$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->retourne_formulaire_patient ( 'Ajout' );

$site->affiche ();
?>