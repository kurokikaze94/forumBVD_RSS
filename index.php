<?php require_once("includes/header.php"); ?>

    <div>
        <div>
        	<?php
              if($_SESSION['auth']==0)
                {
                	echo "<h1>Hello</h1>";
                	echo "<h3>Veuillez vous identifier via le boutton 'Login'</h3>";
                    $requete = $bdd->prepare('SELECT UserLogin FROM user WHERE UserPassword != ""'); 
                    $requete->execute();
                    $tmp1count = $requete->rowCount();
                    if( $tmp1count > 0)
                    {
                        for ($i = 0; $i < $tmp1count ; $i++) 
                        {
                            extract($requete->fetch());
                            echo '<p>Compte '.($i + 1).' login : '.$UserLogin.'</p>';
                        }
                    }
                    else
                    {
                        setFlash("Aucun compte present en base, allez on s'inscrit !","Danger");
                        echo flash();
                    }
                }
                else
                {
                	echo "<h1>Bonjour ".$_SESSION['login']."</h1>";
                    echo "<h3><b>Consignes : </b></h3>
                        <ol>
                            <li>differents topics triés par categories et commentables</li>
                            <li>gestions de flux rss</li>
                            <li><b>messagerie inter user</b></li>
                            <li>generation auto de mots de passe a la creation du compte</li>
                        </ol>
                        <h3>Utilisateur</h3>
                        <ol>
                            <li>s'inscrire (login, données perso)</li>
                            <li>Enregistrer une image avatar</li>
                            <li>modifier et supprimer ses propres posts</li>
                            <li>modifier ses données personnelles</li>
                            <li>consulter et <b>commenter les new rss</b></li>
                            <li><b>communication via messagerie interne et mail avec autres users</b></li>
                        </ol>
                        <h3>Moderateurs</h3>
                        <ol>
                            <li>supprimer tout posts</li>
                            <li>bannir tout utilisateur</li>
                            <li>+ droits utilisateurs</li>
                        </ol>
                        <h3>Administrateur</h3>
                        <ol>
                           <li>creation topics et de categories du forum</li>
                            <li>promulgation/suppression de droits moderateur/administrateur</li>
                            <li>creation de flux rss et de categories de flux</li>
                            <li>+ droit modo</li>
                        </ol>";
                } 
            ?>
        </div>
    </div>
    
<?php require_once("includes/footer.php"); ?>