<?php include("lib/includes.php"); ?>
<?php include("includes/header.php"); ?>
<?php if($_SESSION['auth'] == 0) {header("Location:".WEBROOT."index.php");}?>

<?php 
  $choix_cat_flux = 0;
  if (isset($_POST["choix_cat_rss"]))
  {
    $choix_cat_flux = $_POST["choix_cat_rss"];
  }
  $choix_flux = 0;
  if (isset($_POST["choix_rss"]))
  {
    $choix_flux = $_POST["choix_rss"];
  }

  echo '<form method="post">';
  echo '<label for="choix_cat_rss">Categorie RSS:</label>';
  echo '<select name="choix_cat_rss">';

  $requete0 = $bdd->prepare('SELECT catid, catlibelle FROM categoriesrss');
  $requete0->execute();

  if($requete0->rowCount() > 0)
  {
    for($i=0;$i<$requete0->rowCount();$i++)
    {
      extract($requete0->fetch());
      if($catid == $choix_cat_flux)
      {
        echo '<option value="'.$catid.'" selected="selected">"'.$catlibelle.'"</option>';
      }
      else
      {
        echo '<option value="'.$catid.'">"'.$catlibelle.'"</option>';
      }
    }
  }
  echo '</select>';
  echo '<input type="submit" value="CAT" name="CAT"/>';

  if (isset($_POST['CAT']))
  {
    echo '<label for="choix_rss">Flux RSS:</label>';
    echo '<select name="choix_rss">';
    echo '<option value="0" selected="selected">';

    $requete1 = $bdd->prepare('SELECT id, nom, url FROM fluxrss where cat_id = :cat');
    $requete1->bindValue(':cat',$choix_cat_flux, PDO::PARAM_INT);
    $requete1->execute();

    if($requete1->rowCount() > 0)
    {
      for($i=0;$i<$requete1->rowCount();$i++)
      {
        extract($requete1->fetch());
        echo '<option value="'.$id.'">"'.$nom.'"</option>';
      }
    }
    echo '</select>';
    echo '<input type="submit" value="GO" name="GO"/>';
  }
  echo '</form>';

if (isset($_POST['GO']))
{
  if ( $choix_flux == "0")
  {
    echo "Selectionnez un flux !";
  }
  else
  {
    $requete2 = $bdd->prepare('SELECT nom , url FROM fluxrss where id = :id');
    $requete2->bindValue(':id',$choix_flux, PDO::PARAM_INT);
    $requete2->execute();
    extract($requete2->fetch());

    echo "<h1>Flux RSS : ".$nom.".</h1>";

    $url_rss[0] = $url;
    $tableau = array(); 

    foreach ($url_rss as $k=>$v) 
    { 
      $tableau = array_merge($tableau,liste_rss($v, 25, true, true, true)); 
    }

    foreach($tableau as $index=>$valeur)
    {
      echo "<div>";
      echo '<form method="post">';
      echo "<h3><b>".$valeur['title']."</b></h3>";
      echo "<p><i>Date : ".$valeur['date']."</i></p>";
      echo "<p>".$valeur['description']."</p>";
      echo "<a href='".$valeur['link']."'>".$valeur['link']."</a>";
      echo'<input type="hidden" name="urlrss" value="'.$valeur['link'].'" />';
      echo'<input type="hidden" name="nomrss" value="'.$valeur['title'].'" />';
      echo '<input type="submit" value="mail" name="mailrss"/></form>';
      echo "</div>";
    }
  } 
}

if (isset($_POST['mailrss']))
{
      $to       =   $_SESSION['email'];
      $subject  =   "Bonne lecture";
      $message  =   "<a href =".$_POST['urlrss'].">".$_POST['nomrss']."</a>";
      $name     =   "Wbforum BVD";
      $mailsend =   sendmail($to,$subject,$message,$name);
      echo "<h1>lien envoyé vers votre boite mail personelle.</h1>";
} 
?>
</div>

<?php include("includes/footer.php"); ?>