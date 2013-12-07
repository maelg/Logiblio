<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Logiblio - Connexion</title>
		<link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/core.css" />
		<link rel="stylesheet" href="css/app.css" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<style type="text/css">
			.livre div {
				display: inline-block;
				width: 49%;
				box-sizing: border-box;
			}
		</style>
	</head>
	<body>
		<div id="sidebar">
			<header>
				<h1>Logiblio</h1>
			</header>
			<div class="hero-unit">
				<p>Entrez un code ISBN pour ajouter un livre à Logiblio</p>
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
			<form class="bloc" method="post" action="add.php">
				<h2>Recherche avec un code ISBN</h2>
				<p>
					<input type="text" name="isbn" id="isbn" placeholder="Code ISBN"/>
				</p>
				<p>
					<a href="#" class="btn">Connexion</a>
				</p>
			</form>
<?php
if(isset($_POST['isbn']))
{
	// ou si vous préférez hardcodé  
	// $isbn = '0061234001';  
	  
	$request = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . $_POST['isbn'];  
	$response = file_get_contents($request);  
	$results = json_decode($response);  
	  
	if($results->totalItems > 0){  
	    // avec de la chance, ce sera le 1er trouvé  
	    $book = $results->items[0];  
	  
	    $infos['isbn'] = $book->volumeInfo->industryIdentifiers[0]->identifier;  
	    $infos['titre'] = $book->volumeInfo->title;  
	    $infos['auteur'] = $book->volumeInfo->authors[0];  
	    $infos['langue'] = $book->volumeInfo->language;  
	    $infos['publication'] = $book->volumeInfo->publishedDate;  
	    $infos['pages'] = $book->volumeInfo->pageCount;  
	  
	    if( isset($book->volumeInfo->imageLinks) )
	        $infos['image'] = str_replace('&edge=curl', '', $book->volumeInfo->imageLinks->thumbnail);  
	    else
	    	$infos['image'] = null;
	  
	}  
	else{  
	   echo 'Livre introuvable';  
	}
?>
			<div class="bloc livre">
				<div>
					<img src="<?php echo $infos['image']; ?>" style="float: left;" alt="">
					<p class="info"><?php echo $infos['titre']; ?> - <?php echo $infos['auteur']; ?><br><small><?php echo $infos['isbn']; ?></small><br>Nbr de pages <?php echo $infos['pages']; ?></p>
				</div>
				<div>
					<p>Est-ce le bon livre ?</p>
					<p><a href="#" class="btn" id="confirm">Oui</a><a href="#" class="btn btn-danger">Non</a></p>
				</div>
			</div>
			<script type="text/javascript">
				if($('#confirm'))
					$('#confirm').focus();
				else
					$('#isbn').focus();

				$('#confirm').click(function() {
					$.ajax({
					  type: "POST",
					  url: "ajax/add.php",
					  data: {book : <?php echo json_encode($infos); ?>}
					})
					  .done(function( msg ) {
					    alert( "Data Saved: " + msg );
					});
				});
			</script>
<?php
}
?>
		</div>
		
	</body>
</html>