<h4>
        <?php echo strip_tags($result['titre']); ?>
        &nbsp; &nbsp;
        <em> posted on <?php echo $result['date_creation_fr']; ?></em>
</h4>
<p>
        <?php 
        //On affiche le contenu du billet
        echo nl2br(strip_tags($result['contenu'])); //nl2br() convertit les sauts de lignes en <br />
        ?>                                                                                      
        <br />
</p>