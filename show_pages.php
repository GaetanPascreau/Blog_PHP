<?php
    //récupération et affichage des messages
    //on compte d'abord le nombre total de messages
    $req = $bdd->query('SELECT COUNT(*) AS nb_billets FROM billets'); 
    $result = $req->fetch();

    //ceil arrondit au nombre sup. ex: si nb_pages=5.2 on arrondit à 6                                      
    $nb_pages = ceil($result['nb_billets']/5); 
    $req->closeCursor();        

    if(isset($_GET['page'])) //si on a saisi ?page=xxx dans l'URL
    {
        //on convertit le texte correspondant au numéro de page en un int
        $_GET['page'] = (int) $_GET['page'];

        if($_GET['page']>0 AND $_GET['page']<=$nb_pages)  
        {
            //on calcule l'index de la première entrée à afficher sur la page
            $limite = 5 * ($_GET['page']-1);  
            $req = $bdd->prepare('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y at %Hh%imin%ss\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT :limite, 5');
            $req->bindValue('limite', $limite, PDO::PARAM_INT);
            $req->execute() or die(print_r($req->errorInfo()));
        }
        else  //si la page n'existe pas on affiche un message d'erreur, puis le contenu de la première page
        {
            echo '<p class="erreur_page">Sorry, page ' .$_GET['page']. ' doesn\'t exist !</p>';  
            $req = $bdd->query('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y at %Hh%imin%ss\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT 0, 5') or die(print_r($req -> errorInfo()));
        }
    }
    else  //si on n'entre pas de numéro de page dans l'URL, on affiche la première page par défaut
    {
        $req = $bdd -> query('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y at %Hh%imin%ss\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT 0, 5') or die(print_r($req -> errorInfo()));
    }
?>
