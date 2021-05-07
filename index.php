<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog_index</title>
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

        <!--AFFICHAGE DES MESSAGES D'ERREUR et du MESSAGE D'INTRO-->
        <div id="annonces">
            <?php
                if(isset($_GET['message']))
                {
                  echo '<span style="color:red"><strong>'.$_GET['message'].'</strong></span>';
                }
            ?>

            <p>Welcome to my blog !</p>
            <p>Feel free to register by clicking on the "Sign in" link above, as a member you'll have acces to a ton of goodies!</p>
            <br />
        </div>

        <!--AFICHAGE DES POSTS-->
        <?php include 'db_connection.php'; // on appelle la page qui permet la connexion à la bdd ?>
        <?php include 'show_pages.php'; // on appelle la page qui récupère les différentes pages de billets ?>
        <?php 
            //affichage des liens pour changer de page
            echo '<p class="liens_pages"><strong>Go to page : </strong>'; 
            for ($i=1; $i<=$nb_pages; $i++)
            {
                echo '<a href="index.php?page=' . $i . '">' . $i . ' &nbsp;</a>';
            }
            echo '</p><br />';
        
            //affichage des 5 messages de la page sélectionnée 
            while($result = $req -> fetch())
            {
        ?>
                <div class="news">
        	        <?php include 'billet.php'; // on fait appel à la page de structure d'un billet ?>
                    <p>
                        <!--on y ajoute un lien vers les commentaires du billet-->
        		      <em> <a href="comments.php?billet=<?php echo $result['id']; ?>">Comments</a></em>
                    </p>
                </div>
                <br />
        <?php
        }
        ?>
        <br /><br />
	</body>
</html>	