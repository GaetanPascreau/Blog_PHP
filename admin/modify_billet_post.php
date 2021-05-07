<?php
	//connexion à la bdd  
    include '../db_connection.php'; 

	//Update du billet avec les données entrées dans le formulaire
	$req = $bdd -> prepare('UPDATE billets SET titre= :titre, contenu= :contenu, date_creation= NOW() WHERE id= :id');
	$req -> execute(array(
		'titre' => $_POST['billet_title'],
		'contenu' => $_POST['billet_content'],
		'id' => $_GET['id'] 
	));
	//on ferme la requête
	$req -> closeCursor();
	//on redirige vers la page admin_posts.php
	header('location: admin_posts.php');
?>