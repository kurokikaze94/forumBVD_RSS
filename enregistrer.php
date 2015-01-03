<?php require_once("includes/header.php"); ?>
<?php if($_SESSION['auth'] != 0) {header("Location:".WEBROOT."index.php");}?>
<?php
	if (isset($_POST['submit']))
	{
	    $login_erreur1 = NULL;
	    $login_erreur2 = NULL;
	    $mdp_erreur = NULL;
	    $email_erreur1 = NULL;
	    $email_erreur2 = NULL;
	    $avatar_erreur = NULL;
	    $avatar_erreur1 = NULL;
	    $avatar_erreur2 = NULL;
	    $avatar_erreur3 = NULL;

	    $erreur = 0;
	    $login=$_POST['login'];
	    //$password = $_POST['password'];
	    //$confirm = $_POST['confirm'];
	    $email = $_POST['email'];
	    $confirmemail= $_POST['confirm'];

	    $requete=$bdd->prepare('SELECT * FROM user WHERE UserLogin =:login');
	    $requete->bindValue(':login',$login, PDO::PARAM_STR);
	    $requete->execute();

	    if($requete->rowCount() > 0)
	    {
	        $login_erreur1 = "Login deja utilisé";
	        $erreur++;
	    }

	    if (strlen($login) > 20 || strlen($login) < 1)
	    {
	        $login_erreur2 = "Login vide ou trop long";
	        $erreur++;
	    }

	    //generateur auto de password plus de declaration de pasword a la creation, reutilisation des champs pour enregistrer l'adresse mail ^^ //
	    /*if ($password != $confirm || empty($confirm) || empty($password))
	    {
	        $mdp_erreur = "Password mal confirmé";
	        $erreur++;
	    }
	    else
	    {
	    	$password = sha1($_POST['password']);
	    	$confirm = sha1($_POST['confirm']);
	    }*/

	    $requetemail=$bdd->prepare('SELECT * FROM user WHERE UserEmail =:email');
	    $requetemail->bindValue(':email',$email, PDO::PARAM_STR);
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
			echo'<h1>Inscription terminée</h1>';
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
		   	$passwordclear = rand(1000000000, 9999999999);
		   	$password = sha1($passwordclear);

		    $requete1=$bdd->prepare('INSERT INTO user (UserLogin, UserPassword, UserAvatar, UserRole, UserEmail)
		    VALUES (:login, :pass, :nomavatar, 1, :email)');
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

			$to       =   $_SESSION['email'];
			$subject  =   "Bienvenue dans la troupe";
			$message  =   "Bienvenue ! Votre mot de passe temporaire est : ".$passwordclear.". Pensez a le changer";
			$name     =   "Wbforum BVD";
			$mailsend =   sendmail($to,$subject,$message,$name);
			if($mailsend==1)
			{	
				header("Location:".WEBROOT."index.php");	  
			}
			else
			{
				echo '<h1>Bug de mail, votre password temporaire est : '.$passwordclear.'</h1>';
			}    
	    }
	    else
	    {
	    	echo'<div>';
			echo'<div>';
	        echo'<h1>Erreur</h1>';
	        echo'<p>Probleme de saisie</p>';
	        echo'<p>'.$erreur.' erreur(s)</p>';
	        echo'<p>'.$login_erreur1.'</p>';
	        echo'<p>'.$login_erreur2.'</p>';
	        echo'<p>'.$mdp_erreur.'</p>';
	        echo'<p>'.$email_erreur1.'</p>';
	        echo'<p>'.$email_erreur2.'</p>';
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
		<form method="post" action="enregistrer.php" enctype="multipart/form-data">
			<fieldset>
			<label for="login">Login : (20 caractères max)</label>  <input type="text" name="login" id="login" placeholder="Login" /><br />
			<label for="email">Email : (50 caractères max)</label><input type="email" name="email" id="email" placeholder="Email"/><br />
			<label for="confirm">Confirmer l'adresse email :</label><input type="email"name="confirm" id="confirm" placeholder="Email"/><br />
			<label for="avatar">Choisissez votre avatar : (100px*100px - 10Ko max)</label><input type="file" name="avatar" id="avatar" /><br />
			</fieldset>
			<p><input type="submit" name="submit" value="Inscription" /></p>
		</form>
	</div>
</div>

<?php require_once("includes/footer.php"); ?>