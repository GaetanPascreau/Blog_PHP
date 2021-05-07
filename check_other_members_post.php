<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>members</title>
</head>

<style>
	h1
	{
		text-align: center;
	}
</style>

<body>
	<h1>Members</h1>

<?php

	//connexion à la base de données
	try
	{
		$bdd = new PDO('mysql:host=localhost; dbname=test; charset=utf8', 'root', 'root',
		array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch(Exception $e)
	{
		die('Erreur :' .$e->getMessage());
	}

	//récupération des membres à partir de la bdd (pour en afficher 5 par page)
	//1) on compte le nombre total de membres pour définir le nombre de pages à créer
	$req = $bdd->query('SELECT COUNT(*) AS nb_membres FROM membres'); 
	$result = $req->fetch();										
	$nb_pages = ceil($result['nb_membres']/5);  //ceil arrondit le nombre de pages au nombre supérieur
	$req->closeCursor();

	if(isset($_GET['page'])) //si on a saisi: ?page=xxx dans l'URL
	{
		$_GET['page'] = (int) $_GET['page'];  
		if($_GET['page']>0 AND $_GET['page']<=$nb_pages)  //si ce n° est >0 et inférieur au nbr de pages
		{
			$limite = 5 * ($_GET['page']-1);  //on calcule l'index de la première entrée à afficher sur la page
			$req = $bdd->prepare('SELECT pseudo, avatar_path FROM membres ORDER BY id LIMIT :limite, 5');
			$req->bindValue('limite', $limite, PDO::PARAM_INT);
			$req->execute() or die(print_r($req ->errorInfo()));
		}
		else  //si la page n'existe pas on affiche un message d'erreur, puis le contenu de la dernière page
		{
			echo '<p style="color: red">Sorry, the page ' .$_GET['page']. ' doesn\'t exist !</p>';  
			$req = $bdd->query('SELECT pseudo, avatar_path FROM membres ORDER BY id LIMIT 0, 5') or die(print_r($req -> errorInfo()));
		}
	}
	else  //si on n'entre pas de numéro de page dans l'URL, on affiche la première page par défaut
	{
		
		$req = $bdd ->query('SELECT pseudo, avatar_path FROM membres ORDER BY id LIMIT 0, 5') or die(print_r($req -> errorInfo()));
	} 

	//affichage des liens pour changer de page
	echo '<p>Move to page :'; 
 	for ($i=1; $i<=$nb_pages; $i++)
	{
    	echo '<a href="membres_post.php?page=' . $i . '">' . $i . ' &nbsp;</a>';
	}
	echo '</p><br />'; 

	//affichage des 10 membres de la page sélectionnée = tant qu'il y a une ligne à chercher dans $req on remplit l'array $result
	while($result = $req->fetch())
	{
		//on se protège contre les failles XSS avec htmlspecialchars() ou strip_tags()
		//echo '<img src="$result['avatar_path'] />  ' 
		//.strip_tags($result['pseudo']). '<br /><br />';
		//echo 'membre: '.htmlspecialchars($result['pseudo']). '<br />';
		echo '<table>
				<tr>
					<td><a href=""><img src="'.$result['avatar_path'].'"></a></td>
					<td><strong>'.htmlspecialchars($result['pseudo']). '</strong></td>
				</tr>
			  </table>';
	}
	//on ferme la requête
	$req->closeCursor();      
?>
	<form action="membres_post.php" method="post">
		<input type="submit" value="Rafraîchir" />
	</form>
</body>
</html>