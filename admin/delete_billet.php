<?php
	//connexion à la bdd  
    	include '../db_connection.php';

	//Suppression du billet sélectionné
	$req = $bdd -> prepare('DELETE FROM billets WHERE id=?');
	$req -> execute(array($_GET['billet']));

	//Suppression des commentaires liés au billet supprimé
	//On ferme la première requête
    $req ->closeCursor();
    $req = $bdd -> prepare('DELETE FROM commentaires WHERE id_billet=?');
	$req -> execute(array($_GET['billet']));

	//on redirige vers la page index_ameliore_bis.php
	header('location: admin_posts.php');
	//On ferme la requête
    $req ->closeCursor();
?>