<?php
class page_base {
	protected $right_sidebar;
	protected $left_sidebar;
	protected $titre;
	protected $modal_login;
	protected $js = array (
			'jquery-2.1.4.min',
			'bootstrap.min'
	);
	protected $css = array (
			'bootstrap.min',
			'bootstrap_edit.min',
			'perso',
			'freelancer',
	);
	protected $page;
	protected $metadescription = "Kaliémie";
	protected $metakeyword = array (
			'Kaliémie',
			'Visites médicales' 
	);
	public function __construct($p) {
		$this->titre = $p;
	}
	public function __set($propriete, $valeur) {
		switch ($propriete) {
			case 'css' :
				{
					if (!in_array($valeur, $this->css, true)) { array_push($this->css, $valeur); }
					break;
				}
			case 'js' :
				{
				if (!in_array($valeur, $this->js, true)) { array_push($this->js, $valeur); }
					break;
				}
			case 'metakeyword' :
				{
				if (!in_array($valeur, $this->metakeyword, true)) { array_push($this->metakeyword, $valeur); }
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
			case 'modal_login' :
				{
					$this->modal_login = $this->modal_login . $valeur;
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
		
		echo '
		    <!-- Header -->
		    <header>
		        <div class="container">
		            <div class="row">
		                <div class="col-lg-12">
		                    <img class="img-responsive img-logo" src="image/logo.png" alt="">
		                    <div class="intro-text">
		                        <span class="name">KALIEMIE</span>
		                        <hr class="star-light"> 
								<span class="skills">Visites médicales</span>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </header>	
		';
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
			
			echo $this->modal_login.'
					
					<ul class="nav navbar-nav navbar-right">
						<li><a href="" class="" data-toggle="modal" data-target="#modalConnexion">Connexion</a></li>
					</ul>
					
					';
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
			<nav class="navbar navbar-default">
				<div class="container-fluid">
				    <div class="navbar-header">
      					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"  data-target="#monmenu" aria-expanded="false">
        					<span class="sr-only">Toggle navigation</span>
        					<span class="icon-bar"></span>
        					<span class="icon-bar"></span>
        					<span class="icon-bar"></span>
      					</button>
						<p class="visible-xs navbar-text">Menu</p>
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
			<footer class="bottom">
				<div class="container">
					<p class="text-center">Magmasters - Kaliemie 2016.</p>
				</div>
			</footer>';
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

<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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

   <!-- Wrap all page content here -->
    <div id="wrap">
	<div class="global container-fluid">
	
		<?php $this->affiche_entete(); ?>
		<?php $this->affiche_entete_menu(); ?>
		<?php $this->affiche_menu(); ?>
		<?php $this->affiche_menu_connexion(); ?>
		<?php $this->affiche_footer_menu(); ?>
						
		<div class="row">
			<div class="col-md-12">
				<?php echo $this->left_sidebar; ?>
			</div>
		<!--  <div class="row">
			<div class="col-md-2">
				<//?php echo $this->right_sidebar;?>
			</div>
		</div>
		-->
		</div>
		<?php $this->affiche_footer(); ?>
	</div>
	</div>
</body>
</html>
<?php
	}
}

?>
