<?php
    //connexion à la bdd  
    include 'db_connection.php';

    //On fait les contrôles de remplissage des champs obligatoires
    if(isset($_POST['pseudo']) && $_POST['pseudo']!='' && isset($_POST['email']) && $_POST['email']!=''&& isset($_POST['password']) && $_POST['password']!='' && isset($_POST['password-confirm']) && $_POST['password-confirm']!='')
    {
        // On ajoute des sécurités et des valeurs par défaut
        $pseudo = addslashes(htmlspecialchars(htmlentities(trim($_POST['pseudo']))));
        $email = addslashes(htmlspecialchars(htmlentities(trim($_POST['email']))));
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if(isset($_POST['date_of_birth']) and $_POST['date_of_birth']!='')
        {
            $date_of_birth = addslashes(htmlspecialchars(htmlentities(trim($_POST['date_of_birth']))));
        }
        else
        {
            $date_of_birth = '1111/11/11';
        }
        if(isset($_POST['city']) and $_POST['city']!='')
        {
          $city = addslashes(htmlspecialchars(htmlentities(trim($_POST['city']))));  
        }
        else
        {
            $city='n/a';
        }
        if(isset($_POST['job']) and $_POST['job']!='')
        {
            $job = addslashes(htmlspecialchars(htmlentities(trim($_POST['job']))));
        }
        else
        {
            $job ='n/a';
        }
        if(isset($_POST['hobbies']) and $_POST['hobbies']!='')
        {
            $hobbies = addslashes(htmlspecialchars(htmlentities(trim($_POST['hobbies']))));
        }
        else
        {
            $hobbies ='n/a';
        }
        if(isset($_POST['instant_messaging']) and $_POST['instant_messaging']!='')
        {
            $instant_messaging = addslashes(htmlspecialchars(htmlentities(trim($_POST['instant_messaging']))));
        }
        else
        {
          $instant_messaging ='n/a';
        }
        $share_email = 'no';


        // On vérifie que l'email à un format valide.
        if(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)) 
        {
            echo '<br /> email valide';
            // On vérifie que les deux mots de passe sont identiques.
            if($_POST['password'] == $_POST['password-confirm']) 
            {
                echo '<br /> les mdp sont identiques';
                //On vérifie que le mot de passe respecte les normes de sécurité
                if(preg_match('#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[><,=|;/.&:\#-+!*$@%_?])([><,=|;/.&:\#-+!*$@%_?\w]{8,})$#', $_POST['password']))
                {
                    echo '<br /> le mdp est valide';
                    // On vérifie si le pseudo entré existe déjà dans notre bdd
                    $req = $bdd -> prepare('SELECT id, pass, pseudo, avatar_path FROM membres WHERE pseudo = :pseudo');
                    $req -> execute(array(
                        'pseudo' => $pseudo
                    ));
                    $result = $req -> fetch();
                    $req -> closeCursor();

                    // si ce n'est pas le cas on peut l'ajouter.                    
                    if(!$result)           
                    {
                        echo '<br />le pseudo est libre<br />';
                        //On insert le nouvel inscrit dans la bdd
                        $req = $bdd->prepare('INSERT INTO membres(pseudo, pass, email, date_inscription, avatar_path, date_of_birth, city, job, hobbies, instant_messaging, share_email) VALUES(:pseudo, :password, :email, NOW(), 0, :date_of_birth, :city, :job, :hobbies, :instant_messaging, :share_email)');
                        $req->execute(array(
                            'pseudo' => $pseudo,
                            'password' => $password,
                            'email' => $email,
                            'date_of_birth' => $date_of_birth,
                            'city' => $city,
                            'job' => $job,
                            'hobbies' => $hobbies,
                            'instant_messaging' => $instant_messaging,
                            'share_email' => $share_email
                        ));
                        $req -> closeCursor();
                        echo 'données insérées en bdd !<br />';                       

                        //On enregistre l'avatar sur le disque s'il a été renseigné
                        if(isset($_FILES['avatar']) && $_FILES['avatar']['error']==0)
                        {
                           echo '<br />On a choisi un avatar : '.$_FILES['avatar']['name'].'<br />';
                            if($_FILES['avatar']['size']<= 1000000)
                            {
                                echo '<br />le fichier respecte la taille maximale autorisée<br />';
                                $infosfichier = pathinfo($_FILES['avatar']['name']);
                                $extension_upload = $infosfichier['extension'];
                                $extension_authorized = array('jpg', 'jpeg', 'png', 'gif');
                                if(in_array($extension_upload, $extension_authorized))
                                {
                                    echo '<br />l\'extension est autorisée<br />';
                                    //on récupère les données enregistrées en bdd
                                    $req = $bdd->prepare('SELECT pseudo, pass, id, email FROM membres WHERE pseudo = :pseudo');
                                    $req->execute(array(
                                        'pseudo' => $pseudo
                                    ));
                                    $result = $req-> fetch();
                                    echo $result['pseudo'].'<br />'; 
                                    echo $result['pass'].'<br />'; 
                                    echo $result['email'].'<br />';
                                    echo $result['id'].'<br />';   
                                    $req -> closeCursor();                                 

                                    //on déplace l'avatar du fichier temporaire vers le fichier de destination sur le disque
                                    $avatar_path_temp = 'avatars/'.$result['id'].'.'.$extension_upload;
                                    move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_path_temp);
                                    echo '<br />avatar déplacé !';

                                    //On crée une miniature de l'avatar et on le nomme d'après l'ID du membre et on l'enregistre sur le disque
                                    $source = imagecreatefromjpeg($avatar_path_temp);
                                    $destination = imagecreatetruecolor(150, 150);
                                    $width_source = imagesx($source);
                                    $height_source = imagesy($source);
                                    $width_destination = imagesx($destination);
                                    $height_destination = imagesy($destination);
                                    imagecopyresampled($destination, $source, 0, 0, 0, 0, $width_destination, $height_destination, $width_source, $height_source);
                                    $avatar_path = 'avatars/mini_'.$result['id'].'.'.$extension_upload;
                                    imagejpeg($destination, $avatar_path);

                                    //On insert le chemin vers le fichier de l'avatar dans la bdd
                                    $req = $bdd->prepare('UPDATE membres SET avatar_path = :avatar WHERE pseudo= :pseudo');
                                    $req->execute(array(
                                        'avatar' => $avatar_path,
                                        'pseudo' => $pseudo
                                    ));
                                    $req -> closeCursor();
                                    echo '<br />chemin vers l\'avatar inséré dans la bdd !';
                                    echo '<br />le fichier a été ajouté !';
                                    $message = 'An avatar has been added !';
                                    header('Location: login.php?message='.strip_tags($message));
                                }
                                else
                                {
                                    echo '<br />l\'extension n\'est pas autorisée...<br />';
                                    $message = 'unsupported file extension !';
                                    header('Location: registration.php?message='.strip_tags($message));
                                }
                            }
                            else
                            {
                                echo '<br />le fichier est trop volumineux !';
                                $message = 'The file is too big !';
                                header('Location: registration.php?message='.strip_tags($message));
                            }
                        }
                        else
                        {
                            echo '<br />aucun avatar n\'a été choisi !';
                            //On nomme l'avatar d'après le fichier par défaut et on l'enregistre sur le disque
                            $avatar_path = 'avatars/default_avatar_bis.jpg';
                            //echo '<br />avatar par défaut sélectionné !';

                            //On insert le chemin vers le fichier de l'avatar par défaut dans la bdd
                            $req = $bdd->prepare('UPDATE membres SET avatar_path = :avatar WHERE pseudo= :pseudo');
                            $req->execute(array(
                                'avatar' => $avatar_path,
                                'pseudo' => $pseudo
                            ));
                            $req -> closeCursor();
                            echo '<br />chemin vers l\'avatar par défaut inséré dans la bdd !';
                            $message = 'No avatar selected !';
                            header('Location: login.php?message='.strip_tags($message));
                        }

                        //Si on a coché la case pour partager l'email, on change la valeur par défaut de la variable en bdd (no devient yes)
                        if(isset($_POST['share_email']))
                        {
                            $share_email = 'yes';
                            $req = $bdd->prepare('UPDATE membres SET share_email = :share_email WHERE pseudo= :pseudo');
                            $req->execute(array(
                                'pseudo' => $pseudo,
                                'share_email' => $share_email
                            ));
                            $req -> closeCursor();
                        }
                        
                    }
                    else
                    {
                        echo '<br />le pseudo n\'est pas libre';
                        $message = 'This nickname is already used !';
                        header('Location: registration.php?message='.strip_tags($message));
                    }
                }
                else
                {
                    $message = 'Your password isn\'t strong enought: Please use a minmum of 8 characters, including at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 special character among (|/\,;.:!?-_=*$@%)';
                    header('Location: registration.php?message='.strip_tags($message));
                }
            }
            else
            {
                $message = 'Passwords don\'t match !';
                header('Location: registration.php?message='.strip_tags($message));
            }
        }
        else
        {
            $message = 'Your email address is invalid ! The permitted characters are :lower case letters, numbers and the following special characters (-_ .). Also check that your domain name exists !';
            header('Location: registration.php?message='.strip_tags($message));
        }
    }
    else
    {
        $message = 'Please complete this form to register.';
        header('Location: registration.php?message='.strip_tags($message));
    }                              
?>