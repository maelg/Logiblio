<!DOCTYPE html>
<html ng-app>
	<head>
		<meta charset="utf-8" />
		<title>Logiblio - Ajouter un livre</title>
		<link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="css/app.css" />
		<link rel="stylesheet" href="css/add.php.css" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/angular.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	</head>
	<body>
		<div class="col-md-3" id="sidebar">
			<header class="page-header">
				<h1>Logiblio</h1>
			</header>
			<div class="hero-unit">
				<p>Ajouter un livre à Logiblio</p>
				<nav>
					<a href="index.html" class="btn btn-lg btn-primary">Retour</a>
					<a href="musique.html" class="btn btn-lg btn-primary">Aide</a>
				</nav>
			</div>
			<footer>
				<p>Copyright 2013 Mael Guillossou</p>
			</footer>
		</div>
		<div class="col-md-9" id="page">
			<div class="jumbotron">
				<h1>Ajouter un livre</h1>
				<p>Pour ajouter un livre à la base de données de Logiblio, vous pouvez entrer son code ISBN, les informations du livre serons alors recherché</p>
				<p><a class="btn btn-primary btn-md" role="button">Guider moi</a></p>
			</div>
			<div class="content">
			<form class="bloc add" method="post" action="add.php">
				<div>
								<h2>Recherche avec un code ISBN</h2>

					<input type="text" name="isbn" id="isbn" placeholder="Code ISBN" />
									<p>
					<a href="#" class="btn btn-primary">Rechercher</a>
				</p>

				</div>
<?php $owner = (!isset($_POST['owner'])) ? null : $_POST['owner']; ?>
				<div>
					<h3>Livre appartenant à</h3>
					<input type="radio" name="owner" value="thierry" id="thierry" <?php if($owner == 'thierry') echo 'checked'; ?> /> <label for="thierry">Thierry</label><br>
			        <input type="radio" name="owner" value="beatrice" id="beatrice" <?php if($owner == 'beatrice') echo 'checked'; ?> /> <label for="beatrice">Béatrice</label><br>
			        <input type="radio" name="owner" value="mathilde" id="mathilde" <?php if($owner == 'mathilde') echo 'checked'; ?> /> <label for="mathilde">Mathilde</label><br>
			        <input type="radio" name="owner" value="mael" id="mael" <?php if($owner == 'mael') echo 'checked'; ?> /> <label for="mael">Maël</label><br>
			        <input type="radio" name="owner" value="elouan" id="elouan" <?php if($owner == 'elouan') echo 'checked'; ?> /> <label for="elouan">Elouan</label>
			    </div>
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
	    $infos['proprietaire'] = $owner;
	  
	    if( isset($book->volumeInfo->imageLinks) )
	        $infos['image'] = str_replace('&edge=curl', '', $book->volumeInfo->imageLinks->thumbnail);  
	    else
	    	$infos['image'] = null;
?>
			<div class="bloc livre">
				<div>
					<img src="<?php echo $infos['image']; ?>" style="float: left;" alt="">
					<p class="info"><?php echo $infos['titre']; ?> - <?php echo $infos['auteur']; ?><br><small><?php echo $infos['isbn']; ?></small><br>Nbr de pages <?php echo $infos['pages']; ?></p>
				</div>
				<div class="confirm">
					<p>Est-ce le bon livre ?</p>
					<p><a href="#" class="btn btn-success" id="confirm">Oui</a><a href="#" class="btn btn-danger">Non</a></p>
				</div>
			</div>
<?php
	}
	else{  
	   echo 'Livre introuvable';  
	}
?>
			<script type="text/javascript">
				function notifyMe(msg, tps) {
				  // Let's check if the browser supports notifications
				  if (!("Notification" in window)) {
				    alert("This browser does not support desktop notification");
				  }

				  // Let's check if the user is okay to get some notification
				  else if (Notification.permission === "granted") {
				    // If it's okay let's create a notification
				    var notification = new Notification(msg);
				    setTimeout(function(){
						notification.close();
					}, tps);

				  }

				  // Otherwise, we need to ask the user for permission
				  // Note, Chrome does not implement the permission static property
				  // So we have to check for NOT 'denied' instead of 'default'
				  else if (Notification.permission !== 'denied') {
				    Notification.requestPermission(function (perm) {
			        if (perm == 'granted') {
			            notifyMe(msg, tps)
			        }
			    });
				}
			} 

				if($('#confirm'))
					$('#confirm').focus();
				else
					$('#isbn').focus();

				$('#confirm').click(function() {
					$('.confirm').slideUp();
					$.ajax({
					  type: "POST",
					  url: "ajax/add.php",
					  data: {book : <?php echo json_encode($infos); ?>}
					})
					  .done(function( msg ) {
					    notifyMe('Livre enregistré', 5000)
					    $('#isbn').focus();
					});
				});
			</script>
<?php
}
?>
		</div>
		</div>
		</div>
	</body>
</html>