<?php
class page_base_securisee_admin extends page_base {
	public function __construct($p) {
		parent::__construct ( $p );
	}
	public function affiche() {
		if (! isset ( $_SESSION ['email'] ) || ! isset ( $_SESSION ['type'] )) {
			echo '<script>document.location.href="index.php"; </script>';
		} else {
			if ($_SESSION ['type'] !== "admin") {
				echo '<script>document.location.href="index.php"; </script>';
			} else {
				parent::affiche ();
			}
		}
	}
	public function affiche_menu() {
		parent::affiche_menu ();
		?>


<ul class="nav navbar-nav">

	<li class="dropdown"><a href="" class="dropdown-toggle"
		data-toggle="dropdown" role="button" aria-haspopup="true"
		aria-expanded="false">Administration<span class="caret"></span></a>
		<ul class="dropdown-menu  multi-level">
			<li><a href="">Modifier le mot de passe</a></li>
			
			<li role="separator" class="divider"></li>

			<li class="dropdown dropdown-submenu"><a href="#">Gestion des infirmiers</a>
				<ul class="dropdown-menu">
					<li><a href="ajout_infirmier.php">Inscrire un infirmier</a></li>
					<li><a href="modif_infirmier.php">Modifier un infirmier</a></li>
					<li><a href="supp_infirmier.php">Supprimer un infirmier</a></li>
				</ul>
			</li>
			
			<li role="separator" class="divider"></li>
			
			<li class="dropdown dropdown-submenu"><a href="#">Gestion des patients</a>
				<ul class="dropdown-menu">
					<li><a href="ajout_patient.php">Inscrire un patient</a></li>
					<li><a href="modif_patient.php">Modifier un patient</a></li>
					<li><a href="supp_patient.php">Supprimer une inscription patient</a></li>
				</ul>
			</li>
			
			<li role="separator" class="divider"></li>
			
			<li class="dropdown dropdown-submenu"><a href="#">Gestion des articles</a>
				<ul class="dropdown-menu">
					<li><a href="ajout_article.php">Ajouter un article</a></li>
					<li><a href="modif_article.php">Modifier un article</a></li>
					<li><a href="supp_article.php">Supprimer un article</a></li>
				</ul>
			</li>
			
			<li role="separator" class="divider"></li>
			
			<li class="dropdown dropdown-submenu"><a href="#">Gestion des visites</a>
				<ul class="dropdown-menu">
					<li><a href="liste_visites.php">Visualiser les visites</a></li>
					<li><a href="ajout_visite.php">Ajouter une visite</a></li>
					<li><a href="supp_visite.php">Supprimer une visite</a></li>
				</ul>
			</li>
		</ul>
	</li>
</ul>
<?php
	}
}
