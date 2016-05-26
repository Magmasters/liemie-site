<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base_securisee_admin ( 'Gestion des articles (modification)' );
$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->js = 'ckeditor/ckeditor';
$site->css = 'tooltipster';
$site->css = 'modal';

$controleur = new controleur ();
$site->right_sidebar = $site->rempli_right_sidebar ();


if (isset ($_POST['type_article_a_modif'])) {
	$type_articles = $_POST['type_article_a_modif'];
	$site->left_sidebar = $controleur->affiche_liste_articles ( 'Modif ordre',  $type_articles);	
} else if (isset ( $_POST ["send"] )) {
	foreach ( $_POST ["num_affichage"] as $index => $value ) {
		
	}
} else {
	$site->left_sidebar = $controleur->affiche_selecteur_type_article();	
}

$site->affiche ();

?>
