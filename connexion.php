<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Logiblio - Connexion</title>
		<link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/core.css" />
		<link rel="stylesheet" href="css/app.css" />
	</head>
	<body>
		<div id="sidebar">
			<header>
				<h1>Logiblio</h1>
			</header>
			<div class="hero-unit">
				<p>Vous devez vous connecter pour acceder à cet espace</p>
				<nav>
					<ul class="btns">
						<li><a href="index.html">Retour</a></li>
						<li><a href="musique.html">Aide</a></li>
					</ul>
				</nav>
			</div>
			<footer>
				<p>Copyright 2013 Mael Guillossou</p>
			</footer>
		</div>
		<div id="content">
			<form id="login" method="post" action="traitement.php">
				<h2>Connexion</h2>
				<p>
					<input type="text" name="user" id="user" placeholder="Utilisateur"/>
					<input type="password" name="pass" id="pass" placeholder="Mot de passe"/>
				</p>
				<p>
					<a href="#" class="btn">Connexion</a>
				</p>
			</form>
		</div>
	</body>
</html>