<?php
class page_base_securisee_patient extends page_base {
	public function __construct($p) {
		parent::__construct ( $p );
	}
	public function affiche() {
		if (! isset ( $_SESSION ['email'] ) || ! isset ( $_SESSION ['type'] )) {
			echo '<script>document.location.href="index.php"; </script>';
		} else {
			if ($_SESSION ['type'] != 'patient') {
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
		aria-expanded="false">Modifier mon mot de passe<span class="caret"></span></a>
		<ul class="dropdown-menu">
			<li><a href="">Modifier mes informations</a></li>
			<li><a href="">Poster un témoignage</a></li>
			<li><a href="">Planning</a></li>
			<li><a href="">Détails visites</a></li>
		</ul></li>
</ul>
<?php
	}
	
}
