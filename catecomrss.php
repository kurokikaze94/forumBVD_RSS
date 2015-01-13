<?php include("lib/includes.php"); ?>
<?php include("includes/header.php"); ?>
<?php if($_SESSION['auth'] == 0) {header("Location:".WEBROOT."index.php");}?>

<?php 
	echo"<h1>RSS</h1>";
	
	if (isset($_POST['supprTopic']))
		{
			extract($_POST);

			if (isset($TopicId))
			{
				$requete4 = $bdd->prepare('DELETE FROM messages WHERE TopicId = :TopicId');
				$requete4->bindValue(':TopicId',$TopicId, PDO::PARAM_INT);
				$requete4->execute();

				$requete5 = $bdd->prepare('DELETE FROM topics WHERE TopicId = :TopicId');
				$requete5->bindValue(':TopicId',$TopicId, PDO::PARAM_INT);
				$requete5->execute();
			}
		}

	$requete1 = $bdd->prepare('SELECT * FROM categoriesrss');
	$requete1->execute();
	$tmp1count = $requete1->rowCount();

	if($tmp1count > 0)
	{
		for ($i = 0; $i < $tmp1count ; $i++) 
		{
			echo '<div class="container theme-showcase" role="main">';
			echo '<div class = "wrap">';
			echo '<ul class =  "menu">';
			extract($requete1->fetch());
			echo "<li><a href=''><h1>".$CatLibelle."</h1></a>";

			$requete2 = $bdd->prepare('SELECT * FROM commentairerss where idcatrss = :catid');
			$requete2->bindValue(':catid',$CatId, PDO::PARAM_INT);
			$requete2->execute();
			$tmp2count = $requete2->rowCount();

			if($tmp2count > 0)
			{
				echo "<ul>";
				for ($j = 0; $j < $tmp2count ; $j++) 
				{
					extract($requete2->fetch());
					echo "<li><a href='messages.php?id_topic=".$TopicId."&nom_topic=".$TopicLibelle."'>".$TopicLibelle."</a>";
					if($_SESSION['droit'] == 2 || $_SESSION['droit'] == 3)
					{
						echo '<form method="post">';
						echo '<input type="hidden" name="TopicId" value="'.htmlspecialchars($TopicId).'" />';
						echo '<input type="submit" class="btn btn-warning" value="Supprimer Topic" name="supprTopic"/>';
						echo '</form>';
					}
					echo "</li>";
				}
				echo "</ul>";
			}
			else
			{
				setFlash("Aucun Topic encore créé !","danger");
				echo flash();
			}
			echo '</li></div></div>';
		}

	}
	else
	{
		setFlash("Aucune Categorie encore créée !","danger");
		echo flash();
	}

?>

<?php include("includes/footer.php"); ?>