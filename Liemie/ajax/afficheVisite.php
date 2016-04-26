<?php
//NON UTILISE
session_start ();

include_once ('../class/autoload.php');

$errors = array ();
$data = array ();
$data ['success'] = false;

$tab = array ();
$mypdo = new mypdo ();

$tab ['id_infirmier'] = $_POST ['idInfirmier'];
$tab ['id_patient'] =  $_POST ['idPatient'];
$tab ['date_visite'] = $_POST ['dateVisite'];
$tab ['comment_infirmier'] = $_POST ['commentInfirmier'];

echo json_encode ( $data );
?>