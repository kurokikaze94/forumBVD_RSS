<?php include("lib/includes.php"); ?>
<?php include("includes/header.php"); ?>
<?php if($_SESSION['auth'] == 0) {header("Location:".WEBROOT."index.php");}?>

<div>

<?php	
	echo"<h1>COMMENTER UN FLUX</h1>";

	if(!isset($_SESSION['id_topic']))
	{
		$_SESSION['id_topic'] = $_GET['id_topic'];
	}

	if(!isset($_SESSION['nomrss']))
	{
		$_SESSION['nomrss'] = $_GET['nom_topic'];
	}

	$TopicLibelle = $_SESSION['nomrss'];

	if(!isset($_SESSION['urlrss']))
	{
		$_SESSION['urlrss'] = $_GET['url_topic'];
	}

	$TopicUrl = $_SESSION['urlrss'];

	if (isset($_POST['submit']))
	{
		extract($_POST);

		if (isset($area))
		{
			if($_SESSION['id_topic'] == "x")
			{
				$requete0 = $bdd->prepare('INSERT INTO topicrss (rssTopicLibelle, rssTopicDate, UserId, CatId) 
					VALUES (:topiclib,now(),:UserId,:catId)');
				$requete0->bindValue(':topiclib',$TopicLibelle, PDO::PARAM_STR);
				$requete0->bindValue(':UserId',$_SESSION['id'], PDO::PARAM_INT);
				$requete0->bindValue(':catId',$_SESSION['catrss'], PDO::PARAM_INT);
				$requete0->execute();

				$requete00 = $bdd->prepare('SELECT rssTopicId as Tid FROM topicrss WHERE rssTopicLibelle = :topiclib AND UserId = :UserId AND CatId = :catId');
				$requete00->bindValue(':topiclib',$TopicLibelle, PDO::PARAM_STR);
				$requete00->bindValue(':UserId',$_SESSION['id'], PDO::PARAM_INT);
				$requete00->bindValue(':catId',$_SESSION['catrss'], PDO::PARAM_INT);
				$requete00->execute();
				extract($requete00->fetch());

				$_SESSION['id_topic'] = $Tid;
			}

			$requete1 = $bdd->prepare('INSERT INTO commentairerss (URLCRSS, commentaire, dateCRSS, UserId, idTopicRSS) 
				VALUES (:urlrss, :comText, now(), :UserId, :TopicId)');
			$requete1->bindValue(':urlrss',$TopicUrl, PDO::PARAM_STR);
			$requete1->bindValue(':comText',$area, PDO::PARAM_STR);
			$requete1->bindValue(':UserId',$_SESSION['id'], PDO::PARAM_INT);
			$requete1->bindValue(':TopicId',$_SESSION['id_topic'], PDO::PARAM_INT);
			$requete1->execute();
		}
	}

	if (isset($_POST['supprimer']))
	{
		extract($_POST);
		
		$requete3 = $bdd->prepare('UPDATE commentairerss SET commentaire = "Message Supprimé" WHERE idCRSS = :MesId');
		$requete3->bindValue(':MesId',$idCRSS, PDO::PARAM_INT);
		$requete3->execute();
	}

	if (isset($_POST['editer']))
	{
		extract($_POST);

		if (isset($editarea))
		{
			$requete4 = $bdd->prepare('UPDATE commentairerss SET commentaire = :MesText WHERE idCRSS = :MesId');
			$requete4->bindValue(':MesText',$editarea, PDO::PARAM_STR);
			$requete4->bindValue(':MesId',$idCRSS, PDO::PARAM_INT);
			$requete4->execute();
		}
	}

	echo '<h1>'.$TopicLibelle.'</h1>';

	echo '<form method="post">
    <textarea id="elm1" name="area"></textarea>
    <input type="submit" value="Poster" name="submit"/>
	</form>';

	if($_SESSION['id_topic'] == 'x')
	{
		$tmpcount = 0;
	}
	else
	{
		$requete2 = $bdd->prepare('SELECT idCRSS, commentaire, dateCRSS, u.UserLogin, u.UserRole FROM commentairerss c inner join user u on c.UserId = u.UserId where idTopicRSS = :topicid order by idCRSS desc');
		$requete2->bindValue(':topicid',$_SESSION['id_topic'], PDO::PARAM_INT);
		$requete2->execute();
		$tmpcount = $requete2->rowCount();
	}

	if($tmpcount > 0)
	{
		for ($k = 0; $k < $tmpcount ; $k++) 
		{
			extract($requete2->fetch());
			echo '<p>Message n°'.($tmpcount - $k).' du '.$dateCRSS.' posté par '.$UserLogin.'</p>';

			if ($UserLogin == $_SESSION['login'])
			{
				echo '<form method="post">';
				echo '<textarea id="elm1" name="editarea">'.$commentaire.'</textarea>';
				echo '<input type="hidden" name="idCRSS" value="'.htmlspecialchars($idCRSS).'" />';
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
					echo $commentaire.'</br>';
				}
				else
				{
					echo $commentaire.'</br>';
				}
				echo '<input type="hidden" name="idCRSS" value="'.htmlspecialchars($idCRSS).'" />';
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
					echo $commentaire;
				}
			}
			echo '<p>--------------------</p>';
		}
	}
?>

<?php include("includes/footer.php"); ?>