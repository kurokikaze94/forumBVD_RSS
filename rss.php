<?php include("lib/includes.php"); ?>
<?php include("includes/header.php"); ?>
<?php if($_SESSION['auth'] == 0) {header("Location:".WEBROOT."index.php");}?>

<?php 
       /*$requete1 = $bdd->prepare('SELECT id, nom, url FROM fluxrss');
       $requete1->execute();
       $count = $requete1->rowCount();
       if($count > 0)
       {
              for($i=0;$i<$count;$i++)
              {
                     echo'<option value="'.$i.'" selected="selected">'..'</option>';
              }
       }
       */
       include_once ('rss_admin.php'); 
       $tableau = array(); 

       //integrer une liste deroulante de choix des flux, qu'on recupere dans la bdd (avec ajout suppression d'url)
       $url_rss[0] = 'http://feeds.ign.com/ign/pc-all?format=xml'; 


       foreach ($url_rss as $k=>$v) 
       { 
              $tableau = array_merge($tableau,liste_rss($v, 25, true, true, true)); 
       }
?>

<div>
       <?php 
              foreach($tableau as $index=>$valeur)
              {
                  echo "<div>";
                  echo "<h3><b>".$valeur['title']."</b></h3>";
                  echo "<p><i>Date : ".$valeur['date']."</i></p>";
                  echo "<p>".$valeur['description']."</p>";
                  echo "<a href='".$valeur['link']."'>".$valeur['link']."</a>";
                  echo "</div>";
              }  
       ?>
</div>

<?php include("includes/footer.php"); ?>