<?php
session_start ();

include_once ('../class/autoload.php');

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	$errors = array ();
	$data = array ();
	$data ['success'] = false;

	$tab = array ();
	$mypdo = new mypdo ();

	$tab_infirmier ['id_infirmier'] = $_POST ['idinfirmier'];
	$tab_infirmier ['email'] = $_POST ['email'];
	$tab_infirmier ['nom'] = $_POST ['nom'];
	$tab_infirmier ['prenom'] = $_POST ['prenom'];
	$tab_infirmier ['date_naiss'] = $_POST ['date_naiss'];
	$tab_infirmier ['tel1'] = $_POST ['tel1'];
	$tab_infirmier ['tel2'] = $_POST ['tel2'];
	$tab_infirmier ['tel3'] = $_POST ['tel3'];
	$tab_infirmier ['lien_photo'] = ''; // Pas de lien photo
	
	//Adresse
	$tab_adresse ['num_rue'] = $_POST ['adresse1'];
	$tab_adresse ['nom_rue'] = $_POST ['adresse2'];
	$tab_adresse ['cp'] = $_POST ['cp'];
	$tab_adresse ['ville'] = $_POST ['ville'];
	$tab_adresse ['id_adresse'] = $_POST ['idadresse'];
	

	$resultat = $mypdo->maj_infirmier ( $tab_infirmier );
	if (isset ( $resultat ) && $resultat) {
		$data ['success'] = true;
		$data ['message'][0] = "Informations de l'infirmier ". $_POST['nom']. " " . $_POST['prenom'] . " mises à jour avec succés.";
	} else {
		$errors ['message'][0] = 'Erreur lors de la modification des informations de l\'infirmier !';
	}
	
	$resultat_adresse = $mypdo->maj_adresse_infirmier ( $tab_adresse );
	if (isset ( $resultat_adresse ) && $resultat_adresse) {
		$data ['message'][1] = "Adresse de l'infirmier ". $_POST['nom']. " " . $_POST['prenom'] . " mise à jour avec succés.";
	} else {
		$errors ['message'][1] = 'Erreur lors de la mise à jour l\'adresse de l\'infirmier !';
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
