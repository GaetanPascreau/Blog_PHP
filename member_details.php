<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Blog_member_details</title>
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

		<!--connexion à la base de données-->
		<?php include 'db_connection.php'; ?>

		<!--On récupère le membre correspondant à l'id passé dans l'URL à partir de la bdd-->
		<?php
			if(isset($_GET['id']) AND $_GET['id']!='')
			{
				$req = $bdd->prepare('SELECT id, pseudo, avatar_path, email, instant_messaging, date_of_birth, city, job, hobbies, date_inscription, share_email FROM membres WHERE id = :id');
				$req -> execute(array(
				'id' => $_GET['id']
				));
				$result = $req -> fetch();
				$req->closeCursor();
		?>

			<!--AFFICHAGE DE L'AVATAR ET DU PSEUDO-->
			<div class="member_info">
				<h1>My super blog : <span class="overview" >Overview on <?php echo $result['pseudo']; ?></span></h1>
				<br />
					<img src="<?php echo $result['avatar_path']; ?>" alt="avatar">
						<h2><?php echo $result['pseudo']; ?></h2>
						<br />

						<!--AFFICHAGE DES INFOS DU MEMBRE-->
						<p>Member since <?php echo $result['date_inscription']; ?></p>
						<p>Born on <?php echo $result['date_of_birth']; ?></p>
						<p>I live in <?php echo $result['city']; ?></p>
						<p>What I do for a living : <?php echo $result['job']; ?></p>
						<p>My main hobbies : <?php echo $result['hobbies']; ?></p>
						<p>You can chat with me here : <?php echo $result['instant_messaging']; ?></p>

						<!-- on affiche l'email si le membre l'a autorisé via la checkbox
			  				et on rend l'adresse email cliquable = ouvre l'appli de messagerie-->	  
						<p>Or you can contact me at this address :
						<?php 
		 					if($result['share_email']=='yes')
		 					{ 
								echo'<a href="mailto:'.$result['email'].'">'.$result['email'].'</a>';
							}
							else
							{
								echo 'Sorry it\'s private !';
							}
						?>
						</p>
			</div>

		<?php
		}
		?>
		<br /><br />

		<div id="GoBack">
			<form action="check_other_members.php" method="post">
				<button type="submit" class="btn btn-primary">
             	   Back to Members
            	</button>
			</form>
		</div>
	</body>
</html>