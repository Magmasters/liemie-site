<?php
class mypdo_patient extends mypdo {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function retourne_infos_patient($tab)
	{
		$tab_patient = array ();
		$statement = 'SELECT * FROM PATIENT where EMAIL = :email';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':email', $tab ['email'], PDO::PARAM_STR );
		if ($sth->execute () && $sth->rowCount () > 0) {
			$patient = $sth->fetchObject ();
			$tab_patient [id] = $patient->ID_PATIENT;
			$tab_patient [nom] = $patient->NOM;
			$tab_patient [prenom] = $patient->PRENOM;
			$tab_patient [date_naissance] = $patient->DATE_NAISSANCE;
			$tab_patient [email] = $patient->EMAIL;
			$tab_patient [mdp] = $patient->MDP;
			$tab_patientr [lien_photo] = $patient->LIEN_PHOTO;
			$sth->closeCursor ();
		}
	
		return $tab_patient;
	}
	public function ajouter_patient($tab)
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
	
		$statement = 'INSERT INTO PATIENT (NOM, PRENOM, DATE_NAISSANCE, EMAIL, MDP, TEL1, TEL2, TEL3, ID_ADRESSE) VALUES (:nom, :prenom, :date_naiss, :email, :mdp, :tel1, :tel2, :tel3, :id_adresse)';
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
		 * Requête passée avec succés, patient ajouté
		 */
		if ($sth->execute() && $sth->rowCount() > 0) {
			return true;
		} else {
			//Erreur lors de l'exécution de la requête.
			return false;
		}
	}
	
	public function maj_patient($tab)
	{
	
		$statement = 'UPDATE PATIENT SET NOM = :nom, PRENOM = :prenom, EMAIL = :email, DATE_NAISSANCE = :date_naiss, TEL1 = :tel1, TEL2 = :tel2, TEL3 = :tel3, LIEN_PHOTO = :lien_photo WHERE ID_PATIENT= :id_patient';
		$sth = $this->connexion->prepare($statement);
		$sth->bindParam(':id_patient', $tab['id_patient'], PDO::PARAM_INT);
		$sth->bindParam(':nom', $tab['nom'], PDO::PARAM_STR);
		$sth->bindParam(':prenom', $tab['prenom'], PDO::PARAM_STR);
		$sth->bindParam(':date_naiss', $tab['date_naiss'], PDO::PARAM_STR);
		$sth->bindParam(':email', $tab['email'], PDO::PARAM_STR);
		$sth->bindParam(':tel1', $tab['tel1'], PDO::PARAM_STR);
		$sth->bindParam(':tel2', $tab['tel2'], PDO::PARAM_STR);
		$sth->bindParam(':tel3', $tab['tel3'], PDO::PARAM_STR);
		$sth->bindParam(':lien_photo', $tab['lien_photo'], PDO::PARAM_STR);
	
		/*
		 * Requête passée avec succés, patient ajouté
		 */
		if ($sth->execute() && $sth->rowCount() > 0) {
			return true;
		} else {
			//Erreur lors de l'exécution de la requête.
			return false;
		}
	}
	
	public function maj_adresse_patient($tab) {
		/*
		 * Une fois les informations du patient mises à jour
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
	
	
	public function liste_patient()
	{
		$statement = 'SELECT * FROM PATIENT';
		$sth = $this->connexion->prepare ( $statement );
		if ($sth->execute () && $sth->rowCount () > 0) {
			return $sth;
		}
	
		return false;
	}
	
	public function trouve_patient($idpatient)
	{
		$patient = new patient();
		$statement = 'SELECT * FROM patient WHERE ID_INFIRMIER = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $idpatient, PDO::PARAM_INT );
		if ($sth->execute () && $sth->rowCount () > 0) {
	
			$row_patient = $sth->fetchObject();
	
			$statement = 'SELECT * FROM ADRESSE WHERE ID_ADRESSE = :id_adr';
			$sth = $this->connexion->prepare($statement);
			$sth->bindParam(':id_adr', $row_patient->ID_ADRESSE);
	
			if ($sth->execute() && $sth->rowCount() > 0) {
	
				$row_adresse = $sth->fetchObject();
	
				$patient->id_patient = $row_patient->ID_PATIENT;
				$patient->id_adresse = $row_patient->ID_ADRESSE;
				$patient->email = $row_patient->EMAIL;
				$patient->nom = $row_patient->NOM;
				$patient->prenom = $row_patient->PRENOM;
				$patient->date_naiss = $row_patient->DATE_NAISSANCE;
				$patient->lien_photo = $row_patient->LIEN_PHOTO;
				$patient->tel1 = $row_patient->TEL1;
				$patient->tel2 = $row_patient->TEL2;
				$patient->tel3 = $row_patient->TEL3;
	
				$patient->adresse_num = $row_adresse->NUM;
				$patient->adresse_rue = $row_adresse->RUE;
				$patient->adresse_cp = $row_adresse->CODE_POSTAL;
				$patient->adresse_ville = $row_adresse->VILLE;
	
				return $patient;
			}
		}
	
		return null;
	}
	
	public function supprimer_patient($tab)
	{
		$statement = 'DELETE FROM SPECIALISER WHERE ID_PATIENT = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $tab['id_patient'], PDO::PARAM_INT );
	
		if (!$sth->execute()) {
			return false;
		}
	
		$statement = 'DELETE FROM VISITE WHERE ID_patient = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $tab['id_patient'], PDO::PARAM_INT );
	
		if (!$sth->execute()) {
			return false;
		}
	
		$statement = 'DELETE FROM AFFECTER_PATIENT WHERE ID_PATIENT = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $tab['id_patient'], PDO::PARAM_INT );
	
		if (!$sth->execute()) {
			return false;
		}
	
		$statement = 'DELETE FROM PATIENT WHERE ID_PATIENT = :id';
		$sth = $this->connexion->prepare ( $statement );
		$sth->bindParam ( ':id', $tab['id_patient'], PDO::PARAM_INT );
	
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
	
	
}