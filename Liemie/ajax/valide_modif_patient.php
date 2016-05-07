<?php
session_start ();

include_once ('../class/autoload.php');

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	$errors = array ();
	$data = array ();
	$data ['success'] = false;

	$tab = array ();
	$mypdo = new mypdo ();

	$tab_patient ['id_patient'] = $_POST ['idpatient'];
	$tab_patient ['email'] = $_POST ['email'];
	$tab_patient ['nom'] = $_POST ['nom'];
	$tab_patient ['prenom'] = $_POST ['prenom'];
	$tab_patient ['date_naiss'] = $_POST ['date_naiss'];
	$tab_patient ['tel1'] = $_POST ['tel1'];
	$tab_patient ['tel2'] = $_POST ['tel2'];
	$tab_patient ['tel3'] = $_POST ['tel3'];
	
	//Adresse
	$tab_adresse ['num_rue'] = $_POST ['adresse1'];
	$tab_adresse ['nom_rue'] = $_POST ['adresse2'];
	$tab_adresse ['cp'] = $_POST ['cp'];
	$tab_adresse ['ville'] = $_POST ['ville'];
	$tab_adresse ['id_adresse'] = $_POST ['idadresse'];
	

	$resultat = $mypdo->maj_patient ( $tab_patient );
	if (isset ( $resultat ) && $resultat) {
		$data ['success'] = true;
		$data ['message'][0] = "Informations du patient ". $_POST['nom']. " " . $_POST['prenom'] . " mises à jour avec succés.";
	} else {
		$errors ['message'][0] = 'Les infirmations du patient n\'ont pas été mises à jour. 
				Note : Pour qu\'une modification soit acceptée au moins un des champs doit être modifié.';
	}
	
	$resultat_adresse = $mypdo->maj_adresse_patient ( $tab_adresse );
	if (isset ( $resultat_adresse ) && $resultat_adresse) {
		$data ['message'][1] = "Adresse du patient ". $_POST['nom']. " " . $_POST['prenom'] . " mise à jour avec succés.";
	} else {
		$errors ['message'][1] = 'L\'addresse du patient n\'a pas été mise à jour. 
				Note : Pour que celle-ci soit mise à jour, au moins un des champs de l\'adresse doit être modifié.';
	}
	
	if (! empty ( $errors )) {
		$data ['success'] = false;
		$data ['errors'] = $errors;
	}
	
} else {
	$errors ['message'] = 'Vous n\'avez pas les droits nécessaires !';
	$data ['errors'] = $errors;
}


echo json_encode ( $data );
?>