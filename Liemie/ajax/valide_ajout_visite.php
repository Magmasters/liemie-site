<?php
session_start ();

include_once ('../class/autoload.php');

$data = array ();
$data ['errors'] = array();
$errors = array();
$data ['success'] = false;

if (isset($_SESSION ['type']) && $_SESSION ['type'] == 'admin' ) {
	
	if (isset($_POST['idinfirmier']) && 
			isset($_POST['idpatient']) &&
			isset($_POST['date_visite']) &&
			isset($_POST['heure_visite']))
	{
		
		$mypdo = new mypdo();
		
		$idinfirmier = $_POST['idinfirmier'];
		$idpatient = $_POST['idpatient'];
		$jour_visite = $_POST['date_visite'];
		$heure_visite = $_POST['heure_visite'];
		
		$date_visite = $jour_visite." ".$heure_visite;
		
		
		$datetime_timestamp  = strtotime($date_visite);
		$now = time();
		//Une visite doit être programmée plus de 48 heures avant la date de visite prévue
		if ($datetime_timestamp <= ($now+3600*48))
		{
			array_push($errors, "Une visite doit être programmée au moins 48 heures avant la date de visite prévue");
		} else {
			$tab = array();
			$tab['idinfirmier'] = $idinfirmier;
			$tab['idpatient'] = $idpatient;
			$tab['date_visite'] = $date_visite;
			
			if($mypdo->ajouter_visite($tab)) {
				$data ['success'] = true;
			}
		}
	}
	
	if ($data['success']) {
		$data ['message'][0] = "Une visite sera effectuée par l'infirmier $idinfirmier chez le patient $idpatient, le $jour_visite à $heure_visite";
	} else {
		$data['errors'] = $errors;
	}
}

echo json_encode ( $data );