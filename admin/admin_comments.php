<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>TP_blog_avec_commentaires_index_for_members</title>
        <link href="../style.css" rel="stylesheet" >
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>

    <body>
        <!--IMPORT DE JQUERY, POPPER ET BOOTSTRAP-->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <!--BARRE DE MENUS-->
        <?php include 'menubar_for_administrator.php'; ?>

        <!--AFFICHAGE DE L'AVATAR ET DU PSEUDO-->
        <div class="avatar">
            <p>Logged as :</p>
                <img src="<?php
                            if(isset($_SESSION['avatar']))
                            {
                                echo '../'.$_SESSION['avatar'];  
                            }
                           ?>"
                />
            <p>
                <?php
                    if (isset($_SESSION['pseudo']))
                    {
                        echo $_SESSION['pseudo'];
                    }
                ?>
            </p>     
        </div> 

        <h1>My Super blog !</h1>

        <!--AFFICHAGE DES MESSAGES D'ERREUR -->
        <div id="annonces">
            <?php
                if(isset($_GET['message']))
                {
                  echo '<span style="color:red; font-size: 1.5em"><strong>'.$_GET['message'].'</strong></span>';
                }
            ?>
        </div>
        <br />


      <!--AFICHAGE DES COMMENTAIRES sur le POST SELECTIONNE-->
        <?php include '../db_connection.php'; // on appelle la page qui permet la connexion à la bdd ?>
    
        <?php
          //Récupération du billet sélectionné via l'URL
          $req = $bdd ->prepare('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y at %Hh%imin%ss\') AS date_creation_fr FROM billets WHERE id=?');
          $req ->execute(array($_GET['billet']));
          $result = $req ->fetch();

          //on teste si le numéro de billet existe dans la bdd), sinon on affiche un message d'erreur
          if(empty($result['id']))
          {
             die('<h1 style="color : red">This post doesn\'t exist !</h1>');
          }
          else
          {
        ?>

        <div class="news">
          <?php include '../billet.php'; // on fait appel à une page qui contient la structure d'un billet ?>
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
            $req->closeCursor();        

            //si on a saisi ?page_commentaires=xxx dans l'URL
            if(isset($_GET['page_commentaires'])) 
            {

              //on convertit le texte correspondant au numéro de page en un int pour faire des opérations
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

                //si la page n'existe pas on affiche un message d'erreur
                else  
                {
                  echo '<p class="erreur_page">Sorry, the comment page n°' .$_GET['page_commentaires']. ' doesn\'t exist !</p>';  
                }
            }

            //si on n'entre pas de numéro de page dans l'URL, on affiche la première page de commentaires
            else  
            {
              $req = $bdd ->prepare('SELECT id, auteur, commentaire, id_billet, DATE_FORMAT(date_commentaire, \'%d/%m/%Y at %Hh%imin%ss\') AS date_commentaire_fr FROM commentaires WHERE id_billet=? ORDER BY date_commentaire DESC LIMIT 0, 5');
                $req->execute(array($_GET['billet'])) or die(print_r($req->errorInfo()));
            }
            echo '</p><br />';

            //on affiche les commentaires tant qu'il y en a
            while($result = $req ->fetch())
            {
          ?>
              <p><strong><?php echo strip_tags($result['auteur']); ?></strong>
                 <em> on <?php echo $result['date_commentaire_fr']; ?></em>
                 &nbsp;&nbsp;&nbsp;&nbsp;
                 <!--On y ajoute un lien vers la page de suppression d'un commentaire-->
                 <em>
                   <a style="color: red" href="delete_comment.php?billet=<?php echo $result['id_billet'];?>&commentaire=<?php echo $result['id'];?>">Delete</a>
                 </em>
              </p>
              <p><?php echo nl2br(strip_tags($result['commentaire'])); ?></p>
              <p>-------------------------------------------------------------------------------------------------------</p>
              <?php
            } 
            $req ->closeCursor();

            //affichage des liens pour changer de page
            echo '<p class="liens_pages"><strong>Go to comment page : </strong>'; 
            for ($i=1; $i<=$nb_pages_commentaires; $i++)
            {
                echo '<a href="admin_posts.php?billet='.$_GET['billet'].'&page_commentaires=' . $i . '">' . $i . ' &nbsp;</a>';
            }
            echo '</p><br />';
            ?>    
          
            <!--FORMULAIRE D'AJOUT DE COMMENTAIRES-->
            <form action="admin_comments_post.php?billet=<?php echo $_GET['billet']; ?>" method="post">
              <h2>Add a comment</h2>
                <br />
              <label for="auteur">Author:</label>
              <input type="text" name="auteur" id="auteur" value="<?php echo $_SESSION['pseudo']; ?>"/>
                <br /><br />
              <textarea name="commentaire" id="commentaire" cols="30" rows="10">Your comment here</textarea>
                <br /><br />
              <button type="submit" class="btn btn-primary">
                 Send
              </button>
            </form> 
        </div>
    <?php
    }
    ?>
  </body>
</html>