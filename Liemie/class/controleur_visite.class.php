<?php
include_once ('autoload.php');

class controleur_visite extends controleur {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function retourne_liste_visites() {
		
		$formulaire_ajout = "";
		$droit_ajout = false;
		if ($_SESSION['type'] === "admin")
		{
			$formulaire_ajout = $this->retourne_formulaire_visite("Ajout");
			$droit_ajout = true;
		}
		
		$retour = "";
		
		$retour .= '
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
					                    Ajouter une visite
					                </h4>
					            </div>
					            
					            <!-- Modal Body -->
					            <div class="modal-body">
					                
								'.$formulaire_ajout.'
					                
					                
					            </div>
					            
					            <!-- Modal Footer -->
					            <div class="modal-footer">
					                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					            </div>
					        </div>
					    </div>
					</div>
		';
		
		$retour .= "<div id='calendrier'></div>";
		
		$retour .= '
			<script>
				var json_visites;
				function set_visites(visites){
				    json_visites = visites;
				}
				function get_visites(){
					console.log("get_visites :");
					console.log(json_visites);
				    return json_visites;
				}
				
				function recupererVisites(startDate = "", endDate = "") {
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
				}
				
				recupererVisites();
				
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
						/*
							if title is enterd calendar will add title and event into fullcalendrier.
						*/
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
						editable: true allow user to edit events.
					*/
					editable: true,
					/*
						events is the main option for calendrier.
						for demo we have added predefined events in json object.
					*/
				
					//events: get_visites(),
				
					viewRender: function(view, element){
					        var startDate = view.intervalStart.format("YYYY-MM-DD HH:MM:ss");
							var endDate = view.intervalEnd.format("YYYY-MM-DD HH:MM:ss");
							recupererVisites(startDate, endDate);
							console.log("Du " + startDate + " au " + endDate);
							$("#calendrier").fullCalendar("removeEvents");
							$("#calendrier").fullCalendar("addEventSource", get_visites());
							$("#calendrier").fullCalendar("refetchEvents");
					},
				
					eventRender: function(event, element) {
						 Tipped.create(element, event.description);
					}
				});
			</script>
		';
		
		return $retour;
	}
	
	public function retourne_formulaire_visite($type, $idvisite = "") {
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
		}
		
		$form = '
				<h3>' . $titreform . '</h3>
				<form id="form_visite" method="post" role="form" class="formulaire-infirmier" >';
		if ($type == 'Ajout') {
			$form = $form . '
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
						function formatRepo (infirmier) {
						  if (infirmier.loading) return infirmier.text;

						  var markup = "<div class=\'select2-result-repository clearfix\'>" +
							"<div class=\'select2-result-repository__avatar\'><img src=\'" + infirmier.avatar_url + "\' /></div>" +
							"<div class=\'select2-result-repository__meta\'>" +
							  "<div class=\'select2-result-repository__title\'>" + infirmier.full_name + "</div>";

						  markup += "</div></div>";

						  return markup;
						}

						function formatRepoSelection (infirmier) {
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
							  templateResult: formatRepo, // omitted for brevity, see the source of this page
							  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
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
							  templateResult: formatRepo, // omitted for brevity, see the source of this page
							  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
						});
					
						$("#idpatient").on("select2:select", function(e) { 
						    //console.log(e.params);
							//console.log(e.params.data.id);
							$idpatient = e.params.data.id;
						});
					</script>
					
			';
		} else {
			$form = $form . '
					<div id="to_hide">
						
					</div>
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
		
		var $url="ajax/valide_ajout_visite.php";
		if($("#submit").prop("value")=="Modifier"){
			//$idinfirmier = '. json_encode($idinfirmier) .'
			$url="ajax/valide_modif_infirmier.php";
		}
		if($("#submit").prop("value")=="Supprimer"){
			//$idinfirmier = '. json_encode($idinfirmier) .'
			$url="ajax/valide_supp_infirmier.php";
		}
		if($("#submit").prop("value")=="Ajouter"){$mdp = $("#mp").val();}
		if($("#form_visite").valid())
		{
			/* Donn꦳ du post */
							
			var formData = {
				"idinfirmier"			: $idinfirmier,
				"idpatient"				: $idpatient,
				"date_visite"			: $("#date_visite").val(),
				"heure_visite"			: $("#heure_visite").val(),
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
