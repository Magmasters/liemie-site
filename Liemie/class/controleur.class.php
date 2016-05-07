<?php
class controleur {
	protected $vpdo;
	protected $db;
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
		// $retour = $this->retourne_carroussel_bootstrap();
		$articles = $this->vpdo->retourne_article_par_type('EQUIPE');
		$retour = '';
		
		if ($articles == null)
		{
			return $retour;
		}
		
		while ($article = $articles->fetchObject())
		{
			$retour = $retour. '
			<article>
				<h2>'.$article->TITRE.'</h2>
				'.$article->CONTENU.'
			</article>
			';
		}
		
		return $retour;
	}
	
	public function retourne_contact() {
		// $retour = $this->retourne_carroussel_bootstrap();
		$articles = $this->vpdo->retourne_article_par_type('CONTACT');
		$retour = '';
		
		if ($articles == null)
		{
			return $retour;
		}
		
		while ($article = $articles->fetchObject())
		{
			$retour = $retour. '
			<article>
				<h2>'.$article->TITRE.'</h2>
				'.$article->CONTENU.'
			</article>
			';
		}
	
		return $retour;
	}
	
	public function retourne_article_accueil() {
		// $retour = $this->retourne_carroussel_bootstrap();
		$articles = $this->vpdo->retourne_article_par_type('ACCUEIL');
		$retour = '';
		
		if ($articles == null)
		{
			return $retour;
		}
		
		while ($article = $articles->fetchObject())
		{
			$retour = $retour. '
			<article>
				<h2>'.$article->TITRE.'</h2>
				'.$article->CONTENU.'
			</article>
			';
		}
		
		return $retour;
	}
	
	public function retourne_temoignage() {
		return '
		<article>
			<h1>Témoignages</h1>
		</article>';
	}
	
	public function retourne_formulaire_login() {
		
		return '
					<!-- Modal -->
					<div class="modal fade" id="modalConnexion" tabindex="-1" role="dialog" 
					     aria-labelledby="myModalLabel" aria-hidden="true">
					    <div class="modal-dialog">
					        <div class="modal-content">
					            <!-- Modal Header -->
					            <div class="modal-header">
					                <button type="button" class="close" 
					                   data-dismiss="modal">
					                       <span aria-hidden="true">&times;</span>
					                       <span class="sr-only">Close</span>
					                </button>
					                <h4 class="modal-title" id="myModalLabel">
					                    Connexion - accés sécurisé
					                </h4>
					            </div>
					            
					            <!-- Modal Body -->
					            <div class="modal-body">
					                
					                <form role="form" name="login" id="login" method="post">
										<div class="form-group">
											<label for="email">Identifiant (email)</label>
											<input autocomplete="on" type="text" class="form-control input-lg" id="email" name="email" value="">
										</div>
										<div class="form-group">
											<label for="mdp">Mot de passe</label>
											<input autocomplete="off" type="password" class="form-control input-lg" id="mdp" name="mdp" value="">
										</div>
										<div class="checkbox">
											<label for="rblogin">Type de compte</label>
											<input type="radio" name="rblogin" id="rbp"  value="rbp" required/>Patient
											<input type="radio" name="rblogin" id="rbi"  value="rbi" required/>Infirmier
											<input type="radio" name="rblogin" id="rba" value="rba" required/>Administrateur
										</div>
										<button type="submit" class="btn btn-default">Valider</button>
				
										<button type="button" class="btn btn-secondary"><a href="restitution_mdp.php"><img src="./image/user_edit.png" width="16" height="16"> Mot de passe oublié </a></button>
				
										<!-- Conteneur des messages retournés par le script de validation ajax -->
										<div id="message-infos-login">
										</div>
				
					                </form>
					                
					                
					            </div>
					            
					            <!-- Modal Footer -->
					            <div class="modal-footer">
					                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					            </div>
					        </div>
					    </div>
					</div>
					
				
					<script>
				
					function home(){ document.location.href="index.php";}
				
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
						$("#message-infos-login").hide();
										
						var $url="ajax/valide_connect.php";
						if($("#login").valid())
						{
							$categ="infirmier";
							if($("input[type=radio][name=rblogin]:checked").attr("value")=="rbp"){$categ="patient";}
							if($("input[type=radio][name=rblogin]:checked").attr("value")=="rbi"){$categ="infirmier";}
							if($("input[type=radio][name=rblogin]:checked").attr("value")=="rba"){$categ="admin";}
							var formData = {
							"email" 				: $("#email").val().toUpperCase(),
				   			"mdp"					: $("#mdp").val(),
				   			"categ"					: $categ												   		
							};	
											
							var filterDataRequest = $.ajax(
				    		{
																
				        		type: "POST", 
				        		url: $url,
				        		dataType: "json",
								encode  : true,
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
				
										//$("#message-infos-login").toggleClass("alert alert-danger");
				
										$("#message-infos-login").attr("class","alert alert-danger");
								}
								else
								{
										$msg="";
										if(data.message){$msg+="</br>";$x=data.message;$msg+=$x;}
				
										$("#message-infos-login").attr("class","alert alert-success");
									    
										window.setTimeout(function(){
									
									        //après 3 sec on redirige vers la page index.php
									        window.location.href = "index.php";
									
									    }, 3000);
								}
								
								//On affiche le message retourné par la page de validation ajax.
								$("#message-infos-login").html($msg);
								$("#message-infos-login").show();
				
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
				            	required: "Vous devez choisir un type de compte"
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
	
	public function retourne_formulaire_reinit_mdp($idjeton, $user, $categ) {
	
		if (!$this->vpdo->validite_jeton($idjeton, $user, $categ)) {
			return '
						<div class="alert alert-danger">
							<p> Jeton invalide </p>
						</div>
						<button type="button" class="btn btn-secondary"><a href="index.php"><img src="./image/exit.png" width="16" height="16"> Retour accueil </a></button>
					';
		}
	
		return '
	
			<div class="well">
				<form name="login_oubli" id="login_oubli" method="post">
					<div>
						<label for="mdp_resti">Nouveau mot de passe</label>
						<input autocomplete="on" type="password" class="form-control input-lg" id="mdp_resti" name="mdp_resti" value="">
					</div>
					<div>
						<label for="mdp_resti_confirm">Confirmation mot de passe</label>
						<input autocomplete="on" type="password" class="form-control input-lg" id="mdp_resti_confirm" name="mdp_resti_confirm" value="">
					</div>
					<div id="checkerror">
					<br>
					</div>
	
					<input name="send" class="btn btn-lg btn-success" type="submit" value="Envoyer">
				</form>
			</div>
	
			<script>function hd(){ $(\'#modal\').hide();}</script>
			<script>function home(){ document.location.href="index.php";}</script>
			<div  id="modal" >
				<h1>Informations !</h1>
				<div id="dialog1" ></div>
				<a class="no" onclick="hd();home();">OK</a>
			</div>
	<script>
	$("#modal").hide();
	//Initialize the tooltips
	$("#login_oubli :input").tooltipster({
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
	$("#login_oubli").submit(function( e ){
        e.preventDefault();
		$("#modal").hide();
	
		var $url="ajax/valide_reinit_mdp.php";
	
		$idjeton = '. json_encode($idjeton) .'
		$user = '. json_encode($user) .'
		$categ = '. json_encode($categ) .'
	
		if($("#login_oubli").valid())
		{
			var formData = {
				"mdp" 					: $("#mdp_resti").val(),
				"mdp2" 					: $("#mdp_resti_confirm").val(),
	   			"categ"					: $categ,
				"idjeton" 				: $idjeton,
				"user"					: $user,
			};
	
			var filterDataRequest = $.ajax(
    		{
	
        		type: "POST",
        		url: $url,
        		dataType: "json",
				encode	: true,
        		data	: formData,
	
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
	
	$("#login_oubli").validate({
		rules:
		{
	
			"mdp_resti": {required: true},
			"mdp_resti_confirm": {required: true}
		},
		messages:
		{
        	"mdp_resti":
          	{
            	required: "Les mot de passes doivent être identiques !"
          	},
			"mdp_resti_confirm":
			{
            	required: "Les mot de passes doivent être identiques !"
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
	
	public function retourne_formulaire_mdp_oublie() {
		return '
	
			<div class="well">
				<form name="resitution_mdp" id="resitution_mdp" method="post">
					<div>
						<label for="email_oubli">Identifiant (email)</label>
						<input autocomplete="on" type="text" class="form-control input-lg" id="email_oubli" name="email_oubli" value="">
					</div>
					<div class="list-group-item">
						<label for="rblogin_oubli">Type de compte</label>
						<input type="radio" name="rblogin_oubli" id="rbp"  value="rbp" required/>Patient
						<input type="radio" name="rblogin_oubli" id="rbi"  value="rbi" required/>Infirmier
						<input type="radio" name="rblogin_oubli" id="rba" value="rba" required/>Administrateur
					</div>
					<div id="checkerror">
					<br>
					</div>
	
					<input name="send" class="btn btn-lg btn-success" type="submit" value="Envoyer">
				</form>
			</div>
	
			<script>function hd(){ $(\'#modal\').hide();}</script>
			<script>function home(){ document.location.href="index.php";}</script>
			<div  id="modal" >
				<h1>Informations !</h1>
				<div id="dialog1" ></div>
				<a class="no" onclick="hd();home();">OK</a>
			</div>
	<script>
	$("#modal").hide();
	//Initialize the tooltips
	$("#resitution_mdp :input").tooltipster({
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
	$("#resitution_mdp").submit(function( e ){
        e.preventDefault();
		$("#modal").hide();
	
		var $url="ajax/valide_restitution_mdp.php";
		if($("#resitution_mdp").valid())
		{
			$categ="infirmier";
			if($("input[type=radio][name=rblogin_oubli]:checked").attr("value")=="rbp"){$categ="patient";}
			if($("input[type=radio][name=rblogin_oubli]:checked").attr("value")=="rbi"){$categ="infirmier";}
			if($("input[type=radio][name=rblogin_oubli]:checked").attr("value")=="rba"){$categ="admin";}
			var formData = {
			"email" 				: $("#email_oubli").val().toUpperCase(),
   			"categ"					: $categ,
			};
	
			var filterDataRequest = $.ajax(
    		{
	
        		type: "POST",
        		url: $url,
        		dataType: "json",
				encode	: true,
        		data	: formData,
	
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
	
	$("#resitution_mdp").validate({
		rules:
		{
				
			"email_oubli": {required: true},
			"rblogin_oubli": {required: true}
		},
		messages:
		{
        	"email_oubli":
          	{
            	required: "Vous devez saisir un identifiant valide"
          	},
			"rblogin_oubli":
			{
            	required: "Vous devez choisir un type de compte"
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
				$identifiant = "Non trouvé...";
			}
		}
		
		$form = '
			<article >
				<h3>' . $titreform . '</h3>
				<form id="form_infirmier" method="post" role="form" class="formulaire-infirmier" >';
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
							$("#boutonCopier").text().select();
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
		if($("#form_infirmier").valid())
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
  
	$("#form_infirmier").validate({
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
	
	
	public function retourne_formulaire_article($type, $idarticle = "") {
		$form = '';
		$titre = '';
		$contenu = '';
		$type_article = '';
	
		if ($type == 'Ajout') {
			$titreform = 'Formulaire ajout article';
			$libelbutton = 'Ajouter';
		}
		if ($type == 'Supp') {
			$titreform = 'Formulaire suppression article';
			$libelbutton = 'Supprimer';
		}
		if ($type == 'Modif') {
			$titreform = 'Formulaire modification article';
			$libelbutton = 'Modifier';
		}
		if ($type == 'Supp' || $type == 'Modif') {
			$article = $this->vpdo->retourne_article_par_id($idarticle);
			if ($article != null) {
				$titre = $article->TITRE;
				$contenu = $article->CONTENU;
				$type_article = $article->TYPE;
			}
			else {
				$titre = "Non trouvé...";
			}
		}
	
		$form = '
			<article >
				<h3>' . $titreform . '</h3>
				<form id="form_article" method="post" role="form" >';
		$form = $form . '
					<div class="form-group">
						<label for="titre">Titre de l\'article</label>
						<input type="text" class="form-control" id="titre" name="titre" value='.$titre.'>
					</div>
					<div class="form-group">
					<label for="type_article">Type de l\'article</label>
					<select name="type_article" id="type_article">
						<option value="ACCUEIL">Accueil</option> 
						<option value="EQUIPE">Equipe</option>
						<option value="CONTACT">Contact</option>
					</select>
					</div>
								
					<div class="form-group">
						<label for="contenu">Contenu de l\'article</label>
						<textarea rows="4" cols="50" name="contenu" id="contenu" class="ckeditor">'.$contenu.'</textarea>
					</div>
					
					<input id="submit" type="submit" name="send" class="button" value="' . $libelbutton . '" />
				</form>
				
				<script>
					$("#type_article").val('.json_encode($type_article).');
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
	$("#form_article :input").tooltipster({
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
	$("#form_article").submit(function( e ){
        e.preventDefault();
		$("#modal").hide();
				
		$idarticle = 0;
		$contenu = CKEDITOR.instances.contenu.getData();
	
		var $url="ajax/valide_ajout_article.php";
		if($("#submit").prop("value")=="Modifier"){
			$idarticle = '. json_encode($idarticle) .'
			$url="ajax/valide_modif_article.php";
		}
		if($("#submit").prop("value")=="Supprimer"){
			$idarticle = '. json_encode($idarticle) .'
			$url="ajax/valide_supp_article.php";
		}
		if($("#submit").prop("value")=="Ajouter"){$mdp = $("#mp").val();}
		if($("#form_article").valid())
		{
			/* Données du post */
				
			var formData = {
				"type_article"			: $("#type_article").val(),
				"titre" 				: $("#titre").val(),
				/*"contenu"				: $("#contenu").text(),*/
				"contenu"				: $contenu,
				"idarticle"				: $idarticle,
			};
	
			var filterDataRequest = $.ajax(
    		{
	
        		type: "POST",
        		url: $url,
        		dataType: "json",
				encode	: true,
        		data	: formData,
	
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
	
	$("#form_infirmier").validate({
		rules:
		{
				
			"titre": {required: true},
			"contenu": {required: true},
			"type_article": {required: true},
			"adresse2": {required: true},
		},
		messages:
		{
        	"titre":
          	{
            	required: "Vous devez saisir un titre valide."
          	},
			"contenu":
          	{
            	required: "Vous devez saisir du texte ici."
          	},
			"type_article":
			{
            	required: "Veuillez selectionner un type."
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
	
	public function affiche_liste_articles($type) {
		if ($type == 'Supp') {
			$titreform = 'Suppression article';
		}
		if ($type == 'Modif') {
			$titreform = 'Modification article';
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
            		<th >Type</th>
            		<th >Titre</th>
    				<th ></th>
        		</tr>
    		</thead>
    		<tbody >';
		$result = $this->vpdo->liste_articles ();
		if ($result != false) {
			while ( $row = $result->fetch ( PDO::FETCH_OBJ ) )
			// parcourir chaque ligne sélectionnée
			{
	
				$retour = $retour . '<tr>
    			<td>' . $row->TYPE . '</td>
    			<td>' . $row->TITRE . '</td>
    
    			<td Align=center><input onClick="this.form.submit();" type="checkbox" name="checkbox_nom[]" value="' . $row->ID_ARTICLE . '" /></td>
    			</tr>';
			}
		}
		$retour = $retour . '</tbody></table></form></article>';
		return $retour;
	}
	
	public function affiche_liste_infirmiers($type) {
		
		$info = "";
		$libelbutton = "Valider";
		
		if ($type === 'Supp') {
			$titreform = 'Suppression infirmier';
			$info = 'Cette page vous permet d\'effectuer une recherche parmis
						les infirmiers existants et de supprimer le profil souhaité.';
			$libelbutton = "Supprimer";
		}
		if ($type === 'Modif') {
			$titreform = 'Modification infirmier';
			$info = 'Cette page vous permet d\'effectuer une recherche parmis
						les infirmiers existants et de modifier le profil souhaité.';
			$libelbutton = "Modifier";
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
				<h3>' . $titreform . '</h3>
				<div class="alert alert-info">
						'. $info .'
				</div>
				<form method="post">
					<fieldset class="form-group">
						<label for="idinfirmier">Infirmier</label>
						<select name="idinfirmier" id="idinfirmier" class="js-data-example-ajax">
							<option>Saisissez un nom d\'infirmier</option>
						</select>
					</fieldset >
					
					<fieldset class="form-group">
						<input id="submit" type="submit" class="btn btn-default" value="' . $libelbutton . '">
					</fieldset>
				</form>
				
					<script>
							//Mise en forme de l\'affichage des élèments dans le champ de sélection
							function formatVisite (infirmier) {
							  if (infirmier.loading) return infirmier.text;
			
							  var markup = "<div class=\'select2-result-repository clearfix\'>" +
								"<div class=\'select2-result-repository__avatar\'><img src=\'" + infirmier.avatar_url + "\' /></div>" +
								"<div class=\'select2-result-repository__meta\'>" +
								  "<div class=\'select2-result-repository__title\'>" + infirmier.full_name + "</div>";
			
							  markup += "</div></div>";
			
							  return markup;
							}
			
							function formatVisiteSelection (infirmier) {
							  return infirmier.full_name || infirmier.text;
							}
				
							$("#idinfirmier").select2({
								  ajax: {
									url: "ajax/recherche_patient_infirmier.php",
									dataType: "json",
									type: "POST",
									delay: 500,
									data: function (params) {
									  return {
										q: params.term, // search term
										page: params.page,
										type: "infirmier", //spécifier le type recherché ("infirmier" ou "patient")
									  };
									},
									processResults: function (data, params) {
									  // parse the results into the format expected by Select2
									  // since we are using custom formatting functions we do not need to
									  // alter the remote JSON data, except to indicate that infinite
									  // scrolling can be used
									  params.page = params.page || 0;
			
									  return {
										results: data.items,
										pagination: {
										  more: (params.page * 3) < data.total_count
										}
									  };
									},
									cache: true
								  },
								  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
								  minimumInputLength: 3,
								  templateResult: formatVisite,
								  templateSelection: formatVisiteSelection
							});
				
							$("#idinfirmier").on("select2:select", function(e) {
							    //console.log(e.params);
								//console.log(e.params.data.id);
								$idinfirmier = e.params.data.id;
							});
					</script>
				
				
				';
		
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
