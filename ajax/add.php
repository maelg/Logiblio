<?php
if(!isset($_POST['book'])) {
	exit();
}
$book = $_POST['book'];

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=mael', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$req = $bdd->prepare('INSERT INTO logiblio_books VALUES("", :isbn, :titre, :auteur, :langue, :publication, :pages, :image, :proprietaire)');
$req->execute(array(
	'isbn' => $book['isbn'], 
	'titre' => $book['titre'],
	'auteur' => $book['auteur'], 
	'langue' => $book['langue'], 
	'publication' => $book['publication'], 
	'pages' => $book['pages'], 
	'image' => $book['image'], 
	'proprietaire' => $book['proprietaire']
	));