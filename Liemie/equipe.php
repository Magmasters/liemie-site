<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base ( 'L\'�quipe' );
if (isset ( $_SESSION ['email'] ) && isset ( $_SESSION ['type'] )) {
	if ($_SESSION ['type'] == 'famille') {
		$site = new page_base_securisee_infirmier ( 'Accueil' );
	}
	if ($_SESSION ['type'] == 'admin') {
		$site = new page_base_securisee_admin ( 'Accueil' );
	}
}

$controleur = new controleur ();
$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->retourne_equipe ();

$site->affiche ();
?>