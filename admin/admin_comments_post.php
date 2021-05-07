<?php
echo 'hello !<br />';

	//connection à la bdd
	include '../db_connection.php';
echo 'connecté !<br />'; 

	//on prépare la nouvelle entrée
	if(isset($_POST['auteur']) && $_POST['auteur']!='' && isset($_POST['commentaire']) && $_POST['commentaire']!='' && $_POST['commentaire']!='Your comment here' && isset($_GET['billet']) && $_GET['billet']!='')
	{
		echo 'j\'ai tout récupéré !<br />';
		$req = $bdd ->prepare('INSERT INTO commentaires (id_billet, auteur, commentaire, date_commentaire) VALUES(:billet, :auteur, :commentaire, NOW())');
		//on enregistre la nouvelle entrée dans la bdd
		$req->execute(array(
			'billet' => $_GET['billet'],
			'auteur'=> $_POST['auteur'],
			'commentaire'=> $_POST['commentaire']
		));
		echo 'enregistré en bdd !<br />';
		//on redirige vers la page commentaires.php
		header('location: admin_comments.php?billet='.strip_tags($_GET['billet']));
	}
	else
	{
		//on redirige vers la page commentaires.php sans apporter de modification
		$message = 'Please enter a comment !';
		header('location: admin_comments.php?billet='.strip_tags($_GET['billet']).'&message='.strip_tags($message));
	}
?>