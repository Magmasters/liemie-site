<?php
class controleur {
	private $vpdo;
	private $db;
	public function __construct() {
		$this->vpdo = new mypdo ();
		$this->db = $this->vpdo->connexion;
	}
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
	public function retourne_equipe() {
		return '
		<article>
			<h1>L\'Equipe</h1>
		</article>';
	}
	public function retourne_temoignage() {
		return '
		<article>
			<h1>Témoignages</h1>
		</article>';
	}
	public function retourne_article_accueil() {
		// $retour = $this->retourne_carroussel_bootstrap();
		$retour = '
		<article>
			<h2>Liemie</h2>
		</article>';
		return $retour;
	}
	public function retourne_formulaire_login() {
		return '
			<article >
				<h3>Formulaire de connexion</h3>
				<form id="login" method="post" class="login">
					<input type="text" name="email" id="email" placeholder="Identifiant" required/>
					<input type="password" name="mdp" id="mdp" placeholder="Mot de passe" required/></br>
					<input type="radio" name="rblogin" id="rbi"  value="rbi" required/>Infirmier
					<input type="radio" name="rblogin" id="rba" value="rba" required/>Administrateur</br></br>
					<input type="submit" name="send" class="button" value="Envoi login" />
				</form>
				<script>function hd(){ $(\'#modal\').hide();}</script>
				<script>function home(){ document.location.href="index.php";}</script>
				<div  id="modal" >
										<h1>Informations !</h1>
										<div id="dialog1" ></div>
										<a class="no" onclick="hd();home();">OK</a>
				</div>
			<article >
	<script>
	$("#modal").hide();
	//Initialize the tooltips
	$("#login :input").tooltipster({
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
	$("#login").submit(function( e ){
        e.preventDefault();
		$("#modal").hide();
						
		var $url="ajax/valide_connect.php";
		if($("#login").valid())
		{
			$categ="infirmier";		
			if($("input[type=radio][name=rblogin]:checked").attr("value")=="rbi"){$categ="infirmier";}
			if($("input[type=radio][name=rblogin]:checked").attr("value")=="rba"){$categ="admin";}
			var formData = {
			"email" 					: $("#email").val().toUpperCase(),
   			"mdp"					: $("#mdp").val(),
   			"categ"					: $categ												   		
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
						var $msg="erreur-></br><ul style=\"list-style-type :decimal;padding:0 5%;\">";
						if (data.errors.message) {
							$x=data.errors.message;
							$msg+="<li>";
							$msg+=$x;
							$msg+="</li>";
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
						$msg="";
						if(data.message){$msg+="</br>";$x=data.message;$msg+=$x;}
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
   
	$("#login").validate({
		rules:
		{
													
			"email": {required: true},
			"mdp": {required: true},
			"rblogin": {required: true}
		},
		messages:
		{
        	"email":
          	{
            	required: "Vous devez saisir un identifiant valide"
          	},
			"mdp":
          	{
            	required: "Vous devez saisir un mot de passe valide"
          	},			
			"rblogin":
			{
            	required: "Vous devez choisir famille ou administrateur"
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
	}
	public function genererMDP($longueur = 8) {
		// initialiser la variable $mdp
		$mdp = "";
		
		// Définir tout les caractères possibles dans le mot de passe,
		// Il est possible de rajouter des voyelles ou bien des caractères spéciaux
		$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ&#@$*!";
		
		// obtenir le nombre de caractères dans la chaîne précédente
		// cette valeur sera utilisé plus tard
		$longueurMax = strlen ( $possible );
		
		if ($longueur > $longueurMax) {
			$longueur = $longueurMax;
		}
		
		// initialiser le compteur
		$i = 0;
		
		// ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
		while ( $i < $longueur ) {
			// prendre un caractère aléatoire
			$caractere = substr ( $possible, mt_rand ( 0, $longueurMax - 1 ), 1 );
			
			// vérifier si le caractère est déjà utilisé dans $mdp
			if (! strstr ( $mdp, $caractere )) {
				// Si non, ajouter le caractère à $mdp et augmenter le compteur
				$mdp .= $caractere;
				$i ++;
			}
		}
		
		// retourner le résultat final
		return $mdp;
	}
	public function retourne_formulaire_infirmier($type, $idinfirmier = "") {
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
			$titreform = 'Formulaire ajout infirmier';
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
			$row = $this->vpdo->trouve_famille ( $idinfirmier);
			if ($row != null) {
				$identifiant = $row->identifiant;
				$nom1 = $row->nom1;
				$prenom1 = $row->prenom1;
				$adresse11 = $row->adresse11;
				if (isset ( $row->adresse12 )) {
					$adresse12 = $row->adresse12;
				}
				$cp1 = $row->cp1;
				$ville1 = $row->ville1;
				if (isset ( $row->mail1 )) {
					$mail1 = $row->mail1;
				}
				$tel11 = $row->tel11;
				if (isset ( $row->tel12 )) {
					$tel12 = $row->tel12;
				}
				if (isset ( $row->tel13 )) {
					$tel13 = $row->tel13;
				}
				if ($row->fonction1 == 'pere') {
					$checkpere1 = 'checked';
				}
				if ($row->fonction1 == 'mere') {
					$checkmere1 = 'checked';
				}
				if ($row->fonction1 != 'pere' && $row->fonction1 != 'mere') {
					$checkautre1 = 'checked';
					$autre1 = $row->fonction1;
				}
				if (isset ( $row->nom2 )) {
					$nom2 = $row->nom2;
				}
				if (isset ( $row->prenom2 )) {
					$prenom2 = $row->prenom2;
				}
				if (isset ( $row->adresse21 )) {
					$adresse21 = $row->adresse21;
				}
				if (isset ( $row->adresse22 )) {
					$adresse22 = $row->adresse22;
				}
				if (isset ( $row->cp2 )) {
					$cp2 = $row->cp2;
				}
				if (isset ( $row->ville2 )) {
					$ville2 = $row->ville2;
				}
				if (isset ( $row->mail2 )) {
					$mail2 = $row->mail2;
				}
				if (isset ( $row->tel21 )) {
					$tel21 = $row->tel21;
				}
				if (isset ( $row->tel22 )) {
					$tel22 = $row->tel22;
				}
				if (isset ( $row->tel23 )) {
					$tel23 = $row->tel23;
				}
				if (isset ( $row->fonction2 )) {
					if ($row->fonction2 == 'pere') {
						$checkpere2 = 'checked';
					}
					if ($row->fonction2 == 'mere') {
						$checkmere2 = 'checked';
					}
					if ($row->fonction2 != 'pere' && $row->fonction2 != 'mere') {
						$checkautre2 = 'checked';
						$autre2 = $row->fonction2;
					}
				}
			}
		}
		
		$form = '
			<article >
				<h3>' . $titreform . '</h3>
				<form id="form_infirmier" method="post" >';
		if ($type == 'Ajout' || $type == 'Demand') {
			$vmp = $this->genererMDP ();
			$form = $form . '
					<div >
					Identifiant : <input type="text" name="identifiant" id="identifiant" placeholder="email infirmier" value="' . $identifiant . '" required/></br>
					Mot de passe : <input type="text" readonly name="mp" id="mp" value="' . $vmp . '"></br>
					</div>
					
			';
		} else {
			$form = $form . '
			<div style="visibility: hidden;">
					Identifiant : <input type="text" name="identifiant" id="identifiant" placeholder="email infirmier" value="' . $identifiant . '" required/></br>
					Mot de passe : <input type="text"  name="mp" id="mp" value=""></br>
					</div>
							';
		}
		$form = $form . ' 
					<input type="text" name="nom" id="nom" placeholder="Nom infirmier" value="' . $nom1 . '" required/>
					<input type="text" name="prenom" id="prenom" placeholder="Prenom infirmier" value="' . $prenom1 . '" required/></br>
					<input type="date" name="date_naiss" id="date_naiss" placeholder="Date de naissance" value="' . $date_naiss . '" required/></br>
					<input type="text" name="adresse1" id="adresse1" placeholder="Adresse" value="' . $adresse11 . '" required/>
					<input type="text" name="adresse2" id="adresse2" placeholder="Complément Adresse" value="' . $adresse12 . '" /></br>
					<input type="text" name="cp" id="cp1" placeholder="Code Postal" value="' . $cp1 . '" required/>
					<input type="text" name="ville" id="ville" placeholder="Ville" value="' . $ville1 . '" required/></br>
					<input type="text" name="mail_id" id="mail_id" placeholder="email" value="' . $mail1 . '" required/></br>
					<input type="text" name="tel1" id="tel1" placeholder="Tel fixe" value="' . $tel11 . '" required/>
					<input type="text" name="tel2" id="tel1" placeholder="Tel portable" value="' . $tel12 . '" />
					<input type="text" name="tel3" id="tel2" placeholder="Tel travail" value="' . $tel13 . '" /></br>
					
					<input id="submit" type="submit" name="send" class="button" value="' . $libelbutton . '" />
				</form>
				<script>function hd(){ $(\'#modal\').hide();}</script>
				<div  id="modal" >
										<h1>Informations !</h1>
										<div id="dialog1" ></div>
										<a class="no" onclick="hd();">OK</a>
				</div>
			<article >
	<script>
	$("#modal").hide();
	//Initialize the tooltips
	$("#form_infirmier :input").tooltipster({
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
	$("#form_infirmier").submit(function( e ){
        e.preventDefault();
		$("#modal").hide();
	
		var $url="ajax/valide_ajout_infirmier.php";
		if($("#submit").prop("value")=="Modifier"){$url="ajax/valide_modif_famille.php";}
		if($("#submit").prop("value")=="Supprimer"){$url="ajax/valide_supp_famille.php";}
		if($("#submit").prop("value")=="Soumettre"){$url="ajax/valide_demand_famille.php";}
		if($("#form_infirmier").valid())
		{
			/* Données du post */
			var formData = {
				"mp"					: $("#mp").val(),
				"nom" 					: $("#nom").val().toUpperCase(),
				"prenom"				: $("#prenom").val(),
				"adresse1"				: $("#adresse1").val(),
				"adresse2"				: $("#adresse2").val(),
				"cp"					: $("#cp").val(),
				"ville"					: $("#ville").val(),
				"email"					: $("#mail_id").val(),
				"tel1"					: $("#tel1").val(),
				"tel2"					: $("#tel2").val(),
				"tel3"					: $("#tel3").val(),
				"date_naiss"			: $("#date_naiss").val(),
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
						var $msg="erreur-></br><ul style=\"list-style-type :decimal;padding:0 5%;\">";
						if (data.errors.message) {
							$x=data.errors.message;
							$msg+="<li>";
							$msg+=$x;
							$msg+="</li>";
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
						$msg="";
						if(data.message){$msg+="</br>";$x=data.message;$msg+=$x;}
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
  
	$("#form_infirmier").validate({
		rules:
		{
							
			"nom1": {required: true},
			"prenom1": {required: true},
			"adresse1": {required: true},
			"tel11": {required: true,regex: /^(\+33|0033|0)[0-9]{9}$/},
			"tel12": {regex: /^(\+33|0033|0)[0-9]{9}$/},
			"tel13": {regex: /^(\+33|0033|0)[0-9]{9}$/},
			"tel21": {regex: /^(\+33|0033|0)[0-9]{9}$/},
			"tel22": {regex: /^(\+33|0033|0)[0-9]{9}$/},
			"tel23": {regex: /^(\+33|0033|0)[0-9]{9}$/},
			"cp1":{required: true,regex:/^\d{5}$/},
			"ville1": {required: true},
			"mail1": {required: true,regex: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/},
			"cp2":{regex:/^\d{5}$/},
			"mail2": {regex: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/}
		},
		messages:
		{
        	"nom1":
          	{
            	required: "Vous devez saisir un nom valide"
          	},
			"prenom1":
          	{
            	required: "Vous devez saisir un prenom valide"
          	},
			"adresse1":
			{
            	required: "Vous devez saisir une adresse valide"
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
	public function retourne_carroussel_bootstrap() {
		$retour = '
				<style type="text/css">
					#carroussel {
						max-width: 600px;
						max-height: 150px;
					}
					.carousel {
						max-width: 600px;
						max-height: 150px;
					}
				
					.carousel img {
						max-width: 600px;
						max-height: 150px;
					}
				</style>
				';
		
		$retour = $retour . '
				<div id="carroussel" class="carousel slide" data-ride="carousel">
				  <!-- Indicators -->
				  <ol class="carousel-indicators">
				    <li data-target="#carroussel" data-slide-to="0" class="active"></li>
				    <li data-target="#carroussel" data-slide-to="1"></li>
				    <li data-target="#carroussel" data-slide-to="2"></li>
				    <li data-target="#carroussel" data-slide-to="3"></li>
				  </ol>
				
				  <!-- Wrapper for slides -->
				  <div class="carousel-inner" role="listbox">
				    <div class="item active">
				      <img class="img-responsive center-block" src="image/defil/1.jpg" alt="Chania">
				    </div>
				
				    <div class="item">
				      <img class="img-responsive center-block" src="image/defil/2.jpg" alt="Chania">
				    </div>
				
				    <div class="item">
				      <img class="img-responsive center-block" src="image/defil/3.jpg" alt="Flower">
				    </div>
				
				    <div class="item">
				      <img class="img-responsive center-block" src="image/defil/4.jpg" alt="Flower">
				    </div>
				  </div>
				
				  <!-- Left and right controls -->
				  <a class="left carousel-control" href="#carroussel" role="button" data-slide="prev">
				    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				    <span class="sr-only">Previous</span>
				  </a>
				  <a class="right carousel-control" href="#carroussel" role="button" data-slide="next">
				    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				    <span class="sr-only">Next</span>
				  </a>
				</div>
				';
		return $retour;
	}
	public function affiche_liste_famille($type) {
		if ($type == 'Supp') {
			$titreform = 'Suppression famille';
		}
		if ($type == 'Modif') {
			$titreform = 'Modification famille';
		}
		$retour = '
				<style type="text/css">
    			table {border-collapse: collapse;}
				tr:nth-of-type(odd) {background: #eee;}
				tr:nth-of-type(even) {background: #eff;}
				tr{color: black;}
				th {background: #333;color: white;}
				td, th {padding: 6px;border: 1px solid #ccc;}
				</style>
				<article >
				<h3>' . $titreform . '</h3><form method="post">
    	<table>
    		<thead>
        		<tr>
            		<th >Identifiant Famille</th>
            		<th >Nom RP1</th>
            		<th >Prénom RP1</th>
    				<th ></th>
        		</tr>
    		</thead>
    		<tbody >';
		$result = $this->vpdo->liste_famille ();
		if ($result != false) {
			while ( $row = $result->fetch ( PDO::FETCH_OBJ ) ) 
			// parcourir chaque ligne sélectionnée
			{
				
				$retour = $retour . '<tr>
    			<td>' . $row->identifiant . '</td>
    			<td>' . $row->nom1 . '</td>
    			<td>' . $row->prenom1 . '</td>
    			
    			<td Align=center><input onClick="this.form.submit();" type="checkbox" name="nom_checkbox[]" value="' . $row->id_famille . '" /></td>
    			</tr>';
			}
		}
		$retour = $retour . '</tbody></table></form></article>';
		return $retour;
	}
	
	public function affiche_infos_infirmier()
	{
		$tab = array();
		$tab['email'] = $_SESSION['email'];
		$tab_infirmier = $this->vpdo->retourne_infos_infirmier($tab);
		$retour = '
			<h3 class="profile-title"> Mon profil </h3>
	        <div class="profile-header-container">
	    		<div class="profile-header-img">
	                <img class="img-circle" src="./image/logo.jpg" />
	                <!-- badge -->
	                <div class="rank-label-container">
	                    <span class="label label-default rank-label">'.$tab_infirmier[nom].'</span>
	                </div>
	            </div>
	        </div>
				';
		return $retour;
	}
}

?>
