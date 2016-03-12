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
		$statement = 'INSERT INTO ADRESSE (NUM, RUE, VILLE, CODE_POSTAL) VALUES(:num, :rue, :ville, :cp)';
		$sth = $this->connexion->prepare($statement);
		$sth->bindParam(':num', $tab['num_rue'], PDO::PARAM_INT);
		$sth->bindParam(':rue', $tab['nom_rue'], PDO::PARAM_STR);
		$sth->bindParam(':ville', $tab['ville'], PDO::PARAM_STR);
		$sth->bindParam(':cp', $tab['cp'], PDO::PARAM_STR);
		
		//Si l'ajout de l'adresse dans la table adresse échoué on revoit FALSE
		//sinon on continue l'insertion
		if (!$sth->execute() || $sth->rowCount() <= 0) {
			return false;
		} else {
			$tab['id_adresse'] = $this->connexion->lastInsertId();
		}
		
		$statement = 'INSERT INTO INFIRMIER (NOM, PRENOM, DATE_NAISSANCE, EMAIL, MDP, TEL1, TEL2, TEL3, ID_ADRESSE) VALUES (:nom, :prenom, :date_naiss, :email, :mdp, :tel1, :tel2, :tel3, :id_adresse)';
		$sth = $this->connexion->prepare($statement);
		$sth->bindParam(':nom', $tab['nom'], PDO::PARAM_STR);
		$sth->bindParam(':prenom', $tab['prenom'], PDO::PARAM_STR);
		$sth->bindParam(':date_naiss', $tab['date_naiss'], PDO::PARAM_STR);
		$sth->bindParam(':email', $tab['email'], PDO::PARAM_STR);
		$sth->bindParam(':mdp', $tab['mdp'], PDO::PARAM_STR);
		$sth->bindParam(':tel1', $tab['tel1'], PDO::PARAM_STR);
		$sth->bindParam(':tel2', $tab['tel2'], PDO::PARAM_STR);
		$sth->bindParam(':tel3', $tab['tel3'], PDO::PARAM_STR);
		$sth->bindParam(':id_adresse', $tab['id_adresse'], PDO::PARAM_STR);
		
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
	
	public function maj_infirmier($tab)
	{

		$statement = 'UPDATE INFIRMIER SET NOM = :nom, PRENOM = :prenom, EMAIL = :email, DATE_NAISSANCE = :date_naiss, TEL1 = :tel1, TEL2 = :tel2, TEL3 = :tel3, LIEN_PHOTO = :lien_photo WHERE ID_INFIRMIER= :id_infirmier';
		$sth = $this->connexion->prepare($statement);
		$sth->bindParam(':id_infirmier', $tab['id_infirmier'], PDO::PARAM_INT);
		$sth->bindParam(':nom', $tab['nom'], PDO::PARAM_STR);
		$sth->bindParam(':prenom', $tab['prenom'], PDO::PARAM_STR);
		$sth->bindParam(':date_naiss', $tab['date_naiss'], PDO::PARAM_STR);
		$sth->bindParam(':email', $tab['email'], PDO::PARAM_STR);
		$sth->bindParam(':tel1', $tab['tel1'], PDO::PARAM_STR);
		$sth->bindParam(':tel2', $tab['tel2'], PDO::PARAM_STR);
		$sth->bindParam(':tel3', $tab['tel3'], PDO::PARAM_STR);
		$sth->bindParam(':lien_photo', $tab['lien_photo'], PDO::PARAM_STR);
		
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
	
	public function maj_adresse_infirmier($tab) {
		/*
		 * Une fois les informations de l'infirmier mises à jour
		 * ont met à jour les informations de l'adresse correspodante
		 */
		$idadresse = intval($tab['id_adresse']);
		
		$statement = 'UPDATE ADRESSE SET NUM=:num, RUE=:rue, VILLE=:ville, CODE_POSTAL=:cp WHERE ID_ADRESSE=:id_adresse';
		$sth = $this->connexion->prepare($statement);
		$sth->bindParam(':num', $tab['num_rue'], PDO::PARAM_STR);
		$sth->bindParam(':rue', $tab['nom_rue'], PDO::PARAM_STR);
		$sth->bindParam(':ville', $tab['ville'], PDO::PARAM_STR);
		$sth->bindParam(':cp', $tab['cp'], PDO::PARAM_STR);
		$sth->bindParam(':id_adresse', $idadresse, PDO::PARAM_INT);
		
		if ($sth->execute() && $sth->rowCount() > 0) {
			return true;
		} else {
			//Erreur lors de l'exécution de la requête.
			return false;
		}
	}
	
	public function liste_infirmiers()
	{
		$statement = 'SELECT * FROM INFIRMIER';
		$sth = $this->connexion->prepare ( $statement );
		if ($sth->execute () && $sth->rowCount () > 0) {
			return $sth;
		}
		
		return false;
	}
	
	public function trouve_infirmier($idinfirmier)
	{
		$infirmier = new Infirmier();
		$statement = 'SELECT * FROM INFIRMIER WHERE ID_INFIRMIER = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $idinfirmier, PDO::PARAM_INT );
		if ($sth->execute () && $sth->rowCount () > 0) {
			
			$row_infirmier = $sth->fetchObject();
			
			$statement = 'SELECT * FROM ADRESSE WHERE ID_ADRESSE = :id_adr';
			$sth = $this->connexion->prepare($statement);
			$sth->bindParam(':id_adr', $row_infirmier->ID_ADRESSE);
			
			if ($sth->execute() && $sth->rowCount() > 0) {
				
				$row_adresse = $sth->fetchObject();
				
				$infirmier->id_infirmier = $row_infirmier->ID_INFIRMIER;
				$infirmier->id_adresse = $row_infirmier->ID_ADRESSE;
				$infirmier->email = $row_infirmier->EMAIL;
				$infirmier->nom = $row_infirmier->NOM;
				$infirmier->prenom = $row_infirmier->PRENOM;
				$infirmier->date_naiss = $row_infirmier->DATE_NAISSANCE;
				$infirmier->lien_photo = $row_infirmier->LIEN_PHOTO;
				$infirmier->tel1 = $row_infirmier->TEL1;
				$infirmier->tel2 = $row_infirmier->TEL2;
				$infirmier->tel3 = $row_infirmier->TEL3;
				
				$infirmier->adresse_num = $row_adresse->NUM;
				$infirmier->adresse_rue = $row_adresse->RUE;
				$infirmier->adresse_cp = $row_adresse->CODE_POSTAL;
				$infirmier->adresse_ville = $row_adresse->VILLE;
				
				return $infirmier;
			}
		}
	
		return null;
	}
	
	public function supprimer_infirmier($tab)
	{
		$statement = 'DELETE FROM SPECIALISER WHERE ID_INFIRMIER = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $tab['id_infirmier'], PDO::PARAM_INT );
		
		if (!$sth->execute()) {
			return false;
		}
		
		$statement = 'DELETE FROM VISITE WHERE ID_INFIRMIER = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $tab['id_infirmier'], PDO::PARAM_INT );
		
		if (!$sth->execute()) {
			return false;
		}
		
		$statement = 'DELETE FROM AFFECTER_INFIRMIER WHERE ID_INFIRMIER = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $tab['id_infirmier'], PDO::PARAM_INT );
		
		if (!$sth->execute()) {
			return false;
		}
		
		$statement = 'DELETE FROM INFIRMIER WHERE ID_INFIRMIER = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $tab['id_infirmier'], PDO::PARAM_INT );
		
		if (!$sth->execute() || $sth->rowCount() <= 0) {
			return false;
		}
		
		$statement = 'DELETE FROM ADRESSE WHERE ID_ADRESSE = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $tab['id_adresse'], PDO::PARAM_INT );
			
		if (!$sth->execute() || $sth->rowCount() <= 0) {
			return false;
		}
		
		return true;
	}
	
	public function retourne_article_par_type($type)
	{
		$statement = 'SELECT * FROM ARTICLES WHERE TYPE = :type';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':type', $type, PDO::PARAM_STR );
		if ($sth->execute () && $sth->rowCount () > 0) {
			return $sth;
		}
		
		return null;
	}
	
	public function retourne_article_par_id($id)
	{
		$statement = 'SELECT * FROM ARTICLES WHERE ID_ARTICLE = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', intval($id), PDO::PARAM_INT );
		if ($sth->execute () && $sth->rowCount () > 0) {
	
			$row = $sth->fetchObject();
			return $row;
		}
	
		return null;
	}
	
	public function ajouter_article($tab)
	{
		$statement = 'INSERT INTO ARTICLES (TITRE, TYPE, CONTENU) VALUES(:titre, :type, :contenu)';
		$sth = $this->connexion->prepare($statement);
		$sth->bindParam(':titre', $tab['titre'], PDO::PARAM_STR);
		$sth->bindParam(':type', $tab['type'], PDO::PARAM_STR);
		$sth->bindParam(':contenu', $tab['contenu'], PDO::PARAM_STR);
		
		if ($sth->execute() && $sth->rowCount() > 0) {
			return true;
		}
		
		echo var_dump($sth->errorInfo());
		
		return false;
	}
}
?>
