<?php include("lib/includes.php"); ?>
<?php include("includes/header.php"); ?>
<?php if($_SESSION['auth'] == 0) {header("Location:".WEBROOT."index.php");}?>

<div>

<?php	
	echo"<h1>FORUM</h1>";
	
	$TopicId = $_GET['id_topic'];
	$TopicLibelle = $_GET['nom_topic'];

	if (isset($_POST['MesId']))
	{
		$MesId = $_POST['MesId'];
	}

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

	if (isset($_POST['supprimer']))
	{
		extract($_POST);
		
		$requete3 = $bdd->prepare('UPDATE messages SET MesText = "Message Supprimé" WHERE MesId = :MesId');
		$requete3->bindValue(':MesId',$MesId, PDO::PARAM_INT);
		$requete3->execute();
	}

	if (isset($_POST['editer']))
	{
		extract($_POST);

		if (isset($editarea))
		{
			$requete4 = $bdd->prepare('UPDATE messages SET MesText = :MesText WHERE MesId = :MesId');
			$requete4->bindValue(':MesText',$editarea, PDO::PARAM_STR);
			$requete4->bindValue(':MesId',$MesId, PDO::PARAM_INT);
			$requete4->execute();
		}
	}
?>

<?php
	echo '<div>';
	echo '<h1>'.$TopicLibelle.'</h1>';
	echo '</div>';

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
?>

<div>
<form method="post">
    <textarea id="elm1" name="area"></textarea>
    <input type="submit" value="Poster" name="submit"/>
</form>
</div>

<?php include("includes/footer.php"); ?>