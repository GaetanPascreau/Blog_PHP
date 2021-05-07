<?php
    //connexion à la bdd  
    include 'db_connection.php';

    //On fait les contrôles de remplissage des champs 
    session_start();

    //PARTIE CHANGEMENT DE PSEUDO
    if(isset($_POST['new_pseudo']) && $_POST['new_pseudo']!='' && $_POST['new_pseudo']!= $_SESSION['pseudo'])
    {
        // On ajoute une sécurité
        $new_pseudo = addslashes(htmlspecialchars(htmlentities(trim($_POST['new_pseudo']))));

        // On vérifie si le pseudo entré existe déjà dans notre bdd
        $req = $bdd -> prepare('SELECT id, pseudo FROM membres WHERE pseudo = :new_pseudo');
        $req -> execute(array(
            'new_pseudo' => $new_pseudo
        ));
        $result = $req -> fetch();
        $req -> closeCursor();

        // si ce n'est pas le cas on peut faire la modification.                    
        if(!$result)           
        {
            echo '<br />le pseudo est libre<br />';
            echo '<br />Mon pseudo de session est : '.$_SESSION['pseudo'];
            echo '<br />Mon id de session est : '.$_SESSION['id'];

            //On remplace l'ancien pseudo par le nouveau dans la bdd (= update)
            $req = $bdd->prepare('UPDATE membres SET pseudo= :new_pseudo WHERE id= :id');
            $req->execute(array(
                'new_pseudo' => $new_pseudo,
                'id' => $_SESSION['id']
            ));
            $req -> closeCursor();
			echo '<br />Mon nouveau pseudo devrait être : '.$new_pseudo;
						
			//recherche de la modif via une nouvelle requête
			$req = $bdd -> prepare('SELECT id, pseudo FROM membres WHERE id = :id');
            $req -> execute(array(
               	'id' => $_SESSION['id']
            ));
            $result = $req -> fetch();
            $req -> closeCursor();
            echo '<br />Mon nouveau pseudo est : '.$result['pseudo'];
            $message = 'Please log in again using your new nickname/password !';

            //on procède automatiquement à la déconnexion du membre
            include 'logout.php';
				//header('Location: login.php?message='.strip_tags($message)); 
            
        }
        else
        {
           	echo '<br />le pseudo n\'est pas libre';
            $message = 'This nickname is already used !';
            header('Location: modify_login.php?message='.strip_tags($message));
        }
    }
    
    //PARTIE CHANGEMENT DE MOT DE PASSE
    if(isset($_POST['old_password']) && $_POST['old_password']!='' && isset($_POST['new_password']) && $_POST['new_password']!='' && isset($_POST['new_password-confirm']) && $_POST['new_password-confirm']!='')
    {
    	echo '<br />mon pseudo est : '.$_SESSION['pseudo'];

    	// On récupère l'ancien mdp correspondant à l'utilisateur via la session et la bdd
        $req = $bdd -> prepare('SELECT id, pseudo, pass FROM membres WHERE pseudo = :pseudo');
        $req -> execute(array(
        	'pseudo' => $_SESSION['pseudo']
        ));
        $result = $req -> fetch();
        $req -> closeCursor();
        echo '<br />ancien mdp récupéré : '.$result['pass'];

        //comparaison de l'ancien mdp envoyé via le formulaire avec celui présent dans la bdd
        $isPasswordCorrect = password_verify($_POST['old_password'], $result['pass']);
        if($isPasswordCorrect)
        {
        	echo '<br/>le vieux mot de passe est correct !';

        	// On vérifie que les deux nouveaux mots de passe sont identiques.
            if($_POST['new_password'] == $_POST['new_password-confirm']) 
            {
				echo '<br/>Parfait! Les nouveaux mots de passe sont identiques !';

				//On vérifie que le mot de passe respecte les normes de sécurité
                if(preg_match('#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[><,=|;/.&:\#-+!*$@%_?])([><,=|;/.&:\#-+!*$@%_?\w]{8,})$#', $_POST['new_password']))
                {
                	echo '<br/>OK, le nouveau mot de passe est sûr !';
                	//On modifie (=update) le mdp dans la bdd
                	$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                	echo '<br/>voici le nouveau mdp : '.$new_password;
                	$req = $bdd ->prepare('UPDATE membres SET pass = :new_password WHERE pass = :old_password');
                	$req -> execute(array(
                		'new_password' => $new_password,
                		'old_password' => $result['pass']
                	));
                	$req -> closeCursor();
                	echo '<br/>le nouveau mot de passe devrait être :'.$new_password;
                	$message = 'Please log in again using your new nickname/password !';
                	include 'logout.php';
					//header('Location: login.php?message='.strip_tags($message)); 
                }
                else
                {
                	echo '<br/>le nouveau mot de passe n\'est pas aux normes !';
                	$message = 'Your new password isn\'t strong enought: Please use a minmum of 8 characters, including at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 special character among (|/\,;.:!?-_=*$@%)';
                    header('Location: modify_login.php?message='.strip_tags($message));
                }
            }
            else
            {
            	echo '<br/>les nouveaux mots de passe ne sont pas identiques !';
            	$message = 'New passwords don\'t match !';
                header('Location: modify_login.php?message='.strip_tags($message));
            }
        }
        else
        {
        	echo '<br/>le vieux mot de passe est incorrect !';
        	$message = 'Invalid old password !';
            header('Location: modify_login.php?message='.strip_tags($message));
        }
    }
    
    elseif(!(isset($_POST['new_pseudo']) && $_POST['new_pseudo']!='' && $_POST['new_pseudo']!= $_SESSION['pseudo']) && !(isset($_POST['old_password']) && $_POST['old_password']!='' && isset($_POST['new_password']) && $_POST['new_password']!='' && isset($_POST['new_password-confirm']) && $_POST['new_password-confirm']!=''))
    {
    	echo '<br />Aucun changement !';
    	$message = 'Please make a change or go back to Homepage !';
   		header('Location: modify_login.php?message='.strip_tags($message));
    }  
?>
