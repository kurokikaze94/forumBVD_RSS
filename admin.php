<?php require_once("includes/header.php"); ?>
<?php if($_SESSION['droit'] == 0 || $_SESSION['droit'] == 1) {header("Location:".WEBROOT."index.php");}?>

<div>
<div>
<?php
$cat = (isset($_GET['cat']))?htmlspecialchars($_GET['cat']):'';

  switch($cat)
  {
    case "forum":

    $action = htmlspecialchars($_GET['action']);
    switch($action)
    {
      case "creer":

      if(empty($_GET['c']))
      {
        echo'<p><a href="'.WEBROOT.'admin.php?cat=forum&action=creer&c=c">Créer une catégorie</a></p>';
      }
      elseif($_GET['c'] == "c")
      {
        echo'<h1>Création d une catégorie</h1>';
        echo'<form method="post" action="'.WEBROOT.'majadmin.php?cat=forum&action=creer&c=c">';
        echo'<label> Indiquez le nom de la catégorie :</label>
        <input type="text" id="nom" name="nom" /><br /><br />   
        <input type="submit" value="Envoyer"></form>';
      }
      break;
      
      case "edit":
      if(!isset($_GET['e']))
      {
        echo'<p><a href="'.WEBROOT.'admin.php?cat=forum&action=edit&amp;e=editc">Editer une catégorie</a></p>';
      }
      elseif($_GET['e'] == "editc")
      {
        if(!isset($_POST['cat']))
        {
          $requete = $bdd->query('SELECT * FROM categoriesforum ORDER BY CatDate DESC');
          echo'<form method="post" action="'.WEBROOT.'admin.php?cat=forum&amp;action=edit&amp;e=editc">';
          echo'<p>Choisir une catégorie :</br />
          <select name="cat">';
          while($data = $requete->fetch())
          {
            echo'<option value="'.$data['CatId'].'">'.$data['CatLibelle'].'</option>';
          }
          echo'<input type="submit" value="Envoyer"></p></form>';         
          $requete->CloseCursor();                                                                  
        }         
        else
        {
          $requete = $bdd->prepare('SELECT CatLibelle FROM categoriesforum WHERE CatId = :catid');
          $requete->bindValue(':catid',(int) $_POST['cat'],PDO::PARAM_INT);
          $requete->execute();
          $data = $requete->fetch();
          echo'<form method="post" action="'.WEBROOT.'majadmin.php?cat=forum&amp;action=edit&amp;e=editc">';
          echo'<label> Indiquez le nom de la catégorie :</label>';
          echo'<input type="text" id="nom" name="nom" value="'.stripslashes(htmlspecialchars($data['CatLibelle'])).'" /><br /><br />';
          echo'<input type="hidden" name="cat" value="'.$_POST['cat'].'" />';
          echo'<input type="submit" value="Envoyer" /></p></form>';
          $requete->CloseCursor();                            
        }
      }
      break;

      default;
      if($_SESSION['droit']==='3')
      {
        echo'<h1>Administration du forum</h1>';
        echo'<a href="'.WEBROOT.'admin.php?cat=forum&amp;action=creer">Creation d\'une catégorie</a><br />
        <a href="'.WEBROOT.'admin.php?cat=forum&amp;action=edit">Edition d\'une catégorie</a><br />
        <a href="'.WEBROOT.'admin.php?cat=forum&amp;action=supprimer"><s>Supprimer une catégorie</s></a><br />';
      }
      break;
    }
    break;

    case "membres":

    $action = htmlspecialchars($_GET['action']);
    switch($action)
    {

      case "supprimer":

      echo'<h1>suppression d un membre</h1>';  

      if(!isset($_POST['membre']))
      {
        echo'De quel membre voulez-vous disposer ?<br />';
        echo'<br /><form method="post" action="'.WEBROOT.'admin.php?cat=membres&action=supprimer">
        <p><label for="membre">Inscrivez le pseudo : </label> 
        <input type="text" id="membre" name="membre">
        <input type="submit" value="Chercher"></p></form>';
      }
      else
      {
        $pseudo_d = $_POST['membre'];
        if ($pseudo_d == $_SESSION['login'])
        {
          setFlash("Désolé, mais vous ne pouvez pas vous supprimer vous meme...","Danger");
          echo flash();
          echo 'cliquez <a href="'.WEBROOT.'admin.php?cat=membres&amp;action=supprimer">ici</a> pour réessayer</p>';
        }
        else
        {
          $requete1 = $bdd->prepare('SELECT UserId FROM user WHERE UserLogin = :pseudo'); 
          $requete1->bindValue(':pseudo',$pseudo_d,PDO::PARAM_STR);
          $requete1->execute();
          if ($data = $requete1->fetch())
          {       
            $requete2 = $bdd->prepare('UPDATE messages SET MesText = "Utilisateur supprimé" WHERE UserId = :Id'); 
            $requete2->bindValue(':Id',$data['UserId'],PDO::PARAM_INT);
            $requete2->execute();
            $requete3 = $bdd->prepare('UPDATE user SET UserPassword = NULL, UserRole = 0 WHERE UserId = :Id'); 
            $requete3->bindValue(':Id',$data['UserId'],PDO::PARAM_INT);
            $requete3->execute();

            setFlash("l'utilisateur ".$pseudo_d." a bien été supprimé","Danger");
            echo flash();
          }                                                                   
          else echo' <p>Erreur : Ce membre n\'existe pas, <br />
            cliquez <a href="'.WEBROOT.'admin.php?cat=membres&amp;action=supprimer">ici</a> pour réessayer</p>';
        }
      }
      break;

      case "droits":

      echo'<h1>Edition des droits d\'un membre</h1>';  

      if(!isset($_POST['membre']))
      {
        echo'De quel membre voulez-vous modifier les droits ?<br />';
        echo'<br /><form method="post" action="'.WEBROOT.'admin.php?cat=membres&action=droits">
        <p><label for="membre">Inscrivez le pseudo : </label> 
        <input type="text" id="membre" name="membre">
        <input type="submit" value="Chercher"></p></form>';
      }
      else
      {
        $pseudo_d = $_POST['membre'];

        $pseudo_d = $_POST['membre'];
        if ($pseudo_d == $_SESSION['login'])
        {
          setFlash("Désolé, mais vous ne pouvez pas vous modifier vos droits vous meme...","Danger");
          echo flash();
          echo 'cliquez <a href="'.WEBROOT.'admin.php?cat=membres&amp;action=droits">ici</a> pour réessayer</p>';
        }
        else
        {
          $requete = $bdd->prepare('SELECT UserLogin,UserRole
            FROM user WHERE UserLogin = :pseudo and UserRole < :UserRole'); 
          $requete->bindValue(':pseudo',$pseudo_d,PDO::PARAM_STR);
          $requete->bindValue(':UserRole',$_SESSION['droit'],PDO::PARAM_STR);
          $requete->execute();
          if ($data = $requete->fetch())
          {       
            echo'<form action="'.WEBROOT.'majadmin.php?cat=membres&amp;action=droits" method="post">';
            if($_SESSION['droit'] == 2)
            {
              $rang = array
              ( 0 => "Banni",
                1 => "Utilisateur Lambda");
            }
            elseif($_SESSION['droit'] == 3)
            {
              $rang = array
              ( 0 => "Banni",
                1 => "Utilisateur Lambda", 
                2 => "Modérateur", 
                3 => "Administrateur");
            }
            echo'<label>'.$data['UserLogin'].'</label>';
            echo'<select name="droits">';
            $count = sizeof($rang);
            for($i=0;$i<$count;$i++)
            {
              if ($i == $data['UserRole'])
              {
                echo'<option value="'.$i.'" selected="selected">'.$rang[$i].'</option>';
              }
              else
              {
                echo'<option value="'.$i.'">'.$rang[$i].'</option>';
              }
            }
            echo'</select>
            <input type="hidden" value="'.stripslashes($pseudo_d).'" name="pseudo">               
            <input type="submit" value="Envoyer"></form>';
            $requete->CloseCursor();
          }                                                                   
          else echo' <p>Erreur : Ce membre n existe pas, <br />
            cliquez <a href="'.WEBROOT.'admin.php?cat=membres&amp;action=edit">ici</a> pour réessayer</p>';
        }
      }
      break;

      default;
        echo'<h1>Administration des membres</h1>';
        echo'<a href="'.WEBROOT.'admin.php?cat=membres&amp;action=supprimer">Supprimer un membre</a><br />';
        echo'<a href="'.WEBROOT.'admin.php?cat=membres&amp;action=droits">Modifier les droits d\'un membre</a><br />';
      break;
    }
    break;
    default;
      echo'<h1>Administration</h1>';
      if($_SESSION['droit']==='3')
      {
        echo'<a href="'.WEBROOT.'admin.php?cat=forum&amp;action=">Administration du forum</a><br />';
      }
      echo'<a href="'.WEBROOT.'admin.php?cat=membres&amp;action=">Administration des membres</a><br /></p>';
    break;
  }
?>
</div></div>

<?php require_once("includes/footer.php"); ?>