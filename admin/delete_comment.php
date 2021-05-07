<?php
echo 'hello<br />';

	//connexion à la bdd  
    include '../db_connection.php'; 

	//Suppression du commentaire sélectionné
	$req = $bdd -> prepare('DELETE FROM commentaires WHERE id=?');
	$req -> execute(array($_GET['commentaire']));
    $req -> closeCursor();
    echo 'commentaire supprimé<br />';
    
	//on redirige vers la page admin_comments.php du billet sélectionné
	header('location: admin_comments.php?billet='.$_GET['billet']);	
?>