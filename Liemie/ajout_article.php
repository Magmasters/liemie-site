<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base_securisee_admin ( 'Ajout article' );
$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->js = 'ckeditor/ckeditor';
$site->css = 'tooltipster';
$site->css = 'modal';

$controleur = new controleur ();

$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->retourne_formulaire_article ( 'Ajout' );

$site->affiche ();
?>