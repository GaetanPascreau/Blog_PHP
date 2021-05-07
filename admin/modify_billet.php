<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog_modify_a_post</title>
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

        <h1>My super blog : Modify a Post</h1>

        <!--AFFICHAGE DES MESSAGES D'ERREUR -->
        <div id="annonces">
            <?php
                if(isset($_GET['message']))
                {
                  echo '<span style="color:red; font-size: 1.5em"><strong>'.$_GET['message'].'</strong></span>';
                }
            ?>
        </div>

        <!--RECUPERATION DU POST A MODIFIER -->
        <?php
            // on appelle la page qui permet la connexion à la bdd   
            include '../db_connection.php'; 

            //Récupération du billet sélectionné via l'URL
            if(isset($_GET['billet']) && $_GET['billet']!='')
            {
                $req = $bdd -> prepare('SELECT id, titre, contenu, date_creation FROM billets WHERE id=?');
                $req -> execute(array($_GET['billet']));
                $result = $req -> fetch();
                $req -> closeCursor();
            }
            
        ?>

        <!-- FORMULAIRE DE MODIFICATION DU BILLER SELECTIONNE-->
            <br />
            <div class="creation_billet">
                <br />
                <form action="modify_billet_post.php?id=<?php                                   
                                                            if(isset($_GET['billet']))
                                                            {
                                                                echo htmlspecialchars($_GET['billet']);
                                                            }
                                                        ?>" method="post">
                    <label for="billet_title">Title :</label>
                    <input type="text" name="billet_title" 
                            value="<?php                                                                     
                                        if(isset($_GET['billet']))
                                        {
                                            echo $result['titre'];
                                        }
                                    ?>"/>
                    <br /><br />

                    <textarea name="billet_content" id="billet_content" cols="30" rows="10">
                        <?php
                            if(isset($_GET['billet']))
                            {
                                echo $result['contenu'];
                            }
                        ?>
                    </textarea>
                    <br /><br />

                    <button type="submit" class="btn btn-primary">
                        Send
                    </button>
                </form>
            </div>
        <br /><br />
    </body>
</html> 