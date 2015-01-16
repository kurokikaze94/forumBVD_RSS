<?php include("lib/includes.php"); ?>
<?php include("includes/header.php"); ?>
<?php if($_SESSION['auth'] == 0) {header("Location:".WEBROOT."index.php");}?>

<?php 
  
  echo"<h1>MP</h1>";
  
  $choix_cible = 0;
  if (isset($_POST["choix_cible"]))
  {
    $choix_cible = $_POST["choix_cible"];
  }

  echo '<label for="choix_cible">Utilisateur :</label>';
  echo '<select name="choix_cible">';
  echo '<option value="0" selected="selected">';

  $requete1 = $bdd->prepare('SELECT UserId, UserNom, UserPrenom FROM user where UserId <> :id');
  $requete1->bindValue(':id',$_SESSION['id'], PDO::PARAM_INT);
  $requete1->execute();

  if($requete1->rowCount() > 0)
  {
    for($i=0;$i<$requete1->rowCount();$i++)
    {
      extract($requete1->fetch());
      echo '<option value="'.$UserId.'">"'.$UserNom.'"</option>';
    }
  }
  echo '</select>';
  echo '<input type="submit" value="GO" name="GO"/>';
  echo '</form>';

if (isset($_POST['submit']))
  {
    extract($_POST);

    if (isset($area))
    {
      $requete1 = $bdd->prepare('INSERT INTO messages (MesText, MesDate, UserId, TopicId) 
        VALUES (:MesText,now(),:UserId,:TopicId)');
      $requete1->bindValue(':MesText',$area, PDO::PARAM_STR);
      $requete1->bindValue(':UserId',$_SESSION['id'], PDO::PARAM_INT);
      $requete1->bindValue(':TopicId',$TopicId, PDO::PARAM_INT);
      $requete1->execute();
    }
  }

if (isset($_POST['GO']))
{
  if ( $choix_cible == "0")
  {
    echo "Selectionnez un Utilisateur !";
  }
  else
  {
    $requete2 = $bdd->prepare('SELECT MesId, MesText, MesDate, u.UserLogin, u.UserRole FROM messages m inner join user u on m.UserId = u.UserId where topicid = :topicid');
    $requete2->bindValue(':topicid',$TopicId, PDO::PARAM_INT);
    $requete2->execute();
    $tmpcount = $requete2->rowCount();
    if($tmpcount > 0)
    {
      echo '<div>';
      for ($k = 0; $k < $tmpcount ; $k++) 
      {
        extract($requete2->fetch());
        echo '<p>Message n°'.($k + 1).' du '.$MesDate.' posté par '.$UserLogin.'</p>';

        if ($UserLogin == $_SESSION['login'])
        {
          echo '<form method="post">';
          echo '<textarea id="elm1" name="editarea">'.$MesText.'</textarea>';
          echo '<input type="hidden" name="MesId" value="'.htmlspecialchars($MesId).'" />';
            echo '<input type="submit" value="editer" name="editer"/>';
            echo '<input type="submit" value="supprimer" name="supprimer"/>';
          echo '</form>';
        }
        elseif($_SESSION['droit'] == 2 || $_SESSION['droit'] == 3)
        {
          echo '<form method="post">';
          if($UserRole == 0)
          {
            echo 'Message caché car l\'utilisateur a été banni.</p>';
            echo $MesText.'</br>';
          }
          else
          {
            echo $MesText.'</br>';
          }
          echo '<input type="hidden" name="MesId" value="'.htmlspecialchars($MesId).'" />';
            echo '<input type="submit" value="supprimer" name="supprimer"/>';
          echo '</form>';
        }
        else
        {
          if($UserRole == 0)
          {
            echo 'Message supprimé car l\'utilisateur a été banni.</p>';
          }
          else
          {
            echo $MesText;
          }
        }
        echo '<p>--------------------</p>';
      }
      echo '</div>';
    }

  <div>
  <form method="post">
      <textarea id="elm1" name="area"></textarea>
      <input type="submit" value="Poster" name="submit"/>
  </form>
  </div>
    } 
  }
?>

<?php include("includes/footer.php"); ?>