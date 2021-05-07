<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog_members_page</title>
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

      <h1>My Super blog !</h1>
      <br />

		<!--AFFICHAGE DE L'AVATAR ET DU PSEUDO-->
		<div class="avatar_center">
            <p><strong>Welcome Home</strong> </p>
                <img src="<?php
                            	if(isset($_SESSION['avatar']))
                            	{
                               		echo $_SESSION['avatar'];  
                            	}
                           ?>"
                />
            <p>
                <?php
                    if (isset($_SESSION['pseudo']))
                    {
                        echo '<strong>'.$_SESSION['pseudo'].' !</strong>';
                    }
                ?>
            </p>     
        </div>

        <!--MESSAGE OPTIONNEL-->
        <?php
            if(isset($_GET['message']) AND $_GET['message']!='')
            {
                echo '<h3><span style="color:red"><strong>'.$_GET['message'].'</strong></h3></span>';
            }
        ?>
        <br /> 

        <!--AFFICHAGE DES INFOS PERSONNELLES-->
		<?php
			//On se connecte à la bdd
			include 'db_connection.php';
        	//On récupère toutes les infos du membre connecté grâce à $_SESSION['pseudo']
			$req = $bdd->prepare('SELECT id, pseudo, avatar_path, email, instant_messaging, date_of_birth, city, job, hobbies, date_inscription, share_email FROM membres WHERE pseudo = :pseudo');
			$req -> execute(array(
				'pseudo' => $_SESSION['pseudo']
			));
			$result = $req -> fetch();
			$req->closeCursor();
		?>

		<!--On affiche les infos du membre connecté-->
		<br />
		<div class="member_info">
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
		<br />

		<!--LIENS POUR MODIFIER SES IDENTIFIANTS ET POUR VOIR LE PROFIL DES AUTRES MEMBRES-->
		<div id="boutons">
			<form action="modify_login.php" method="post">
				<button type="submit" id="change_credentials" class="btn btn-secondary">Change credentials</button>
			</form>
			&nbsp;&nbsp;&nbsp;

			<form action="modify_member_info.php" method="post">
				<button type="submit" id="change_member_info" class="btn btn-warning">Modify your profile</button>
			</form>
			&nbsp;&nbsp;&nbsp;

			<form action="check_other_members.php" method="post">
				<button type="submit" id="check_other_members" class="btn btn-info">Check other members</button>
			</form>
			&nbsp;&nbsp;&nbsp;
		</div>
	</body>
</html>