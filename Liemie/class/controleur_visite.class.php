<?php
include_once ('autoload.php');

class controleur_visite extends controleur {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function retourne_liste_visites() {
		
		$formulaire_ajout_modif = "";
		$droit_ajout = false;
		if ($_SESSION['type'] === "admin" || $_SESSION['type'] === "infirmier")
		{
			$formulaire_ajout_modif = $this->retourne_formulaire_visite();
			$droit_ajout = true;
		}
		
		$retour = "";
		
		$retour .= '
					<script>
						function verifCreationVisite()
						{
							recupererVisites();
						}
					</script>
					<!-- Modal visite -->
					<div class="modal fade" id="modalVisite" role="dialog" 
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
					                    Ajouter ou modifier une visite
					                </h4>
					            </div>
					            
					            <!-- Modal Body -->
					            <div class="modal-body">
					                
								'.$formulaire_ajout_modif.'
					                
					                
					            </div>
					            
					            <!-- Modal Footer -->
					            <div class="modal-footer">
					                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="verifCreationVisite()">Fermer</button>
					            </div>
					        </div>
					    </div>
					</div>
		';
		
		$retour .= "<div id='calendrier'></div>";
		
		$retour .= '
			<script>
				//Objet "view" du calendrier affiché (permet d\"accéder aux proprioétés dates)
				var current_view;
				
				var json_visites;
				function set_visites(visites){
				    json_visites = visites;
				}
				function get_visites(){
					console.log("get_visites :");
					console.log(json_visites);
				    return json_visites;
				}
				
				/*
				* Récupère les visites depuis la base de donnée
				* (entre les dates "startDate" et "endDate")
				* et les affiche sur le calendrier.
				*/
				function recupererVisites() {
				
					var startDate = current_view.intervalStart.format("YYYY-MM-DD HH:MM:ss");
					var endDate = current_view.intervalEnd.format("YYYY-MM-DD HH:MM:ss");
				
					$.ajax({
						url: "ajax/recherche_visites.php",
						dataType: "json",
				        type: "POST",
						async: false,
				        data: {
							date_debut : startDate,
							date_fin : endDate,
						},
				        success: function(data){
				        	set_visites(data.visites);
				        }
					});
				
					$("#calendrier").fullCalendar("removeEvents");
					$("#calendrier").fullCalendar("addEventSource", get_visites());
					$("#calendrier").fullCalendar("refetchEvents");
				}
				
				var calendrier = $("#calendrier").fullCalendar(
				{
					/*
						header option will define our calendar header.
						left define what will be at left position in calendar
						center define what will be at center position in calendar
						right define what will be at right position in calendar
					*/
					header:
					{
						left: "prev,next today",
						center: "title",
						right: "month,agendaWeek,agendaDay"
					},
					/*
						defaultView option used to define which view to show by default,
						for example we have used agendaWeek.
					*/
					defaultView: "agendaWeek",
					/*
						selectable:true will enable user to select datetime slot
						selectHelper will add helpers for selectable.
					*/
					selectable: '.json_encode($droit_ajout).',
					selectHelper: '.json_encode($droit_ajout).',
					/*
						when user select timeslot this option code will execute.
						It has three arguments. Start,end and allDay.
						Start means starting time of event.
						End means ending time of event.
						allDay means if events is for entire day or not.
					*/
					select: function(start, end, allDay) 
					{
						/*
							after selection user will be promted for enter title for event.
						*/
						$("#modalVisite").modal("show");
						$("#date_visite").val(start.format("YYYY-MM-DD"));
						$("#heure_visite").val(end.format("HH:MM"));
						var title ="Nouvelle visite";
						if (title)
						{
							calendrier.fullCalendar("renderEvent",
								{
									title: title,
									start: start,
									end: end,
									allDay: false
								},
								true // make the event "stick"
							);
						}
						calendrier.fullCalendar("unselect");
					},
					/*
					* Evènement javascript exécuté lors du clic sur un évènement (visite) du calendrier
					* On fait une requete POST à la page "recherche_visites.php" avec l\'id
					* de la visite sur laquelle l\'utilisateur a cliqué.
					*/
					 eventClick: function(event) {
        				if (event.id) {
							$.ajax({
								url: "ajax/recherche_visites.php",
								dataType: "json",
						        type: "POST",
								async: false,
						        data: {
									idvisite : event.id,
								},
						        success: function(data){
									//data.visite : tableau json avec les données de la visite retournée
									//console.log(data);
									
									//On décompose le champ "datetime" date de la visite pour récupérer le jour et l\'heure séparément
									var tabDateHeure = data.visite.start.split(" ");
									$("#date_visite").val(tabDateHeure[0]);
									$("#heure_visite").val(tabDateHeure[1]);
									$url_visite = "ajax/valide_modif_visite.php";
									$idvisite_aModifier = data.visite.id;
									console.log("Affichage du formulaire pour modification de la visite (id)" + $idvisite_aModifier);
							
						        }
							});
							$("#modalVisite").modal("show");
        				}
   					 },
					/*
						editable: true allow user to edit events.
					*/
					editable: true,
					/*
						events is the main option for calendrier.
						for demo we have added predefined events in json object.
					*/
				
					//events: get_visites(),
				
					viewRender: function(view, element){
							current_view = view;
							recupererVisites();
					},
				
					eventRender: function(event, element) {
						 Tipped.create(element, event.description);
					}
				});
			</script>
		';
		
