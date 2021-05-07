<?php
	if(isset($_POST['billet_title']) && $_POST['billet_title']!='' && isset($_POST['billet_content']) && $_POST['billet_content']!='' && $_POST['billet_content']!='Your post here')
	{
		//connexion à la bdd  
    	include '../db_connection.php';

		//enregistrement du billet dans la bdd
		$req = $bdd -> prepare('INSERT INTO billets (titre, contenu, date_creation) VALUES (:titre, :contenu, NOW())');
		$req -> execute(array(
			'titre' => $_POST['billet_title'],
			'contenu' => $_POST['billet_content']
		));
		//on ferme la requête
		$req -> closeCursor();
		//on redirige vers la page index_ameliore_bis.php
		header('location: admin_posts.php');
	}
	else
	{
		$message = 'Please choose a Title and add a Content !';
		header('location: admin_posts.php?message='.$message);
	}
	
?>