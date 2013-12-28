<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Logiblio - Connexion</title>
		<link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="css/app.css" />
		<link rel="stylesheet" href="css/connexion.php.css" />
	</head>
	<body>
		<div class="col-md-3" id="sidebar">
			<header>
				<h1>Logiblio</h1>
			</header>
			<div class="hero-unit">
				<p>Vous devez vous connecter pour acceder à cet espace</p>
				<nav>
					<a href="index.html" class="btn btn-lg btn-primary" class="btn btn-lg btn-primary">Retour</a>
					<a href="musique.html" class="btn btn-lg btn-primary">Aide</a>
				</nav>
			</div>
			<footer>
				<p>Copyright 2013 Mael Guillossou</p>
			</footer>
		</div>
		<div class="col-md-9" id="content">
			<form class="form-signin" role="form">
		        <h2 class="form-signin-heading">Connexion</h2>
		        <input type="text" class="form-control" placeholder="Login" required autofocus>
		        <input type="password" class="form-control" placeholder="Password" required>
		        <label class="checkbox">
		    		<input type="checkbox" value="remember-me"> Remember me
		        </label>
		        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		    </form>
		</div>
	</body>
</html>