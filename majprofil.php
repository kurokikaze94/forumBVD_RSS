<?php require_once("includes/header.php"); ?>
<?php if($_SESSION['auth'] != 1) {header("Location:".WEBROOT."index.php");}?>
<?php
	$cat = (isset($_GET['cat']))?htmlspecialchars($_GET['cat']):'';

	switch($cat)
	{
		case "data" :
			echo '<div>
				<div>
					<h1>Inscription</h1>
						<form method="post" action="majprofil.php" enctype="multipart/form-data">
							<fieldset>
								<label for="email">Email : (50 caractères max / obligatoire)</label><input type="email" name="email" id="email" value="'.$_SESSION['email'].'"/><br />
									<label for="confirm">Confirmer l\'adresse email :</label><input type="email"name="confirm" id="confirm" value="'.$_SESSION['email'].'"/><br />
									<label for="nom">Nom : (20 caractères max / obligatoire)</label><input type="text" name="nom" id="nom"value="'.$_SESSION['nom'].'"/><br />
									<label for="prenom">Prenom : (20 caractères max / obligatoire)</label><input type="text" name="prenom" id="prenom" value="'.$_SESSION['prenom'].'"/><br />
									<label for="tel">Numéro de telephone : (10 chiffres max / séparés par des espaces, points ou tirets)</label><input type="text" name="tel" id="tel"value="'.$_SESSION['tel'].'"/><br />
							</fieldset>
							<p><input type="submit" name="submitdata" value="Mise a jour de vos données" /></p>
						</form>
				</div>
			</div>';
		break;

		case "avat":
			echo '<div>
				<div>
					<h1>Inscription</h1>
						<form method="post" action="majprofil.php" enctype="multipart/form-data">
							<fieldset>
								<label for="avatar">Choisissez votre avatar : (100px*100px - 10Ko max)</label><input type="file" name="avatar" id="avatar" /><br />
							</fieldset>
							<p><input type="submit" name="submitavat" value="Mise a jour de votre avatar" /></p>
						</form>
				</div>
			</div>';
		break;

		case "pswd":
			echo '<div>
				<div>
					<h1>Inscription</h1>
						<form method="post" action="majprofil.php" enctype="multipart/form-data">
							<fieldset>
								<label for="password">Password : (20 caractères max)</label><input type="password" name="password" id="password" placeholder="Password"/><br />
								<label for="confirm">Confirmer le Password :</label><input type="password"name="confirm" id="confirm" placeholder="Password"/><br />
							</fieldset>
							<p><input type="submit" name="submitpswd" value="Mise a jour du password" /></p>
						</form>
				</div>
			</div>';
		break;
	}	

	if (isset($_POST['submitdata']))
	{
	    $nom_erreur1 = NULL;
	    $prenom_erreur1=NULL;
	    $tel_erreur1=NULL;
	    $email_erreur1 = NULL;
	    $email_erreur2 = NULL;
	    $email_erreur3 = NULL;

	    $erreur = 0;
	    $login=$_SESSION['login'];
	    $email = $_POST['email'];
	    $nom = $_POST['nom'];
	    $prenom = $_POST['prenom'];
	    $tel = $_POST['tel'];
	    $confirmemail= $_POST['confirm'];

	    if (!(preg_match('#[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$#',$email)))
	    {
	    	$email_erreur3 = "Format de l'adresse mail incorrect";
	    	$erreur++;
	    }
	    else
	    {
		    $requetemail=$bdd->prepare('SELECT * FROM user WHERE UserEmail =:email and UserLogin <> :login ');
		    $requetemail->bindValue(':email',$email, PDO::PARAM_STR);
		    $requetemail->bindValue(':login', $login, PDO::PARAM_STR);
		    $requetemail->execute();

		    if($requetemail->rowCount() > 0)
		    {
		        $email_erreur1 = "Email deja utilisé";
		        $erreur++;
		    }

		    if ($email != $confirmemail || empty($confirmemail) || empty($email))
		    {
		        $email_erreur2 = "Adresse Email mal confirmée";
		        $erreur++;
		    }
		    else
		    {
		    	$email = $_POST['email'];
		    	$confirmemail = $_POST['confirm'];
		    }
		}

		if (strlen($nom) > 20 || strlen($nom) < 1 || !preg_match('#^[a-zA-Z]{1,20}$#', $_POST['nom']))
	    {
	        $nom_erreur1 = "Nom vide ou trop long";
	        $erreur++;
	    }

	    if (strlen($prenom) > 20 || strlen($prenom) < 1 || !preg_match('#^[a-zA-Z]{1,20}$#', $_POST['prenom']))
	    {
	        $prenom_erreur1 = "Prenom vide ou trop long";
	        $erreur++;
	    }

		if(!preg_match('#^0[1-9]([ .-][0-9]{2}){4}$#',$_POST['tel']) && !strlen($tel) == 0 )
		{
			$tel_erreur1 = "Format du numero de telephone incorrect";
			$erreur++;
		}

		if ($erreur==0)
		{
	   		echo'<div>';
			echo'<div>';
			echo'<h1>Mise a jour des données terminée</h1>';
			echo'</div></div>';

		    $requete1=$bdd->prepare('UPDATE user set UserNom = :nom, UserPrenom = :prenom, UserTel = :tel, UserEmail = :email WHERE UserLogin = :login');
			$requete1->bindValue(':login', $login, PDO::PARAM_STR);
			$requete1->bindValue(':email', $email, PDO::PARAM_STR);
			$requete1->bindValue(':nom', $nom, PDO::PARAM_STR);
			$requete1->bindValue(':prenom', $prenom, PDO::PARAM_STR);
			$requete1->bindValue(':tel', $tel, PDO::PARAM_STR);
		    $requete1->execute();

			$requete2=$bdd->prepare('SELECT UserEmail, UserNom, UserPrenom, UserTel FROM user WHERE UserLogin = :login');
			$requete2->bindValue(':login', $login, PDO::PARAM_STR);
		    $requete2->execute();
		    extract($requete2->fetch());
			$requete2->CloseCursor();

			$_SESSION['email'] = $email;
			$_SESSION['nom'] = $nom;
			$_SESSION['prenom'] = $prenom;
			$_SESSION['tel'] = $tel;

			header("Location:".WEBROOT."profil.php");	   
	    }
	    else
	    {
	    	echo'<div>';
			echo'<div>';
	        echo'<h1>Erreur</h1>';
	        echo'<p>Probleme de saisie</p>';
	        echo'<p>'.$erreur.' erreur(s)</p>';
	        echo'<p>'.$email_erreur1.'</p>';
	        echo'<p>'.$email_erreur2.'</p>';
	        echo'<p>'.$email_erreur3.'</p>';
	        echo'<p>'.$nom_erreur1.'</p>';
	        echo'<p>'.$prenom_erreur1.'</p>';
	        echo'<p>'.$tel_erreur1.'</p>';
	               
	        echo'<p><a href="'.WEBROOT.'majprofil.php?cat=data">ON REMET CA !</a></p>';
	        echo'</div></div>';
	    }
	}

	if (isset($_POST['submitavat']))
	{
		$avatar_erreur = NULL;
	    $avatar_erreur1 = NULL;
	    $avatar_erreur2 = NULL;
	    $avatar_erreur3 = NULL;

	    $erreur = 0;

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
	   		$login = $_SESSION['login'];

	   		echo'<div>';
			echo'<div>';
			echo'<h1>Avatar modifié</h1>';
			echo'</div></div>';

			if(!empty($_FILES['avatar']['size']))
			{
				$nomavatar=(!empty($_FILES['avatar']['size']))?move_avatar($_FILES['avatar']):''; 
		   	}
		   	else
		   	{
		   		$nomavatar= "defaut.jpg";
		   	}

		    $requete1=$bdd->prepare('UPDATE user set UserAvatar = :nomavatar WHERE UserLogin = :login');
			$requete1->bindValue(':login', $login, PDO::PARAM_STR);
			$requete1->bindValue(':nomavatar', $nomavatar, PDO::PARAM_STR);
		    $requete1->execute();

			$requete2=$bdd->prepare('SELECT UserAvatar FROM user WHERE UserLogin = :login');
			$requete2->bindValue(':login', $login, PDO::PARAM_STR);
		    $requete2->execute();
		    extract($requete2->fetch());
			$requete2->CloseCursor();

			$_SESSION['membre_avatar'] = $UserAvatar;

			header("Location:".WEBROOT."profil.php");	  
	    }
	    else
	    {
	    	echo'<div>';
			echo'<div>';
	        echo'<h1>Erreur</h1>';
	        echo'<p>Probleme(s)</p>';
	        echo'<p>'.$erreur.' erreur(s)</p>';
	        echo'<p>'.$avatar_erreur.'</p>';
	        echo'<p>'.$avatar_erreur1.'</p>';
	        echo'<p>'.$avatar_erreur2.'</p>';
	        echo'<p>'.$avatar_erreur3.'</p>';
	       
	        echo'<p><a href="'.WEBROOT.'majprofil.php?cat=avat">ON REMET CA !</a></p>';
	        echo'</div></div>';
	    }
	}


	if (isset($_POST['submitpswd']))
	{
	    $mdp_erreur = NULL;
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
	    	$password = sha1($password);
	    }

		if ($erreur==0)
		{
			echo'<div>';
			echo'<div>';
			echo'<h1>Mise a jour de votre password terminée</h1>';
			echo'</div></div>';

		    $requete1=$bdd->prepare('UPDATE user set UserPassword = :pass WHERE UserLogin = :login');
			$requete1->bindValue(':pass', $password, PDO::PARAM_STR);
			$requete1->bindValue(':login', $login, PDO::PARAM_STR);
		    $requete1->execute();
		    $requete1->CloseCursor();
			header("Location:".WEBROOT."profil.php");	    
	    }
	    else
	    {
	    	echo'<div>';
			echo'<div>';
	        echo'<h1>Erreur</h1>';
	        echo'<p>Probleme de saisie</p>';
	        echo'<p>'.$erreur.' erreur(s)</p>';
	        echo'<p>'.$mdp_erreur.'</p>';
	       
	        echo'<p><a href="'.WEBROOT.'majprofil.php?cat=pswd">ON REMET CA !</a></p>';
	        echo'</div></div>';
	    }
	}

	function move_avatar($avatar)
	{
	    $extension_upload = strtolower(substr(  strrchr($avatar['name'], '.')  ,1));
	    $name = time();
	    $nomavatar = str_replace(' ','',$name).".".$extension_upload;
	    $name = "images/avatars/".str_replace(' ','',$name).".".$extension_upload;
	    move_uploaded_file($avatar['tmp_name'],$name);
	    return $nomavatar;
	}
?>

<?php require_once("includes/footer.php"); ?>