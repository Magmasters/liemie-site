<?php
session_start ();

include_once ('../class/autoload.php');

$data = array ();
$data ['incomplete_results'] = false;

/*
 * Seul un compte ADMIN ou INFIRMIER a l'autorisation de faire une recherche de SOIN
 */
if (isset($_SESSION ['type']) && ($_SESSION ['type'] == 'admin' || $_SESSION ['type'] == 'infirmier') ) {
	
	$mypdo = new mypdo ();
	
	$champ = "LIBELLE_SOIN";
	$critere = $_POST['q'];
	$debut = $_POST['page']*3;
	$fin = $debut + 3;
	
	$data['items'] = array();

	$liste_soins = $mypdo->recherche_soins($champ, $critere, $debut, $fin);

	$data['items'] = $liste_soins;

	$data['total_count'] = count($data['items']);
}

echo json_encode ( $data );