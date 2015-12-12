<?php
class page_base {
	protected $right_sidebar;
	protected $left_sidebar;
	protected $titre;
	protected $js = array (
			'jquery-2.1.4.min',
			'bootstrap.min' 
	);
	protected $css = array (
			'perso',
			'bootstrap.min' 
	);
	protected $page;
	protected $metadescription = "Kali�mie";
	protected $metakeyword = array (
			'Kali�mie',
			'Visites m�dicales' 
	);
	public function __construct($p) {
		$this->titre = $p;
	}
	public function __set($propriete, $valeur) {
		switch ($propriete) {
			case 'css' :
				{
					$this->css [count ( $this->css ) + 1] = $valeur;
					break;
				}
			case 'js' :
				{
					$this->js [count ( $this->js ) + 1] = $valeur;
					break;
				}
			case 'metakeyword' :
				{
					$this->metakeyword [count ( $this->metakeyword ) + 1] = $valeur;
					break;
				}
			case 'titre' :
				{
					$this->titre = $valeur;
					break;
				}
			case 'menu' :
				{
					$this->menu = $valeur;
					break;
				}
			case 'metadescription' :
				{
					$this->metadescription = $valeur;
					break;
				}
			case 'right_sidebar' :
				{
					$this->right_sidebar = $this->right_sidebar . $valeur;
					break;
				}
			case 'loginbar' :
				{
					$this->loginbar = $this->loginbar . $valeur;
					break;
				}
			case 'left_sidebar' :
				{
					$this->left_sidebar = $this->left_sidebar . $valeur;
					break;
				}
		}
	}
	/**
	 * ****************************Gestion des styles *********************************************
	 */
	/* Insertion des feuilles de style */
	private function affiche_style() {
		foreach ( $this->css as $s ) {
			echo "<link rel='stylesheet'  href='css/" . $s . ".css' />\n";
		}
	}
	/**
	 * ****************************Gestion du javascript *********************************************
	 */
	/* Insertion js */
	private function affiche_javascript() {
		foreach ( $this->js as $s ) {
			echo "<script src='js/" . $s . ".js'></script>\n";
		}
	}
	/**
	 * ****************************affichage metakeyword *********************************************
	 */
	private function affiche_keyword() {
		echo '<meta name="keywords" content="';
		foreach ( $this->metakeyword as $s ) {
			echo utf8_encode ( $s ) . ',';
		}
		echo '" />';
	}
	/**
	 * **************************** Affichage de la partie entÃªte **************************************
	 */
	protected function affiche_entete() {
		echo '<header class="page-header hidden-xs hidden-sm">
				<div class="row">
    				<div class="col-xs-12">
						<h1>
							Kaliémie
						</h1>
						<h3>
							<strong>Visites médicales</strong>
						</h3>
 					</div>
				</div>
			</header>
			<header class="page-header hidden-md hidden-lg">
				<div class="row">
    				<div class="col-xs-12">
						<h1>
							Kaliémie
						</h1>
						<h3>
							<strong>Visites médicales</strong>
						</h3>
 					</div>
				</div>
            </header>';
	}
	/**
	 * **************************** Affichage du menu **************************************
	 */
	protected function affiche_menu() {
		echo '
				<ul class="nav navbar-nav">
					<li><a   href="index.php" >Accueil </a></li>
					<li><a   href="equipe.php" >l\'Equipe</a></li>
					<li class=""><a   href="temoignage.php" >Témoignages</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a  href="contact.php">Contact</a></li>
				</ul>
				';
	}
	protected function affiche_menu_connexion() {
		if (! (isset ( $_SESSION ['email'] ) && isset ( $_SESSION ['type'] ))) {
			echo '
					<ul class="nav navbar-nav navbar-right">
						<li><a  href="connect.php">Connexion</a></li>
					</ul>';
		} else {
			echo '
					<ul class="nav navbar-nav navbar-right">
				<li><a  href="deconnect.php">Déconnexion</a></li>
					</ul>';
		}
	}
	public function affiche_entete_menu() {
		echo '
		<div id="menu_horizontal">
			<nav class="navbar navbar-inverse">
				<div class="container-fluid">
				    <div class="navbar-header">
      					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"  data-target="#monmenu" aria-expanded="false">
        					<span class="sr-only">Toggle navigation</span>
        					<span class="icon-bar"></span>
        					<span class="icon-bar"></span>
        					<span class="icon-bar"></span>
      					</button>
						<p class="visible-xs navbar-text"><mark>Menu</mark></p>
    				</div>
					<div class="collapse navbar-collapse" id="monmenu">
				';
	}
	public function affiche_footer_menu() {
		echo '
						
					
				</div>
			</nav>
		</div>';
	}
	
	/**
	 * **************************************** remplissage affichage colonne **************************
	 */
	public function rempli_right_sidebar() {
		return '

				<article>
					<h3>News</h3>
                </article>
				';
	}
	
	/**
	 * **************************************** Affichage du pied de la page **************************
	 */
	private function affiche_footer() {
		echo '
		<!-- Footer -->
		<footer class="footer-basic-centered">

			<p class="footer-company-motto">Liemie Magmasters</p>

			<p class="footer-links">
				<a href="#">Accueil</a>
				<a href="#">L\'Equipe</a>
				<a href="#">A propos</a>
				<a href="#">Contact</a>
			</p>

			<p class="footer-company-name">Magmasters &copy; 2015</p>

		</footer>
		';
	}
	
	/**
	 * ******************************************* Fonction permettant l'affichage de la page ***************
	 */
	public function affiche() {
		?>
<!DOCTYPE HTML>
<html lang='fr'>
<head>
<title><?php echo $this->titre; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo $this->metadescription; ?>" />

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
					<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
					<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
					<![endif]-->
					
					<?php $this->affiche_keyword(); ?>
					<?php $this->affiche_javascript(); ?>
					<?php $this->affiche_style(); ?>
				</head>
<body>
	<div class="global container-fluid">

		<?php $this->affiche_entete(); ?>
		<?php $this->affiche_entete_menu(); ?>
		<?php $this->affiche_menu(); ?>
		<?php $this->affiche_menu_connexion(); ?>
		<?php $this->affiche_footer_menu(); ?>
						
		<div class="row">
			<div class="col-md-10">
				<?php echo $this->left_sidebar; ?>
			</div>
		<div class="row">
			<div class="col-md-2">
				<?php echo $this->right_sidebar;?>
			</div>
		</div>
		</div>
		<?php $this->affiche_footer(); ?>
	</div>
</body>
</html>
<?php
	}
}

?>
