<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <head>
        <meta charset="utf-8" />
        <title>Blog_Login</title>
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

        <h1>My Super Blog : LOGIN</h1>
        <br />

        <!--AFFICHAGE DES MESSAGES D'ERREUR-->
        <?php
         if(isset($_GET['message']) AND $_GET['message']!='')
            {
                echo '<h3><span style="color:red"><strong>'.$_GET['message'].'</strong></h3></span>';
            }
        ?>

        <!--AFFICHAGE DU FORMULAIRE-->
        <div class="container-sm">
            <form action="login_post.php" method="post">
                <div class="form-group">
                    <label for="pseudo">
                      Nickname :
                    </label>
                    <br />
                    <input type="text" name="pseudo" id="pseudo" class="form-control" required="required"
                    value="<?php
                              if(isset($_SESSION['pseudo']) && $_SESSION['pseudo']!='')
                              {
                                  echo $_SESSION['pseudo'];
                              }
                            ?>" />
                    <br /><br /> 
                </div>

                <div class="form-group">
                    <label for="password">
                      Password :
                    </label>
                    <br />
                    <input type="password" name="password" id="password" class="form-control" required="required" />
                    <br /><br />
                </div>

                <div>
                    <input type="checkbox" name="auto_login" id="auto_login" />
                    <label for="auto_login">
                      Automatic login (Remember me !)
                    </label>
                    <br /><br />
                </div>

                <button type="submit" class="btn btn-primary">
                    Login
                </button>
            </form>
        </div>
        <br /><br />

        <form action="index.php" method="post">
            <button type="submit" id="index" class="btn btn-secondary">
                Back to Homepage
            </button>
        </form>
    </body>
</html>