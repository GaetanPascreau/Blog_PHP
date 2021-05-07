<?php
    session_start();
    //connexion à la bdd  
    include 'db_connection.php';
    echo 'connected<br />';

    //On fait les contrôles de remplissage des champs et on sécurise  
    if(isset($_POST['new_date_of_birth']) && $_POST['new_date_of_birth']!='')
    {
        $new_date_of_birth = addslashes(htmlspecialchars(htmlentities(trim($_POST['new_date_of_birth']))));
        echo $new_date_of_birth;
    }
    else
    {
        $new_date_of_birth = '1111/11/11';
        echo $new_date_of_birth;
    }
    if(isset($_POST['new_city']) and $_POST['new_city']!='')
    {
        $new_city = addslashes(htmlspecialchars(htmlentities(trim($_POST['new_city']))));
        echo $new_city;  
    }
    else
    {
        $new_city='n/a';
        echo $new_city;
    }
    if(isset($_POST['new_job']) and $_POST['new_job']!='')
    {
        $new_job = addslashes(htmlspecialchars(htmlentities(trim($_POST['new_job']))));
        echo $new_job;
    }
    else
    {
        $new_job ='n/a';
        echo $new_job;
    }
    if(isset($_POST['new_hobbies']) and $_POST['new_hobbies']!='')
    {
        $new_hobbies = addslashes(htmlspecialchars(htmlentities(trim($_POST['new_hobbies']))));
        echo $new_hobbies;
    }
    else
    {
        $new_hobbies ='n/a';
        echo $new_hobbies;
    }
    if(isset($_POST['new_instant_messaging']) and $_POST['new_instant_messaging']!='')
    {
        $new_instant_messaging = addslashes(htmlspecialchars(htmlentities(trim($_POST['new_instant_messaging']))));
        echo $new_instant_messaging;
    }
    else
    {
        $new_instant_messaging ='n/a';
        echo $new_instant_messaging;
    }

    //On remplace les anciennes données par les nouvelles dans la bdd (= update)
    $req = $bdd->prepare('UPDATE membres SET date_of_birth=:new_date_of_birth, city=:new_city, job=:new_job, hobbies=:new_hobbies, instant_messaging=:new_instant_messaging WHERE id= :id');
    $req->execute(array(
        'new_date_of_birth' => $new_date_of_birth,
        'new_city' => $new_city,
        'new_job' => $new_job,
        'new_hobbies' => $new_hobbies,
        'new_instant_messaging' => $new_instant_messaging,
        'id' => $_SESSION['id']
    ));
    $req -> closeCursor();
	       
    $message = 'Your profile was successfully modified !';
	header('Location: members_page.php?message='.strip_tags($message));    
?>
