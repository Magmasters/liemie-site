<?php
class mypdo extends PDO {
	private $PARAM_hote = 'mysql13.000webhost.com'; // le chemin vers le serveur
	private $PARAM_utilisateur = 'a7391540_devapp'; // nom d'utilisateur pour se connecter
	private $PARAM_mot_passe = 'aa0885aa'; // mot de passe de l'utilisateur pour se connecter
	private $PARAM_nom_bd = 'a7391540_devapp';
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
	public function trouve_famille($idfamille) {
		$requete = 'select * from famille where id_famille=' . $idfamille . ';';
		$result = $this->connexion->query ( $requete );
		if ($result) 

		{
			if ($result->rowCount () == 1) {
				return ($result->fetch ( PDO::FETCH_OBJ ));
			}
		}
		return null;
	}
	public function insert_famille_admin($tab) {
		$errors = array ();
		$data = array ();
		
		// attention le mot de passe est en clair tant que le mail de confirmation usep n'est pas envoyé
		$requete = 'INSERT INTO famille (identifiant,mp,nom1,prenom1,adresse11,adresse12,cp1,ville1,mail1,tel11,tel12,tel13,fonction1, nom2,prenom2,adresse21,adresse22,cp2,ville2,mail2,tel21,tel22,tel23,fonction2)
		VALUES (' . $this->connexion->quote ( $tab ['identifiant'] ) . ',' . 'MD5(' . $this->connexion->quote ( $tab ['mp'] ) . '),' . $this->connexion->quote ( $tab ['nom1'] ) . ',' . $this->connexion->quote ( $tab ['prenom1'] ) . ',' . $this->connexion->quote ( $tab ['adresse11'] ) . ',' . $this->connexion->quote ( $tab ['adresse12'] ) . ',' . $this->connexion->quote ( $tab ['cp1'] ) . ',' . $this->connexion->quote ( $tab ['ville1'] ) . ',' . $this->connexion->quote ( $tab ['mail1'] ) . ',' . $this->connexion->quote ( $tab ['tel11'] ) . ',' . $this->connexion->quote ( $tab ['tel12'] ) . ',' . $this->connexion->quote ( $tab ['tel13'] ) . ',' . $this->connexion->quote ( $tab ['fonction1'] ) . ',' . $this->connexion->quote ( $tab ['nom2'] ) . ',' . $this->connexion->quote ( $tab ['prenom2'] ) . ',' . $this->connexion->quote ( $tab ['adresse21'] ) . ',' . $this->connexion->quote ( $tab ['adresse22'] ) . ',' . $this->connexion->quote ( $tab ['cp2'] ) . ',' . $this->connexion->quote ( $tab ['ville2'] ) . ',' . $this->connexion->quote ( $tab ['mail2'] ) . ',' . $this->connexion->quote ( $tab ['tel21'] ) . ',' . $this->connexion->quote ( $tab ['tel22'] ) . ',' . $this->connexion->quote ( $tab ['tel23'] ) . ',' . $this->connexion->quote ( $tab ['fonction2'] ) . ');';
		
		$nblignes = $this->connexion->exec ( $requete );
		if ($nblignes != 1) {
			$errors ['requete'] = 'Pbs insertion famille :' . $requete;
		}
		
		if (! empty ( $errors )) {
			$data ['success'] = false;
			$data ['errors'] = $errors;
		} else {
			
			$data ['success'] = true;
			$data ['message'] = 'Insertion famille ok!';
		}
		return $data;
	}
	public function modif_famille_admin($tab) {
		$errors = array ();
		$data = array ();
		
		$requete = 'update famille ' . 'set nom1=' . $this->connexion->quote ( $tab ['nom1'] ) . ',' . 'prenom1=' . $this->connexion->quote ( $tab ['prenom1'] ) . ',' . 'adresse11=' . $this->connexion->quote ( $tab ['adresse11'] ) . ',' . 'adresse12=' . $this->connexion->quote ( $tab ['adresse12'] ) . ',' . 'cp1=' . $this->connexion->quote ( $tab ['cp1'] ) . ',' . 'ville1=' . $this->connexion->quote ( $tab ['ville1'] ) . ',' . 'mail1=' . $this->connexion->quote ( $tab ['mail1'] ) . ',' . 'tel11=' . $this->connexion->quote ( $tab ['tel11'] ) . ',' . 'tel12=' . $this->connexion->quote ( $tab ['tel12'] ) . ',' . 'tel13=' . $this->connexion->quote ( $tab ['tel13'] ) . ',' . 'fonction1=' . $this->connexion->quote ( $tab ['fonction1'] ) . ',' . 'nom2=' . $this->connexion->quote ( $tab ['nom2'] ) . ',' . 'prenom2=' . $this->connexion->quote ( $tab ['prenom2'] ) . ',' . 'adresse21=' . $this->connexion->quote ( $tab ['adresse21'] ) . ',' . 'adresse22=' . $this->connexion->quote ( $tab ['adresse22'] ) . ',' . 'cp2=' . $this->connexion->quote ( $tab ['cp2'] ) . ',' . 'ville2=' . $this->connexion->quote ( $tab ['ville2'] ) . ',' . 'mail2=' . $this->connexion->quote ( $tab ['mail2'] ) . ',' . 'tel21=' . $this->connexion->quote ( $tab ['tel21'] ) . ',' . 'tel22=' . $this->connexion->quote ( $tab ['tel22'] ) . ',' . 'tel23=' . $this->connexion->quote ( $tab ['tel23'] ) . ',' . 'fonction2=' . $this->connexion->quote ( $tab ['fonction2'] ) . ' where identifiant=' . $this->connexion->quote ( $tab ['identifiant'] ) . ';';
		
		$nblignes = $this->connexion->exec ( $requete );
		if ($nblignes != 1) {
			$errors ['requete'] = 'Pas de modifications d\'information :' . $requete;
		}
		
		if (! empty ( $errors )) {
			$data ['success'] = false;
			$data ['errors'] = $errors;
		} else {
			
			$data ['success'] = true;
			$data ['message'] = 'Modification famille ok!';
		}
		return $data;
	}
	public function liste_famille() {
		$requete = 'select * from famille order by identifiant;';
		$result = $this->connexion->query ( $requete );
		if ($result) {
			if ($result->rowCount () == 0) {
				return false;
			}
			return $result;
		}
		return false;
	}
}
?>
