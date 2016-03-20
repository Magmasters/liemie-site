<?php
/*
 * Cette page n'est plus utilisée, connexion effectuée via une fenêtre modale
 */
include_once ('class/autoload.php');
$site = new page_base ( 'Connect' );

$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->css = 'tooltipster';
$site->css = 'modal';
$controleur = new controleur ();
$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->retourne_formulaire_login ();

$site->affiche ();
?>