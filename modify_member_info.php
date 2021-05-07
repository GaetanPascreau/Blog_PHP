<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog_modify_member_info</title>
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

        <h1>My super blog : MODIFY YOUR PROFILE</h1>
        <br />

        <!--AFFICHAGE DE L'AVATAR ET DU PSEUDO-->
        <div class="avatar_center">
                <img src="<?php
                                if(isset($_SESSION['avatar']))
                                {
                                    echo $_SESSION['avatar'];  
                                }
                           ?>"
                />
            <p>
                <?php
                    if (isset($_SESSION['pseudo']))
                    {
                        echo '<strong>'.$_SESSION['pseudo'];
                    }
                ?>
            </p>     
        </div> 
        <br />

        <!--MESSAGE D'ERREUR-->
        <?php
            if(isset($_GET['message']) AND $_GET['message']!='')
            {
                echo '<h3><span style="color:red"><strong>'.$_GET['message'].'</strong></h3></span>';
            }
        ?>
        <br />

        <?php
            //On se connecte à la bdd
            include 'db_connection.php';

            //On récupère les infos du membre en bdd
            $req = $bdd -> prepare('SELECT id, pseudo, date_of_birth, city, job, hobbies, instant_messaging, share_email FROM membres WHERE pseudo = :pseudo');
            $req -> execute(array(
                'pseudo' => $_SESSION['pseudo']
            ));
            $result = $req -> fetch();
            $req -> closeCursor();
        ?>
       
        <!--CHANGEMENT DES INFOS-->
        <div class="container-sm">
            <form action="modify_member_info_post.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="new_date_of_birth" class="form-label">
                        date of birth (dd/mm/YYYY) :
                    </label>
                        <br />
                    <input type="date" name="new_date_of_birth" id="new_date_of_birth" class="form-control"
                        value="<?php echo $result['date_of_birth']; ?>">
                    <br /><br />
                </div>

                <div class="mb-3">
                    <label for="new_city" class="form-label">
                        city :
                    </label>
                        <br />
                    <input type="text" name="new_city" id="new_city" class="form-control"
                        value="<?php echo $result['city']; ?>">
                    <br /><br />
                </div>

                <div class="mb-3">
                    <label for="new_job" class="form-label">
                        job :
                    </label>
                        <br />
                    <input type="text" name="new_job" id="new_job" class="form-control"
                        value="<?php echo $result['job']; ?>">
                    <br /><br />
                </div>

                <div class="mb-3">
                    <label for="new_hobbies" class="form-label">
                        hobbies :
                    </label>
                        <br />
                    <input type="text" name="new_hobbies" id="new_hobbies" class="form-control"
                        value="<?php echo $result['hobbies']; ?>">
                    <br /><br />
                </div>

                <div class="mb-3">
                    <label for="new_instant_messaging" class="form-label">
                        instant messaging :
                    </label>
                        <br />
                    <input type="text" name="new_instant_messaging" id="new_instant_messaging" class="form-control"
                        value="<?php echo $result['instant_messaging']; ?>">
                    <br /><br />
                </div>

                <hr noshade="noshade" width="300" size="3" />
                <br />

                <button type="submit" class="btn btn-primary">
                    Apply changes
                </button>
                <br /><br />
            </form>

            <form action="members_page.php" method="post">
                <button type="submit" class="btn btn-secondary">
                    Back to Member page
                </button>
            </form>
        </div>
    </body>
</html>