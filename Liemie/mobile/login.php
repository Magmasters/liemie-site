<?php
include_once ('../class/autoload.php');

$mypdo = new mypdo ();

$tab = array ();
$tab ['email'] = "";
$tab ['mdp'] = "";

$tab ['email'] = $_POST ['email'];
//$tab ['mdp'] = md5 ( $_POST ['mdp'] );
$tab ['mdp'] =  $_POST ['mdp'];

$tab_infirmier = $mypdo->connect_mobile ( $tab );
echo $tab_infirmier;

/*echo $tab['email'];
echo '<br>';
echo $tab['mdp'];*/




