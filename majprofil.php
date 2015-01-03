<?php require_once("includes/header.php"); ?>
<?php if($_SESSION['auth'] != 1) {header("Location:".WEBROOT."index.php");}?>
<?php
	if (isset($_POST['submit']))
	{
	    $mdp_erreur = NULL;
	    $avatar_erreur = NULL;
	    $avatar_erreur1 = NULL;
	    $avatar_erreur2 = NULL;
	    $avatar_erreur3 = NULL;

	    $erreur = 0;
	    $login=$_SESSION['login'];
	    $password = $_POST['password'];
	    $confirm = $_POST['confirm'];

	    if ($password != $confirm || empty($confirm) || empty($password))
	    {
	        $mdp_erreur = "Password mal confirmé";
	        $erreur++;
	    }
	    else
	    {
	    	$password = sha1($_POST['password']);
	    	$confirm = sha1($_POST['confirm']);
	    }

	    if (!empty($_FILES['avatar']['size']))
	    {
	        $maxsize = 10024;
	        $maxwidth = 100;
	        $maxheight = 100;
	        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' );
	        
	        if ($_FILES['avatar']['error'] > 0)
	        {
	                $avatar_erreur = "Probleme lors du transfert : ";
	        }
	        if ($_FILES['avatar']['size'] > $maxsize)
	        {
	                $erreur++;
	                $avatar_erreur1 = "Fichier trop volumineux : (<strong>".$_FILES['avatar']['size']." Octets</strong>    VS <strong>".$maxsize." Octets</strong>)";
	        }

	        $erreurmage_sizes = getimagesize($_FILES['avatar']['tmp_name']);
	        if ($erreurmage_sizes[0] > $maxwidth OR $erreurmage_sizes[1] > $maxheight)
	        {
	                $erreur++;
	                $avatar_erreur2 = "Taille image trop importante : 
	                (<strong>".$erreurmage_sizes[0]."x".$erreurmage_sizes[1]."</strong> VS <strong>".$maxwidth."x".$maxheight."</strong>)";
	        }
	        
	        $extension_upload = strtolower(substr(  strrchr($_FILES['avatar']['name'], '.')  ,1));
	        if (!in_array($extension_upload,$extensions_valides) )
	        {
	                $erreur++;
	                $avatar_erreur3 = "Extension de l'avatar incorrecte";
	        }
	    }

	   if ($erreur==0)
	   {
	   		echo'<div>';
			echo'<div>';
			echo'<h1>Mise a jour de votre profil terminée</h1>';
		    echo'<p>Bienvenue '.stripslashes(htmlspecialchars($_POST['login'])).'</p>
			<p><a href="'.WEBROOT.'index.php">HOME</a></p>';
			echo'</div></div>';

			if(!empty($_FILES['avatar']['size']))
			{
				$nomavatar=(!empty($_FILES['avatar']['size']))?move_avatar($_FILES['avatar']):''; 
		   	}
		   	else
		   	{
		   		$nomavatar= "defaut.jpg";
		   	}
		    $requete1=$bdd->prepare('UPDATE user set UserPassword = :pass, UserAvatar = :nomavatar, UserEmail = :email WHERE UserLogin = :login)');
			$requete1->bindValue(':login', $login, PDO::PARAM_STR);
			$requete1->bindValue(':pass', $password, PDO::PARAM_INT);
			$requete1->bindValue(':nomavatar', $nomavatar, PDO::PARAM_STR);
			$requete1->bindValue(':email', $email, PDO::PARAM_STR);
		    $requete1->execute();

			$requete2=$bdd->prepare('SELECT UserId, UserLogin, UserAvatar, UserRole, UserEmail FROM user WHERE UserLogin = :login');
			$requete2->bindValue(':login', $login, PDO::PARAM_STR);
		    $requete2->execute();
		    extract($requete2->fetch());
			$requete->CloseCursor();

			$_SESSION['auth'] = 1;
			$_SESSION['id'] = $UserId;
			$_SESSION['login'] = $UserLogin;
			$_SESSION['droit'] = $UserRole;
			$_SESSION['email'] = $email;
			$_SESSION['membre_avatar'] = $UserAvatar;
			header("Location:".WEBROOT."index.php");	    
	    }
	    else
	    {
	    	echo'<div>';
			echo'<div>';
	        echo'<h1>Erreur</h1>';
	        echo'<p>Probleme de saisie</p>';
	        echo'<p>'.$erreur.' erreur(s)</p>';
	        echo'<p>'.$mdp_erreur.'</p>';
	        echo'<p>'.$avatar_erreur.'</p>';
	        echo'<p>'.$avatar_erreur1.'</p>';
	        echo'<p>'.$avatar_erreur2.'</p>';
	        echo'<p>'.$avatar_erreur3.'</p>';
	       
	        echo'<p><a href="'.WEBROOT.'enregistrer.php">ON REMET CA !</a></p>';
	        echo'</div></div>';
	    }
	}

	function move_avatar($avatar)
	{
	    $extension_upload = strtolower(substr(  strrchr($avatar['name'], '.')  ,1));
	    $name = time();
	    $nomavatar = str_replace(' ','',$name).".".$extension_upload;
	    $name = "<?php echo WEBROOT; ?>images/avatars/".str_replace(' ','',$name).".".$extension_upload;
	    move_uploaded_file($avatar['tmp_name'],$name);
	    return $nomavatar;
	}
?>

<div>
	<div>
		<h1>Inscription</h1>
		<form method="post" action="majprofil.php" enctype="multipart/form-data">
			<fieldset>
			<label for="password">Password : (20 caractères max)</label><input type="password" name="password" id="password" placeholder="Password"/><br />
			<label for="confirm">Confirmer le Password :</label><input type="password"name="confirm" id="confirm" placeholder="Password"/><br />
			<label for="avatar">Choisissez votre avatar : (100px*100px - 10Ko max)</label><input type="file" name="avatar" id="avatar" /><br />
			</fieldset>
			<p><input type="submit" name="submit" value="Mise  jour profil" /></p>
		</form>
	</div>
</div>

<?php require_once("includes/footer.php"); ?>