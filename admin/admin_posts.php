<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog_amin_posts</title>
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

        <!--AFFICHAGE DES POSTS-->
        <?php include '../db_connection.php'; // on appelle la page qui permet la connexion à la bdd ?>
        <?php include '../show_pages.php'; // on appelle la page qui récupère les différentes pages de billets ?>

        <?php 
        //affichage des liens pour changer de page
        echo '<p class="liens_pages">Go to page :'; 
        for ($i=1; $i<=$nb_pages; $i++)
        {
            echo '<a href="admin_posts.php?page=' . $i . '">' . $i . ' &nbsp;</a>';
        }
        echo '</p><br />';

        //affichage des 5 messages de la page sélectionnée 
            while($result = $req -> fetch())
            {
        ?>

                <!-- on fait appel à la page de structure d'un billet-->
                <div class="news">
                    <?php include '../billet.php'; ?>
                    <p>
                        <!-- on y ajoute un lien vers la page d'administration des commentaire-->
                        <em> <a href="admin_comments.php?billet=<?php echo $result['id']; ?>">Comments</a></em>
                        
                        <!-- on y ajoute un lien vers la page de modification des billets-->
                        <em><a style="color: green; margin-left: 800px" href="modify_billet.php?billet=<?php echo $result['id']; ?>">Modify</a></em> &nbsp;&nbsp;&nbsp;
                        
                        <!-- on y ajoute un lien vers la page de supression des billets-->
                        <em><a style="color: red" href="delete_billet.php?billet=<?php echo $result['id']; ?>">Delete</a></em>
                    </p>
                </div>
                <br />
        <?php
        }
        ?>
            <br />
            <hr noshade="noshade" width="1200" size="3" />
            <br />

            <!-- FORMULAIRE DE CREATION DES BILLETS-->
            <div class="creation_billet">
                <h3>Add a new post</h3>
                <br />
                <form action="add_billet.php" method="post">
                    <label for="billet_title">Title :</label>
                    <input type="text" name="billet_title" /><br /><br />
                    <textarea name="billet_content" id="billet_content" cols="30" rows="10">Your post here</textarea><br /><br />
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            </div>
        <?php
        //On ferme la requête
        $req -> closeCursor();
        ?>
        <br /><br />
    </body>
</html> 