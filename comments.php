<!DOCTYPE html>
<html>
	<head>
		  <meta charset="urf-8" />
		  <title>Blog_comments</title>
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
        <?php include 'menubar.php'; ?>

		<h1>My Super blog !</h1>
        <br />

        <!--AFICHAGE DES COMMENTAIRES sur le POST SELECTIONNE-->
        <?php include 'db_connection.php'; // on appelle la page qui permet la connexion à la bdd ?>
		
        <?php
		    //Récupération du billet sélectionné via l'URL
		    $req = $bdd ->prepare('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y at %Hh%imin%ss\') AS date_creation_fr FROM billets WHERE id=?');
		    $req ->execute(array($_GET['billet']));
		    $result = $req ->fetch();

		    //on teste si le numéro de billet existe dans la bdd, sinon on affiche un message d'erreur
		    if(empty($result['id']))
		    {
			    die('<h1 style="color : red">This post doesn\'t exist !</h1>');
		    }
		    else
		    {
		?>

			<div class="news">
				<?php include 'billet.php'; // on fait appel à la page qui contient la structure du billet ?>
            </div>											
            <br />

            <div class="commentaires">
        	   <h2>Comments</h2>
        <?php 
        	//On ferme la première requête
        	$req ->closeCursor();


			//récupération et affichage des commentaires pour le billet sélectionné
        	$req = $bdd->prepare('SELECT COUNT(*) AS nb_commentaires FROM commentaires WHERE id_billet=?'); 
        	$req -> execute(array($_GET['billet']));
        	$result = $req->fetch();

        	//on recupère ce nombre, ceil arrondit au nombre sup. 
       		$nb_pages_commentaires = ceil($result['nb_commentaires']/5);
       		//on ferme la requête, c'est indispensable pour en démarrer une nouvelle ensuite !  
        	$req->closeCursor();        

       		if(isset($_GET['page_commentaires'])) //si on a saisi ?page_commentaires=xxx dans l'URL
        	{
            	//on convertit le texte correspondant au numéro de page en un int pour faire des opérations (>, <, = ...)
            	$_GET['page_commentaires'] = (int) $_GET['page_commentaires'];

            	if($_GET['page_commentaires']>0 AND $_GET['page_commentaires']<=$nb_pages_commentaires)  
            	{
            		//si la page existe, on calcule l'index de la première entrée à afficher sur la page
               		$limite = 5 * ($_GET['page_commentaires']-1);  
                	$req = $bdd->prepare('SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y at %Hh%imin%ss\') AS date_commentaire_fr FROM commentaires WHERE id_billet=:billet ORDER BY date_commentaire DESC LIMIT :limite, 5');
                	$req->bindValue('limite', $limite, PDO::PARAM_INT);
                	$req->bindValue('billet', $_GET['billet'], PDO::PARAM_INT);
                	$req ->execute() or die(print_r($req->errorInfo()));
            	}
            	else  //si la page n'existe pas on affiche un message d'erreur
            	{
                	echo '<p class="erreur_page">Sorry, the comment page n°' .$_GET['page_commentaires']. ' doesn\'t exist !</p>';  
            	}
       		}
       		else  //si il n'y a pas de n° de page dans l'URL, on affiche la première page de commentaires
        	{
            	$req = $bdd ->prepare('SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y at %Hh%imin%ss\') AS date_commentaire_fr FROM commentaires WHERE id_billet=? ORDER BY date_commentaire DESC LIMIT 0, 5');
            	$req->execute(array($_GET['billet'])) or die(print_r($req->errorInfo()));
        	}
        	echo '</p><br />';

        	//on affiche les commentaires
        	while($result = $req ->fetch())
        	{
        ?>
        	<p><strong><?php echo strip_tags($result['auteur']); ?></strong> <em> on <?php echo $result['date_commentaire_fr']; ?></em></p>
        	<p><?php echo nl2br(strip_tags($result['commentaire'])); ?></p>
        	<p>-------------------------------------------------------------------------------------------------------</p>
        <?php
        	} 
        	$req ->closeCursor();

        	//affichage des liens pour changer de page
        	echo '<p class="liens_pages"><strong>Go to comment page : </strong>'; 
       		for ($i=1; $i<=$nb_pages_commentaires; $i++)
        	{
            	echo '<a href="comments.php?billet='.$_GET['billet'].'&page_commentaires=' . $i . '">' . $i . ' &nbsp;</a>';
        	}
        	echo '</p><br />';
        ?>		
       	    </div>
		<?php
		}
		?>
	</body>
</html>