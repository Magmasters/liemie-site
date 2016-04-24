<?php
class page_base_securisee_infirmier extends page_base {
	public function __construct($p) {
		parent::__construct ( $p );
	}
	public function affiche() {
		if (! isset ( $_SESSION ['email'] ) || ! isset ( $_SESSION ['type'] )) {
			echo '<script>document.location.href="index.php"; </script>';
		} else {
			if ($_SESSION ['type'] != 'infirmier') {
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
		<ul class="dropdown-menu">
			<li><a href="">Modifier mes informations</a></li>
			<li><a href="liste_visites.php">Mon planning</a></li>
			<li><a href="">Indisponibilités</a></li>
			<li><a href="">Mes spécialités</a></li>
		</ul></li>
</ul>
<?php
	}
	
}
