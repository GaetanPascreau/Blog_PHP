<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Blog_check_other_members</title>
		<link href="style.css" rel="stylesheet" >
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	</head>

	<body>
		<!--IMPORT DE JQUERY, POPPER ET BOOTSTRAP-->
      	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <!--BARRE DE MENUS-->
        <?php include 'menubar_for_members.php'; ?>

		<h1>My super blog : All the Blog Members</h1>
		<br />

		<!--connexion à la base de données-->
		<?php include 'db_connection.php'; ?>

		<?php
			//récupération des membres à partir de la bdd (pour en afficher 5 par page)
			//1) on compte le nombre total de membres pour définir le nombre de pages à créer
			$req = $bdd->query('SELECT COUNT(*) AS nb_membres FROM membres'); 
			$result = $req->fetch();										
			$nb_pages = ceil($result['nb_membres']/5);  //ceil arrondit le nombre de pages au nombre supérieur
			$req->closeCursor();

			//si on a saisi: ?page=xxx dans l'URL
			if(isset($_GET['page'])) 
			{
				$_GET['page'] = (int) $_GET['page']; 

				//si ce n° est >0 et inférieur au nbr de pages
				if($_GET['page']>0 AND $_GET['page']<=$nb_pages) 
				{
					$limite = 5 * ($_GET['page']-1);  //on calcule l'index de la 1ère entrée à afficher
					$req = $bdd->prepare('SELECT id, pseudo, avatar_path FROM membres ORDER BY id LIMIT :limite, 5');
					$req->bindValue('limite', $limite, PDO::PARAM_INT);
					$req->execute() or die(print_r($req ->errorInfo()));
				}

				//si la page n'existe pas on affiche un message d'erreur, puis le contenu de la dernière page
				else  
				{
					echo '<p style="color: red">Sorry, the page ' .$_GET['page']. ' doesn\'t exist !</p>';  
					$req = $bdd->query('SELECT id, pseudo, avatar_path FROM membres ORDER BY id LIMIT 0, 5') or die(print_r($req -> errorInfo()));
				}
			}

			//si on n'entre pas de numéro de page dans l'URL, on affiche la première page par défaut
			else  
			{
				$req = $bdd ->query('SELECT id, pseudo, avatar_path FROM membres ORDER BY id LIMIT 0, 5') or die(print_r($req -> errorInfo()));
			} 

			//affichage des liens pour changer de page
			echo '<p class="liens_pages">Move to page : '; 
 			for ($i=1; $i<=$nb_pages; $i++)
			{
    			echo '<a href="check_other_members.php?page=' . $i . '">' . $i . ' &nbsp;</a>';
			}
			echo '</p><br />'; 

			//affichage des 10 membres de la page sélectionnée 
			while($result = $req->fetch())
			{
		?>
			<div class="tableau">	
				<table>
					<tr>
						<td>
							<a href="member_details.php?id=<?php echo $result['id']; ?>">
								<img src="<?php echo $result['avatar_path']; ?>">
							</a>
						</td>
						<td>
							<a href="member_details.php?id=<?php echo $result['id']; ?>">
								<strong><?php echo(htmlspecialchars($result['pseudo'])); ?></strong>
							</a>
						</td>
					</tr>
			  	</table>
			</div>
		<?php
			}
			$req->closeCursor();      
		?>

		<form action="check_other_members.php" method="post">
            <button type="submit" class="btn btn-secondary">
                    Refresh
            </button>
        </form>
	</body>
</html>