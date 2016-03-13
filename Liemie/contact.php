<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base ( 'L\'équipe' );
if (isset ( $_SESSION ['email'] ) && isset ( $_SESSION ['type'] )) {
	if ($_SESSION ['type'] == 'infirmier') {
		$site = new page_base_securisee_infirmier ( 'L\'équipe' );
	}
	if ($_SESSION ['type'] == 'admin') {
		$site = new page_base_securisee_admin ( 'L\'équipe' );
	}
}

$controleur = new controleur ();
$site->right_sidebar = $site->rempli_right_sidebar ();
$site->left_sidebar = $controleur->retourne_contact ();

$site->affiche ();
?>