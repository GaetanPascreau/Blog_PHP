<!DOCTYPE html>
<html>
    <head>
        <head>
        <meta charset="utf-8" />
        <title>Blog_Registration</title>
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

        <h1>My Super Blog : REGISTRATION</h1>
        <br />

        <!--AFFICHAGE DES MESSAGES D'ERREUR-->
        <?php
         if(isset($_GET['message']) AND $_GET['message']!='')
            {
                echo '<h3><span style="color:red"><strong>'.$_GET['message'].'</strong></h3></span>';
            }
        ?>

        <!--AFFICHAGE DU FORMULAIRE-->

        <!-- Formulaire principal, obligatoire-->
        <div class="container-sm">
            <form action="registration_post.php" method="post" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="pseudo" class="form-label">
                        Nickname :
                    </label>
                        <br />
                    <input type="text" name="pseudo" id="pseudo" class="form-control" required="required">
                        <br /><br />
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                         Email :
                    </label>
                        <br />
                    <input type="email" name="email" id="email" class="form-control" required="required">
                        <br /><br />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password :
                    </label>
                        <br />
                    <input type="password" name="password" id="password" class="form-control" required="required">
                    <div class="pass-warning">Please use a minmum of 8 characters, with at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 special character among (|/,\;!:?.-_=*$@%)</div>
                        <br /><br />
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">
                       Confirm password :
                    </label>
                        <br />
                    <input type="password" name="password-confirm" id="password-confirm" class="form-control" required="required">
                        <br /><br />
                </div>

                <div class="mb-3">
                    <label for="avatar" class="form-label">
                       Add an (optional) avatar :
                    </label>
                        <br />
                    <input type="file" name="avatar" id="avatar" class="form-control">
                        <br /><br />
                </div>

                <hr noshade="noshade" width="300" size="5" />
                <br />

                <!-- Formulaire optionnel, facultatif-->
                <h2>Optional information</h2>
                <br />

                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">
                        Date of birth (dd/mm/YYYY):
                    </label>
                    <br />
                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                        <br /><br />
                </div>

                <div class="mb-3">
                    <label for="city" class="form-label">
                        City :
                    </label>
                        <br />
                    <input type="text" name="city" id="city" class="form-control">
                        <br /><br />
                </div>

                <div class="mb-3">
                    <label for="job" class="form-label">
                        Job :
                    </label>
                       <br />
                    <input type="text" name="job" id="job" class="form-control">
                        <br /><br />
                </div>

                <div class="mb-3">
                    <label for="hobbies" class="form-label">
                        Hobbies :
                    </label>
                        <br />
                    <input type="text" name="hobbies" id="hobbies" class="form-control">
                        <br /><br />
                </div>

                <div class="mb-3">
                    <label for="instant_messaging" class="form-label">
                        Instant messaging address :
                    </label>
                        <br />
                    <input type="text" name="instant_messaging" id="instant_messaging" class="form-control">
                        <br /><br />
                </div>

                <div class="form-check">
                    <input type="checkbox" name="share_email" id="share_email" class="form-check-input">
                    <label for="share_email" class="form-check-label">
                        Allow sharing of email address
                    </label>
                        <br /><br />
                </div>

                <hr noshade="noshade" width="300" size="3" />
                    <br />

                <button type="submit" class="btn btn-primary">
                    Registration
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

