<?php
include_once ('autoload.php');

class controleur_visite extends controleur {
	
	public function __get($propriete) {
		switch ($propriete) {
			case 'vpdo' :
				{
					return $this->vpdo;
					break;
				}
			case 'db' :
				{
					
					return $this->db;
					break;
				}
		}
	}
	
	public function retourne_formulaire_visite($type, $idvisite = "") {
		$form = '';
		$nom = '';
		$prenom = '';
		
		if ($type == 'Ajout') {
			$titreform = "Formulaire d'ajout d'une visite";
			$libelbutton = 'Ajouter';
		}
		if ($type == 'Demand') {
			$titreform = 'Formulaire Demande Inscription infirmier';
			$libelbutton = 'Soumettre';
		}
		if ($type == 'Supp') {
			$titreform = 'Formulaire Suppression inscription';
			$libelbutton = 'Supprimer';
		}
		if ($type == 'Modif') {
			$titreform = 'Formulaire Modification infirmier';
			$libelbutton = 'Modifier';
		}
		if ($type == 'Supp' || $type == 'Modif') {
			$infirmier = $this->vpdo->trouve_infirmier ( $idinfirmier);
			if ($infirmier != null) {
				$identifiant = $infirmier->email;
				$idadresse = $infirmier->id_adresse;
				$nom = $infirmier->nom;
				$prenom = $infirmier->prenom;
				$adresse1 = $infirmier->adresse_num;
				$adresse2 = $infirmier->adresse_rue;
				$date_naiss = $infirmier->date_naiss;
				$cp = $infirmier->adresse_cp;
				$ville = $infirmier->adresse_ville;
				$tel1 = $infirmier->tel1;
				if (isset ( $infirmier->tel2 )) {
					$tel2 = $infirmier->tel2;
				}
				if (isset ( $infirmier->tel3 )) {
					$tel3 = $infirmier->tel3;
				}
			}
			else {
				$identifiant = "Non trouv鮮.";
			}
		}
		
		$form = '
			<article >
				<h3>' . $titreform . '</h3>
				<form id="form_visite" method="post" role="form" class="formulaire-infirmier" >';
		if ($type == 'Ajout' || $type == 'Demand') {
			$vmp = $this->genererMDP ();
			$form = $form . '
					
					<fieldset class="form-group">
						<label for="identifiant">Email (identifiant)</label>
						<input type="email" class="form-control" name="identifiant" id="identifiant" placeholder="email infirmier" value="' . $identifiant . '" required/>
						
						<label for="mp">Mot de passe</label>
						<input type="text" class="form-control" readonly name="mp" id="mp" value="' . $vmp . '">
								
						<button onclick="copierMdp" id="boutonCopier" class="btn btn-default">Copier</button>
					</fieldset >
								
					<script>
						function copierMdp() {
							alert("ok");
							$("boutonCopier").text().select();
							document.execCommand("copy");
						}
					</script>
					
			';
		} else {
			$form = $form . '
					<div id="to_hide">
						<fieldset class="form-group">
							<label for="identifiant">Email (identifiant)</label>
							<input type="email" class="form-control" name="identifiant" id="identifiant" placeholder="email infirmier" value="' . $identifiant . '" required/>
							
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
								
						<label for"prenom">Pr꯯m</label>
						<input type="text" class="form-control" name="prenom" id="prenom" value="' . $prenom . '" required/>
						
						<label for"date_naiss">Date de naissance</label>
						<input type="date" class="form-control" name="date_naiss" id="date_naiss" value="' . $date_naiss . '" required/>
					</fieldset >
								
					<fieldset class="form-group">
						<label for"adresse1">Num곯 de voie</label>
						<input type="number" class="form-control" name="adresse1" id="adresse1" value="' . $adresse1 . '" required/>
								
						<label for"adresse2">Nom de voie</label>
						<input type="text" class="form-control" name="adresse2" id="adresse2" value="' . $adresse2 . '" required/>
								
						<label for"cp">Code postal</label>
						<input type="text" class="form-control" name="cp" id="cp" value="' . $cp . '" required/>
								
						<label for"ville">Ville</label>
						<input type="text" class="form-control" name="ville" id="ville" value="' . $ville . '" required/>
					</fieldset >
								
					<fieldset class="form-group">
						<label for"tel1">Tꬮ portable</label>
						<input type="text" class="form-control" name="tel1" id="tel1" value="' . $tel1 . '" required/>
								
						<label for"tel2">Tꬮ fixe</label>
						<input type="text" class="form-control" name="tel2" id="tel2" value="' . $tel2 . '" />
								
						<label for"tel3">Tꬮ autre</label>
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
	$("#form_visite :input").tooltipster({
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
	$("#form_visite").submit(function( e ){
        e.preventDefault();
		$("#modal").hide();
							
		$mdp = 0;
		$idinfirmier = 0;
		$idadresse = 0;
	
		var $url="ajax/valide_ajout_infirmier.php";
		if($("#submit").prop("value")=="Modifier"){
			$idinfirmier = '. json_encode($idinfirmier) .'
			$idadresse = '. json_encode($idadresse) .'
			$url="ajax/valide_modif_infirmier.php";
		}
		if($("#submit").prop("value")=="Supprimer"){
			$idinfirmier = '. json_encode($idinfirmier) .'
			$idadresse = '. json_encode($idadresse) .'
			$url="ajax/valide_supp_infirmier.php";
		}
		if($("#submit").prop("value")=="Ajouter"){$mdp = $("#mp").val();}
		if($("#form_visite").valid())
		{
			/* Donn꦳ du post */
							
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
				"idinfirmier"			: $idinfirmier,
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
  
	$("#form_visite").validate({
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
				required: "Le code postal doit 뵲e compos顤e 5 chiffres"
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