		return $retour;
	}
	
	public function retourne_formulaire_visite($idvisite = "") {
		$form = '';
		$idinfirmier = -1;
		$idpatient = -1;
		
		$libelle_soin = "";
		$description_soin = "";
		$date_visite = "";
		$heure_visite = "";
		
		/*
		 * Peut poser des problèmes de mémoire si un nombre important
		 * d'infirmiers & patients dans la base de données.
		 */
		//$sth_patients = $this->vpdo->liste_patient();
		//$sth_infirmiers = $this->vpdo->liste_infirmiers();
		
		$libelbutton = 'Valider';
		$form = '
				<form id="form_visite" method="post" role="form" class="formulaire-infirmier" >';

		if ($_SESSION['type'] === "admin")
		{
			$form .= '
						<fieldset class="form-group">
							<label for="idinfirmier">Infirmier</label>
							<select name="idinfirmier" id="idinfirmier" class="js-data-example-ajax">
								<option>Saisissez un nom d\'infirmier</option>
							</select>
						</fieldset >
				
						<fieldset class="form-group">
							<label for="idpatient">Patient</label>
							<select name="idpatient" id="idpatient" class="js-data-example-ajax">
								<option>Saisissez un nom de patient</option>
							</select>
						</fieldset>
			
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
				
				
							$("#idpatient").select2({
								  ajax: {
									url: "ajax/recherche_patient_infirmier.php",
									dataType: "json",
									type: "POST",
									delay: 500,
									data: function (params) {
									  return {
										q: params.term, // search term
										page: params.page,
										type: "patient", //spécifier le type recherché ("infirmier" ou "patient")
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
								  templateResult: formatVisite, // omitted for brevity, see the source of this page
								  templateSelection: formatVisiteSelection // omitted for brevity, see the source of this page
							});
				
							$("#idpatient").on("select2:select", function(e) {
							    //console.log(e.params);
								//console.log(e.params.data.id);
								$idpatient = e.params.data.id;
							});
						</script>
			';
		} 
		else if ($_SESSION['type'] === "infirmier")
		{
			$form .= '
						<fieldset class="form-group">
							<label for="selectSoins">Soins</label>
							<select name="selectSoins" id="selectSoins" class="js-data-example-ajax">
								<option>Rechercher un soin</option>
							</select>
						</fieldset >
		
						<script>
							//Mise en forme de l\'affichage des élèments dans le champ de sélection
							function formatSoin (soin) {
							  if (soin.loading) return soin.text;
		
							  var markup = "<div class=\'select2-result-repository clearfix\'>" +
								  "<div class=\'select2-result-repository__title\'>" + soin.libelle_soin;
					
							  markup += "</div></div>";
		
							  return markup;
							}
		
							function formatSoinSelection (soin) {
							  return soin.libelle_soin || soin.text;
							}
			
							$("#selectSoins").select2({
								  ajax: {
									url: "ajax/recherche_soin.php",
									dataType: "json",
									type: "POST",
									delay: 500,
									data: function (params) {
									  return {
										q: params.term, // search term
										page: params.page,
									  };
									},
									processResults: function (data, params) {
									  params.page = params.page || 1;
		
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
								  templateResult: formatSoin,
								  templateSelection: formatSoinSelection
							});
			
							$("#selectSoins").on("select2:select", function(e) {
							    console.log(e.params);
							});
						</script>
			';
		}
		
		$form = $form . '
					<fieldset class="form-group">
						<label for"date_visite">Date de la visite</label>
						<input type="date" class="form-control" name="date_visite" id="date_visite" value="' . $date_visite . '" required/>
								
						<label for"heure_visite">Heure de la visite</label>
						<input type="time" class="form-control" name="heure_visite" id="heure_visite" value="' . $heure_visite . '" required/>
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
		
		var $url_visite="ajax/valide_ajout_visite.php";
		//variable utilisée dans le cas où le formulaire est affiché avec les informations
		//d\'une visite déjà existante.
		var $idvisite_aModifier = -1;

		if($("#form_visite").valid())
		{
			/* Données du post */
			var formData = {
				"idinfirmier"			: $idinfirmier,
				"idpatient"				: $idpatient,
				"date_visite"			: $("#date_visite").val(),
				"heure_visite"			: $("#heure_visite").val(),
				"idvisite"				: $idvisite_aModifier,
			};
				
			var filterDataRequest = $.ajax(
    		{
        		type: "POST",
        		url: $url_visite,
        		dataType: "json",
				encode  : true,
        		data: formData,
			});
			filterDataRequest.done(function(data)
			{
				if ( ! data.success)
				{
						$msg="<ul>";
						if (data.errors) {
							$.each(data.errors, function(index, value) {
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
				required: "Le code postal doit être composé 5 chiffres"
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
