<?php
class mypdo extends PDO {
	private $PARAM_hote = 'mysql-magmasters.alwaysdata.net'; // le chemin vers le serveur
	private $PARAM_utilisateur = '119579'; // nom d'utilisateur pour se connecter
	private $PARAM_mot_passe = 'magmasters'; // mot de passe de l'utilisateur pour se connecter
	private $PARAM_nom_bd = 'magmasters_liemie';
	private $connexion;
	public function __construct() {
		try {
			
			$this->connexion = new PDO ( 'mysql:host=' . $this->PARAM_hote . ';dbname=' . $this->PARAM_nom_bd, $this->PARAM_utilisateur, $this->PARAM_mot_passe, array (
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' 
			) );
			// echo '<script>alert ("ok connex");</script>)';echo $this->PARAM_nom_bd;
		} catch ( PDOException $e ) {
			echo 'hote: ' . $this->PARAM_hote . ' ' . $_SERVER ['DOCUMENT_ROOT'] . '<br />';
			echo 'Erreur : ' . $e->getMessage () . '<br />';
			echo 'N° : ' . $e->getCode ();
			$this->connexion = false;
			// echo '<script>alert ("pbs acces bdd");</script>)';
		}
	}
	public function __get($propriete) {
		switch ($propriete) {
			case 'connexion' :
				{
					return $this->connexion;
					break;
				}
		}
	}
	public function connect($tab) {
		if ($tab ['categ'] == 'infirmier') {
			$requete = 'select * from INFIRMIER where EMAIL="' . $tab ['email'] . '" and MDP="' . $tab ['mdp'] . '";';
		} else {
			$requete = 'select * from ADMIN where EMAIL="' . $tab ['email'] . '" and MDP="' . $tab ['mdp'] . '";';
		}
		$result = $this->connexion->query ( $requete );
		if ($result) {
			if ($result->rowCount () == 1) {
				return ($result);
			}
		}
		return null;
	}
	public function connect_mobile($tab) {
		$tab_infirmier = array ();
		$statement = 'SELECT * FROM INFIRMIER where EMAIL = :email AND MDP = :mdp';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':email', $tab ['email'], PDO::PARAM_STR );
		$sth->bindParam ( ':mdp', $tab ['mdp'], PDO::PARAM_STR, 64 );
		if ($sth->execute () && $sth->rowCount () > 0) {
			$infirmier = $sth->fetchObject ();
			$tab_infirmier [id] = $infirmier->ID_INFIRMIER;
			$tab_infirmier [nom] = $infirmier->NOM;
			$tab_infirmier [prenom] = $infirmier->PRENOM;
			$tab_infirmier [date_naissance] = $infirmier->DATE_NAISSANCE;
			$tab_infirmier [email] = $infirmier->EMAIL;
			$tab_infirmier [mdp] = $infirmier->MDP;
			$tab_infirmier [lien_photo] = $infirmier->LIEN_PHOTO;
			
			$sth->closeCursor ();
		}
		
		return json_encode ( $tab_infirmier );
	}
	public function retourne_categ_soins() {
		$tab_categ_soins = array ();
		$statement = 'SELECT * FROM CATEG_SOINS';
		$sth = $this->connexion->prepare ( $statement );
		if ($sth->execute () && $sth->rowCount () > 0) {
			while ( $categ_soin = $sth->fetchObject () ) {
				$tab_categ_soins [$categ_soin->ID_CATEGORIE] [libelle_categorie] = $categ_soin->LIBELLE_CATEGORIE;
				$tab_categ_soins [$categ_soin->ID_CATEGORIE] [description] = $categ_soin->DESCRIPTION;
			}
			$sth->closeCursor ();
		}
		
		return json_encode ( $tab_categ_soins );
	}
	public function retourne_type_soins() {
		$tab_type_soin = array ();
		$statement = 'SELECT * FROM TYPE_SOIN';
		$sth = $this->connexion->prepare ( $statement );
		if ($sth->execute () && $sth->rowCount () > 0) {
			while ( $type_soin = $sth->fetchObject () ) {
				$tab_type_soin [$type_soin->ID_TYPE_SOIN] [libelle_type_soin] = $type_soin->LIBELLE_TYPE_SOIN;
				$tab_type_soin [$type_soin->ID_TYPE_SOIN] [id_categorie] = $type_soin->ID_CATEGORIE;
			}
			$sth->closeCursor ();
		}
		
		return json_encode ( $tab_type_soin );
	}
	public function retourne_soins() {
		$tab_soins = array ();
		$statement = 'SELECT * FROM SOIN';
		$sth = $this->connexion->prepare ( $statement );
		if ($sth->execute () && $sth->rowCount () > 0) {
			while ( $soin = $sth->fetchObject () ) {
				$tab_soins [$soin->ID_SOIN] [id_type_soin] = $soin->ID_TYPE_SOIN;
				$tab_soins [$soin->ID_SOIN] [libelle_soin] = $soin->LIBELLE_SOIN;
				$tab_soins [$soin->ID_SOIN] [description] = $soin->DESCRIPTION;
			}
			$sth->closeCursor ();
		}
		
		return json_encode ( $tab_soins );
	}
	
	public function retourne_infos_infirmier($tab)
	{
		$tab_infirmier = array ();
		$statement = 'SELECT * FROM INFIRMIER where EMAIL = :email';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':email', $tab ['email'], PDO::PARAM_STR );
		if ($sth->execute () && $sth->rowCount () > 0) {
			$infirmier = $sth->fetchObject ();
			$tab_infirmier [id] = $infirmier->ID_INFIRMIER;
			$tab_infirmier [nom] = $infirmier->NOM;
			$tab_infirmier [prenom] = $infirmier->PRENOM;
			$tab_infirmier [date_naissance] = $infirmier->DATE_NAISSANCE;
			$tab_infirmier [email] = $infirmier->EMAIL;
			$tab_infirmier [mdp] = $infirmier->MDP;
			$tab_infirmier [lien_photo] = $infirmier->LIEN_PHOTO;
			$sth->closeCursor ();
		}
		
		return $tab_infirmier;
	}
	
	public function ajouter_infirmier($tab)
	{
		$statement = 'INSERT INTO INFIRMIER (NOM, PRENOM, DATE_NAISSANCE, EMAIL, MDP) VALUES (:nom, :prenom, :date_naiss, :email, :mdp)';
		$sth = $this->connexion->prepare($statement);
		$sth->bindParam(':nom', $tab['nom'], PDO::PARAM_STR);
		$sth->bindParam(':prenom', $tab['prenom'], PDO::PARAM_STR);
		$sth->bindParam(':date_naiss', $tab['date_naiss'], PDO::PARAM_STR);
		$sth->bindParam(':email', $tab['email'], PDO::PARAM_STR);
		$sth->bindParam(':mdp', $tab['mdp'], PDO::PARAM_STR);
		
		/*
		 * Requête passée avec succés, infirmier ajouté
		 */
		if ($sth->execute() && $sth->rowCount() > 0) {
			return true;
		} else {
			//Erreur lors de l'exécution de la requête.
			return false;
		}
	}
}
?>
