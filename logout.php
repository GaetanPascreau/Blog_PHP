<?php 
	session_start();
	// Suppression des variables de session et de la session
	$_SESSION = array();
	session_destroy();
	header('Location: index.php?message='.strip_tags($message));
	// Suppression des cookies de connexion automatique
	setcookie('pseudo', '');
	setcookie('password', '');
?>