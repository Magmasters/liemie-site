<?php
include_once('autoload.php');

class controleur_patient extends controleur
{
	public function affiche_infos_patient()
	{
		$tab = array();
		$tab['email'] = $_SESSION['email'];
		$tab_patient = $this->vpdo->retourne_infos_patient($tab);
		$retour = '
			<h3 class="profile-title"> Mon profil </h3>
	        <div class="profile-header-container">
	    		<div class="profile-header-img">
	                <img class="img-circle" src="./image/logo.jpg" />
	                <!-- badge -->
	                <div class="rank-label-container">
	                    <span class="label label-default rank-label">'.$tab_patient[nom].'</span>
	                </div>
	            </div>
	        </div>
				';
		return $retour;
	}
	
	public function retourne_formulaire_patient($type, $idpatient= "") {
		$form = '';
		$nom = '';
		$prenom = '';
		$date_naiss = '';
		$adresse1 = '';
		$adresse2 = '';
		$cp = '';
		$ville = '';
		$mail = '';
		$tel1 = '';
		$tel2 = '';
		$tel3 = '';
	
		if ($type == 'Ajout') {
			$titreform = 'Formulaire ajout patient';
			$libelbutton = 'Ajouter';
		}
		if ($type == 'Demand') {
			$titreform = 'Formulaire Demande Inscription patient';
			$libelbutton = 'Soumettre';
		}
		if ($type == 'Supp') {
			$titreform = 'Formulaire Suppression inscription';
			$libelbutton = 'Supprimer';
		}
		if ($type == 'Modif') {
			$titreform = 'Formulaire Modification patient';
			$libelbutton = 'Modifier';
		}
		if ($type == 'Supp' || $type == 'Modif') {
			$patient = $this->vpdo->trouve_patient ( $idpatient);
			if ($patient != null) {
				$identifiant = $patient->email;
				$idadresse = $patient->id_adresse;
				$nom = $patient->nom;
				$prenom = $patient->prenom;
				$adresse1 = $patient->adresse_num;
				$adresse2 = $patient->adresse_rue;
				$date_naiss = $patient->date_naiss;
				$cp = $patient->adresse_cp;
				$ville = $patient->adresse_ville;
				$tel1 = $patient->tel1;
				if (isset ( $patient->tel2 )) {
					$tel2 = $patient->tel2;
				}
				if (isset ( $patient->tel3 )) {
					$tel3 = $patient->tel3;
				}
			}
			else {
				$identifiant = "Non trouvé...";
			}
		}
	
		$form = '
			<article >
				<h3>' . $titreform . '</h3>
				<form id="form_patient" method="post" role="form" class="formulaire-patient" >';
		if ($type == 'Ajout' || $type == 'Demand') {
			$vmp = $this->genererMDP ();
			$form = $form . '
		
					<fieldset class="form-group">
						<label for="identifiant">Email (identifiant)</label>
						<input type="email" class="form-control" name="identifiant" id="identifiant" placeholder="email patient" value="' . $identifiant . '" required/>
	
						<label for="mp">Mot de passe</label>
						<input type="text" class="form-control" readonly name="mp" id="mp" value="' . $vmp . '">
					</fieldset >
		
			';
		} else {
			$form = $form . '
					<div id="to_hide">
						<fieldset class="form-group">
							<label for="identifiant">Email (identifiant)</label>
							<input type="email" class="form-control" name="identifiant" id="identifiant" placeholder="email patient" value="' . $identifiant . '" required/>
	
							<label for="mp">Mot de passe</label>
							<input type="text" class="form-control" readonly name="mp" id="mp">
						</fieldset >
					</div>
					';
		}
		$form = $form . '
					<fieldset class="form-group">
						<label for="nom">Nom</label>
						<input type="text" class="form-control" name="nom" id="nom" value="' . $nom . '" required/>
	
						<label for"prenom">Prénom</label>
						<input type="text" class="form-control" name="prenom" id="prenom" value="' . $prenom . '" required/>
	
						<label for"date_naiss">Date de naissance</label>
						<input type="date" class="form-control" name="date_naiss" id="date_naiss" value="' . $date_naiss . '" required/>
					</fieldset >
	
					<fieldset class="form-group">
						<label for"adresse1">Numéro de voie</label>
						<input type="number" class="form-control" name="adresse1" id="adresse1" value="' . $adresse1 . '" required/>
	
						<label for"adresse2">Nom de voie</label>
						<input type="text" class="form-control" name="adresse2" id="adresse2" value="' . $adresse2 . '" required/>
	
						<label for"cp">Code postal</label>
						<input type="text" class="form-control" name="cp" id="cp" value="' . $cp . '" required/>
	
						<label for"ville">Ville</label>
						<input type="text" class="form-control" name="ville" id="ville" value="' . $ville . '" required/>
					</fieldset >
	
					<fieldset class="form-group">
						<label for"tel1">Tél. portable</label>
						<input type="text" class="form-control" name="tel1" id="tel1" value="' . $tel1 . '" required/>
	
						<label for"tel2">Tél. fixe</label>
						<input type="text" class="form-control" name="tel2" id="tel2" value="' . $tel2 . '" />
	
						<label for"tel3">Tél. autre</label>
						<input type="text" class="form-control" name="tel3" id="tel3" value="' . $tel3 . '" />
					</fieldset >
		
					<input id="submit" type="submit" class="btn btn-default" value="' . $libelbutton . '">
				</form>
	
				<script>
	
					$(\'#to_hide\').hide();
	
					function hd(){
						$(\'#modal\').hide();
						if($("#submit").prop("value")=="Supprimer"){
							window.location.reload();
						}
					}
				</script>
	
				<div  id="modal" >
										<h1>Informations !</h1>
										<div id="dialog1" ></div>
										<a class="no" onclick="hd();">OK</a>
				</div>
			<article >
	<script>
	
	$("#modal").hide();
	//Initialize the tooltips
	$("#form_patient :input").tooltipster({
				         trigger: "custom",
				         onlyOne: false,
				         position: "bottom",
				         multiple:true,
				         autoClose:false});
		jQuery.validator.addMethod(
			  "regex",
			   function(value, element, regexp) {
			       if (regexp.constructor != RegExp)
			          regexp = new RegExp(regexp);
			       else if (regexp.global)
			          regexp.lastIndex = 0;
			          return this.optional(element) || regexp.test(value);
			   },"erreur champs non valide"
			);
	$("#form_patient").submit(function( e ){
        e.preventDefault();
		$("#modal").hide();
	
		$mdp = 0;
		$idpatient = 0;
		$idadresse = 0;
	
		var $url="ajax/valide_ajout_patient.php";
		if($("#submit").prop("value")=="Modifier"){
			$idpatient = '. json_encode($idpatient) .'
			$idadresse = '. json_encode($idadresse) .'
			$url="ajax/valide_modif_patient.php";
		}
		if($("#submit").prop("value")=="Supprimer"){
			$idpatient = '. json_encode($idpatient) .'
			$idadresse = '. json_encode($idadresse) .'
			$url="ajax/valide_supp_patient.php";
		}
		if($("#submit").prop("value")=="Ajouter"){$mdp = $("#mp").val();}
		if($("#form_patient").valid())
		{
			/* Données du post */
	
			var formData = {
				"mp"					: $mdp,
				"nom" 					: $("#nom").val().toUpperCase(),
				"prenom"				: $("#prenom").val(),
				"adresse1"				: $("#adresse1").val(),
				"adresse2"				: $("#adresse2").val(),
				"cp"					: $("#cp").val(),
				"ville"					: $("#ville").val(),
				"email"					: $("#identifiant").val(),
				"tel1"					: $("#tel1").val(),
				"tel2"					: $("#tel2").val(),
				"tel3"					: $("#tel3").val(),
				"date_naiss"			: $("#date_naiss").val(),
				"idpatient"				: $idpatient,
				"idadresse"				: $idadresse,
			};
	
			var filterDataRequest = $.ajax(
    		{
	
        		type: "POST",
        		url: $url,
        		dataType: "json",
				encode          : true,
        		data: formData,
	
			});
			filterDataRequest.done(function(data)
			{
				if ( ! data.success)
				{
						$msg="<ul>";
						if (data.errors.message) {
							$.each(data.errors.message, function(index, value) {
								$msg+="<li>";
								$msg+=value;
								$msg+="</li>";
							});
						}
						if (data.errors.requete) {
							$x=data.errors.requete;
							$msg+="<li>";
							$msg+=$x;
							$msg+="</li>";
						}
	
						$msg+="</ul>";
				}
				else
				{
						$msg="<ul>";
						if(data.message) {
							$.each(data.message, function(index, value) {
								$msg+="<li>";
								$msg+=value;
								$msg+="</li>";
							});
						}
						$msg+="</ul>";
				}
	
					$("#dialog1").html($msg);$("#modal").show();
	
				});
			filterDataRequest.fail(function(jqXHR, textStatus)
			{
	
     			if (jqXHR.status === 0){alert("Not connect.n Verify Network.");}
    			else if (jqXHR.status == 404){alert("Requested page not found. [404]");}
				else if (jqXHR.status == 500){alert("Internal Server Error [500].");}
				else if (textStatus === "parsererror"){alert("Requested JSON parse failed.");}
				else if (textStatus === "timeout"){alert("Time out error.");}
				else if (textStatus === "abort"){alert("Ajax request aborted.");}
				else{alert("Uncaught Error.n" + jqXHR.responseText);}
			});
		}
	});
	
	$("#form_patient").validate({
		rules:
		{
	
			"nom": {required: true},
			"prenom": {required: true},
			"adresse1": {required: true},
			"adresse2": {required: true},
			"tel1": {required: true,regex: /^(\+33|0033|0)[0-9]{9}$/},
			"tel2": {regex: /^(\+33|0033|0)[0-9]{9}$/},
			"tel3": {regex: /^(\+33|0033|0)[0-9]{9}$/},
			"cp":{required: true,regex:/^\d{5}$/},
			"ville": {required: true},
			"email": {required: true,regex: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/},
		},
		messages:
		{
        	"nom":
          	{
            	required: "Vous devez saisir un nom valide"
          	},
			"prenom":
          	{
            	required: "Vous devez saisir un prenom valide"
          	},
			"adresse":
			{
            	required: "Vous devez saisir une adresse valide"
          	},
			"cp":
			{
				required: "Le code postal doit être composé de 5 chiffres"
			}
		},
		errorPlacement: function (error, element) {
			$(element).tooltipster("update", $(error).text());
			$(element).tooltipster("show");
		},
		success: function (label, element)
		{
			$(element).tooltipster("hide");
		}
   	});
	</script>
	
		';
		return $form;
	}
}