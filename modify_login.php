<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog_modify_login</title>
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

        <h1>My super blog : LOGIN CHANGE</h1>
        <br />

        <!--MESSAGE D'ALERTE-->
        <div class="message_login_change">
            <p>Here you can either change your login, or your password, or both !</p>
        </div>
        <br />

        <!--MESSAGE D'ERREUR-->
        <?php
            if(isset($_GET['message']) AND $_GET['message']!='')
            {
                echo '<h3><span style="color:red"><strong>'.$_GET['message'].'</strong></h3></span>';
            }
        ?>

        <!--CHANGEMENT DES INFOS DE LOGIN-->
        <div class="container-sm">
            <form action="modify_login_post.php" method="post" enctype="multipart/form-data">

                <!--CHANGEMENT DE PSEUDO-->
                <h2>Change Nickname</h2>
                <br />
                <div class="mb-3">
                    <label for="new_pseudo" class="form-label">
                        New nickname :
                    </label>
                        <br />
                    <input type="text" name="new_pseudo" id="new_pseudo" class="form-control"
                        value="<?php
                                    if(isset($_SESSION['pseudo']) && $_SESSION['pseudo']!='')
                                    {
                                        echo $_SESSION['pseudo'];
                                    }
                                ?>"><br />    <br />
                </div>

                <hr noshade="noshade" width="300" size="5" />
                <br />

                <!--CHANGEMENT DE MOT DE PASSE-->
                <h2>Change password</h2>
                <br />

                <div class="mb-3">
                    <label for="old_password" class="form-label">
                        Old password :
                    </label>
                    <br />
                    <input type="password" name="old_password" id="old_password" class="form-control">
                    <br /><br />
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">
                        New password :
                    </label>
                    <br />
                    <input type="password" name="new_password" id="new_password" class="form-control">
                    <div class="pass-warning">Please use a minmum of 8 characters, with at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 special character among (|/,\;!:?.-_=*$@%)</div>
                    <br /><br />
                </div>

                <div class="mb-3">
                    <label for="new_password-confirm" class="form-label">
                     Confirm new password :
                    </label>
                    <br />
                    <input type="password" name="new_password-confirm" id="new_password-confirm" class="form-control">
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