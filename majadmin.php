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
      if ($_GET['c'] == "c")
      {
        $titre = $_POST['nom'];
        $query=$bdd->prepare('INSERT INTO categoriesforum (CatLibelle,CatDate, UserId) VALUES (:titre, now(), :id)');
        $query->bindValue(':titre',$titre, PDO::PARAM_STR); 
        $query->bindValue(':id',$_SESSION['id'], PDO::PARAM_INT); 
        $query->execute();          
        echo'<p>La catégorie a été créée !<br /> Cliquez <a href="'.WEBROOT.'admin.php">ici</a> 
        pour revenir à l administration</p>';
        $query->CloseCursor();
      }
      break;

      case "edit":
      if(!isset($_GET['e']))
      {
        echo'<p><a href="'.WEBROOT.'admin.php?cat=forum&action=edit&amp;e=editc">Editer une catégorie</a></p>';
      }
      elseif($_GET['e'] == "editc")
      {
        $titre = $_POST['nom'];

        $query=$bdd->prepare('SELECT COUNT(*) 
        FROM categoriesforum WHERE CatId = :cat');
        $query->bindValue(':cat',(int) $_POST['cat'],PDO::PARAM_INT);
        $query->execute();
        $cat_existe=$query->fetchColumn();
        $query->CloseCursor();
        if ($cat_existe == 0) erreur(ERR_CAT_EXIST);
        
        $query=$bdd->prepare('UPDATE categoriesforum
        SET CatLibelle = :name WHERE CatId = :cat');
        $query->bindValue(':name',$titre,PDO::PARAM_STR);
        $query->bindValue(':cat',(int) $_POST['cat'],PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

        echo'<p>La catégorie a été modifiée !<br />
        Cliquez <a href="'.WEBROOT.'admin.php">ici</a> 
        pour revenir à l administration</p>';
      }
      break;

      default;
      if($_SESSION['droit']==='3')
      {
        echo'<h1>Administration du forum</h1>';
        echo'<a href="'.WEBROOT.'admin.php?cat=forum&amp;action=creer">Creation</a><br />
        <a href="'.WEBROOT.'admin.php?cat=forum&amp;action=edit">Edition</a><br />';
      }
      break;
    }
    break;

    case "membres":

    $action = htmlspecialchars($_GET['action']);
    switch($action)
    {  
        case "droits":
        $membre =$_POST['pseudo'];
        $rang = (int) $_POST['droits'];
        $query=$bdd->prepare('UPDATE user SET UserRole = :rang
        WHERE LOWER(UserLogin) = :pseudo');
              $query->bindValue(':rang',$rang,PDO::PARAM_INT);
              $query->bindValue(':pseudo',strtolower($membre), PDO::PARAM_STR);
              $query->execute();
              $query->CloseCursor();
        echo'<p>Le niveau du membre a été modifié !<br />
        Cliquez <a href="./admin.php">ici</a> pour revenir à l administration</p>';
      break;
    }
    break;
    default;
    if($_SESSION['droit']==='3')
    {
      echo'<h1>Administration</h1>
      <a href="'.WEBROOT.'admin.php?cat=forum&amp;action=">Administration du forum</a><br />
      <a href="'.WEBROOT.'admin.php?cat=membres&amp;action=">Administration des membres</a><br /></p>';
    }
    break;

    case "catrss":

    $action = htmlspecialchars($_GET['action']);

    switch($action)
    {
      case "creer":

      if(empty($_GET['c']))
      {
        echo'<p><a href="'.WEBROOT.'admin.php?cat=catrss&action=creer&c=c">Créer une catégorie</a></p>';
      }
      if ($_GET['c'] == "c")
      {
        $titre = $_POST['nom'];
        $query=$bdd->prepare('INSERT INTO categoriesrss (CatLibelle,CatDate, UserId) VALUES (:titre, now(), :id)');
        $query->bindValue(':titre',$titre, PDO::PARAM_STR); 
        $query->bindValue(':id',$_SESSION['id'], PDO::PARAM_INT); 
        $query->execute();          
        echo'<p>La catégorie a été créée !<br /> Cliquez <a href="'.WEBROOT.'admin.php">ici</a> 
        pour revenir à l administration</p>';
        $query->CloseCursor();
      }
      break;

      case "edit":
      if(!isset($_GET['e']))
      {
        echo'<p><a href="'.WEBROOT.'admin.php?cat=catrss&action=edit&amp;e=editc">Editer une catégorie</a></p>';
      }
      elseif($_GET['e'] == "editc")
      {
        $titre = $_POST['nom'];

        $query=$bdd->prepare('SELECT COUNT(*) 
        FROM categoriesrss WHERE CatId = :cat');
        $query->bindValue(':cat',(int) $_POST['cat'],PDO::PARAM_INT);
        $query->execute();
        $cat_existe=$query->fetchColumn();
        $query->CloseCursor();
        if ($cat_existe == 0) erreur(ERR_CAT_EXIST);
        
        $query=$bdd->prepare('UPDATE categoriesrss
        SET CatLibelle = :name WHERE CatId = :cat');
        $query->bindValue(':name',$titre,PDO::PARAM_STR);
        $query->bindValue(':cat',(int) $_POST['cat'],PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

        echo'<p>La catégorie a été modifiée !<br />
        Cliquez <a href="'.WEBROOT.'admin.php">ici</a> 
        pour revenir à l administration</p>';
      }
      break;

      default;
      if($_SESSION['droit']==='3')
      {
        echo'<h1>Administration du forum</h1>';
        echo'<a href="'.WEBROOT.'admin.php?cat=catrss&amp;action=creer">Creation</a><br />
        <a href="'.WEBROOT.'admin.php?cat=catrss&amp;action=edit">Edition</a><br />';
      }
      break;
    }
    break;

    case "rss":

    $action = htmlspecialchars($_GET['action']);

    switch($action)
    {
      case "creer":

      if(empty($_GET['c']))
      {
        echo'<p><a href="'.WEBROOT.'admin.php?cat=rss&action=creer&c=c">Créer un flux</a></p>';
      }
      if ($_GET['c'] == "c")
      {
        $titre = $_POST['nom'];
        $query=$bdd->prepare('INSERT INTO fluxrss (nom, url, catid, UserId) VALUES (:titre, :url, :catid, :userid)');
        $query->bindValue(':titre',$titre, PDO::PARAM_STR); 
        $query->bindValue(':url',$url, PDO::PARAM_STR); 
        $query->bindValue(':catid',$_POST['id'], PDO::PARAM_INT);
        $query->bindValue(':userid',$_SESSION['id'], PDO::PARAM_INT);  
        $query->execute();          
        echo'<p>Le flux a été créée !<br /> Cliquez <a href="'.WEBROOT.'admin.php">ici</a> 
        pour revenir à l administration</p>';
        $query->CloseCursor();
      }
      break;

      case "edit":
      if(!isset($_GET['e']))
      {
        echo'<p><a href="'.WEBROOT.'admin.php?cat=rss&action=edit&amp;e=editc">Editer un flux</a></p>';
      }
      elseif($_GET['e'] == "editc")
      {
        $titre = $_POST['nom'];
        $url = $_POST['url'];

        $query=$bdd->prepare('SELECT COUNT(*) 
        FROM fluxrss WHERE Id = :id');
        $query->bindValue(':id',(int) $_POST['cat'],PDO::PARAM_INT);
        $query->execute();
        $cat_existe=$query->fetchColumn();
        $query->CloseCursor();
        if ($cat_existe == 0) erreur(ERR_CAT_EXIST);
        
        $query=$bdd->prepare('UPDATE fluxrss
        SET nom = :name, url = :url WHERE Id = :cat');
        $query->bindValue(':name',$titre,PDO::PARAM_STR);
        $query->bindValue(':url',$url, PDO::PARAM_STR); 
        $query->bindValue(':id',(int) $_POST['cat'],PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

        echo'<p>Le flux a été modifiée !<br />
        Cliquez <a href="'.WEBROOT.'admin.php">ici</a> 
        pour revenir à l administration</p>';
      }
      break;

      default;
      if($_SESSION['droit']==='3')
      {
        echo'<h1>Administration du forum</h1>';
        echo'<a href="'.WEBROOT.'admin.php?cat=rss&amp;action=creer">Creation</a><br />
        <a href="'.WEBROOT.'admin.php?cat=rss&amp;action=edit">Edition</a><br />';
      }
      break;
    }
    break;
  }
?>
</div></div>

<?php require_once("includes/footer.php"); ?>