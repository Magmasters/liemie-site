<?php
class page_base_securisee_admin extends page_base {
	public function __construct($p) {
		parent::__construct ( $p );
	}
	public function affiche() {
		if (! isset ( $_SESSION ['email'] ) || ! isset ( $_SESSION ['type'] )) {
			echo '<script>document.location.href="index.php"; </script>';
		} else {
			if ($_SESSION ['type'] != 'admin') {
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
			<li><a href="">Modifier mot de passe</a></li>
			<li role="separator" class="divider"></li>

			<li class="dropdown dropdown-submenu"><a href="#">Famille</a>
				<ul class="dropdown-menu">
					<li><a href="ajout_famille.php">Inscrire une famille</a></li>
					<li><a href="modif_famille.php">Modifier une famille</a></li>
					<li><a href="">Supprimer une famille</a></li>
					<li><a href="">Choisir une famille</a></li>
				</ul></li>
			<li role="separator" class="divider"></li>
			<li class="dropdown dropdown-submenu"><a href="#">Enfant</a>
				<ul class="dropdown-menu">
					<li><a href="">Inscrire un enfant</a></li>
					<li><a href="">Modifier une enfant</a></li>
					<li><a href="">Supprimer une inscription enfant</a></li>
				</ul></li>
		</ul></li>
</ul>
<?php
	}
}
