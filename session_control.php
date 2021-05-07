<?php
	session_start();

	//connexion à la bdd  
    include 'db_connection.php';

	//VERSION AVEC CONTROLE DIFFERENCIE DE LA PRESENCE D'UNE SESSION ET D'UN COOKIE
	//on vérifie qu'un cookie existe et qu'il correspond aux infos de la bdd
	if(isset($_COOKIE['pseudo']) && $_COOKIE['pseudo']!='')
	{
		//on récupère le mot de passe en bdd
		$req = $bdd -> prepare('SELECT id, pass, pseudo FROM membres WHERE pseudo = :pseudo AND pass= :password');
		$req -> execute(array(
        		'pseudo' => $_COOKIE['pseudo'],
        		'password' => $_COOKIE['password']
        	));
		$result = $req -> fetch();
		if(result)
		{
			$message = 'Welcome back '.$_COOKIE['pseudo'] .'! Thanks for using our cookies !'; 
			header('Location: index_for_members.php?message='.htmlspecialchars($message));
		}
	}
	//on vérifie qu'une session est ouverte, sinon on redirige vers la page login
	elseif (isset($_SESSION['pseudo']) && $_SESSION['pseudo']!='') 
	{
		$message = 'Welcome ' .$_SESSION['pseudo']. '! Do you know you can use our cookies for auto-connection ?'; 
		header('Location: index_for_members.php?message='.htmlspecialchars($message));
	}
	else
	{
		$message='You must log in first !';
		header('Location: login.php?message='.htmlspecialchars($message));
	}
?>