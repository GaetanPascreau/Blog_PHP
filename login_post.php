<?php
    //connexion à la bdd  
    include 'db_connection.php';
                        
    //récupération de l'utilisateur et de son mdp hashé
    if(isset($_POST['pseudo']) && $_POST['pseudo']!= '' && isset($_POST['password']) && $_POST['password']!= '')
    {
        $pseudo = addslashes(htmlspecialchars(htmlentities(trim($_POST['pseudo']))));
        
        $req = $bdd -> prepare('SELECT id, pass, pseudo, avatar_path FROM membres WHERE pseudo = :pseudo');
        $req -> execute(array(
            'pseudo' => $pseudo
        ));
        $result = $req -> fetch();

        //si le pseudo entré ne se trouve pas dans la bdd alors $result vaut FAUX (= on récupère rien)
        if(!$result)
        {
            $message = 'Invalid nickname or password !';
            header('Location: login.php?message='.htmlspecialchars($message));
        }
        else
        {
            //comparaison du mdp envoyé via le formulaire avec celui présent dans la bdd
            $isPasswordCorrect = password_verify($_POST['password'], $result['pass']);
            if($isPasswordCorrect)
            { 
                session_start();
                $_SESSION['id'] = $result['id'];
                $_SESSION['pseudo'] = $result['pseudo'];
                $_SESSION['avatar'] = $result['avatar_path'];

                //on crée 2 cookies (pseudo et password)
                if(isset($_POST['auto_login']) && $_POST['auto_login']='on')
                {
                    setcookie('pseudo', $_POST['pseudo'], time()+ 30*24*3600, null, null, false, true);
                    setcookie('password', $result['pass'], time()+ 30*24*3600, null, null, false, true); 
                }
                               
                header('Location: session_control.php');
            }
            else
            {
                $message = 'Invalid nickname or password !';
                header('Location: login.php?message='.htmlspecialchars($message));
            }
        } 
        $req -> closeCursor();
    }
?>