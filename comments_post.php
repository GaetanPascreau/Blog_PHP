<?php
	if(isset($_GET['billet']) && $_GET['billet']!='' && isset($_POST['auteur']) && $_POST['auteur']!='' && isset($_POST['commentaire']) && $_POST['commentaire']!='' && $_POST['commentaire']!='Your comment here')
	{
		//connexion à la bdd  
    	include 'db_connection.php';

		//on prépare la nouvelle entrée
		$req = $bdd ->prepare('INSERT INTO commentaires (id_billet, auteur, commentaire, date_commentaire) VALUES(:billet, :auteur, :commentaire, NOW())');
		//on enregistre la nouvelle entrée dans la bdd
		$req->execute(array(
			'billet' => $_GET['billet'],
			'auteur'=> $_POST['auteur'],
			'commentaire'=> $_POST['commentaire']
		));
		//on redirige vers la page commentaires.php
		header('location: comments_for_members.php?billet='.strip_tags($_GET['billet']));
	}
	else
	{
		//on redirige vers la page commentaires.php sans apporter de modification
		$message = 'Please enter a comment !';
		header('location: comments_for_members.php?billet='.strip_tags($_GET['billet']).'&message='.strip_tags($message));
	}
?>