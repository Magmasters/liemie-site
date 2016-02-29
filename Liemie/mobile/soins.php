<?php
include_once ('../class/autoload.php');


$mypdo = new mypdo ();

$table = $_GET ['table'];

switch ($table) {
	case 'categorie' :
		$tab_categ_soins = $mypdo->retourne_categ_soins ();
		echo $tab_categ_soins;
		break;
	
	case 'type' :
		$tab_type_soins = $mypdo->retourne_type_soins ();
		echo $tab_type_soins;
		break;
	
	case 'soin' :
		$tab_soins = $mypdo->retourne_soins ();
		echo $tab_soins;
		break;
}

