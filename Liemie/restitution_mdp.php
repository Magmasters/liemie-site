<?php
session_start ();
include_once ('class/autoload.php');

$site = new page_base ( 'Mot de passe oublié' );

$site->js = 'jquery.validate.min';
$site->js = 'messages_fr';
$site->js = 'jquery.tooltipster.min';
$site->css = 'tooltipster';
$site->css = 'modal';

$controleur = new controleur ();
$site->right_sidebar = $site->rempli_right_sidebar ();


//Si l'utilisateur est déjà connecté à un compte on le renvoit sur la page d'accueil
if (isset ( $_SESSION ['email'] ) && isset ( $_SESSION ['type'] )) {
	if (isset($_SESSION ['type'])) {
		echo '<script>document.location.href="index.php"; </script>';
	}

} else {
	/*
	 * Si l'utilisateur n'est pas connecté on crée
	 * le formulaire de connexion modal
	 */
	
	$site->modal_login = $controleur->retourne_formulaire_login();
}

if (isset($_GET['jeton'])) {
	$jeton = $_GET['jeton'];
	$jeton = str_replace(' ', '+', $jeton);
	$jeton_decrypt = Cryptage::mc_decrypt($jeton);
	$parametres = array();
	
	//echo 'JETON - '.$jeton.'<br>';
	//echo 'JETON_DECRYPT - '.$jeton_decrypt;
	
	foreach (explode('&', $jeton_decrypt) as $chunk) {
		$param = explode("=", $chunk);
	
		if ($param) {
			$parametres[urldecode($param[0])] = urldecode($param[1]);
		}
	}
	
	$idjeton = $parametres['jeton'];
	$user = $parametres['user'];
	$categ = $parametres['utype'];
	$date = $parametres['date'];
	
	$site->left_sidebar = $controleur->retourne_formulaire_reinit_mdp ($idjeton, $user, $categ, $date);
	
} else {
	$site->left_sidebar = $controleur->retourne_formulaire_mdp_oublie ();
}

$site->affiche ();
?>