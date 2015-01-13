<?php require_once("includes/header.php"); ?>
<?php if($_SESSION['auth'] != 0) {header("Location:".WEBROOT."index.php");}?>
<?php
	echo"<h1>LOGIN</h1>";

	if (isset($_POST['submit']))
	{	
		extract($_POST);
		if(preg_match('#^[a-z0-9]{1,20}$#', $login))
		{
			if (isset($password))
			{
				$password = sha1($password);
				$requete = $bdd->prepare('SELECT * FROM user WHERE userlogin =:login AND userpassword = :password');
				$requete->bindValue(':login',$login, PDO::PARAM_STR);
				$requete->bindValue(':password',$password, PDO::PARAM_STR);
				$requete->execute();
				if($requete->rowCount()==1)
				{
					extract($requete->fetch());
					if($UserRole == 0)
					{
						setFlash("Ce compte a été banni, veuillez contacter un admin.<br/><a href='index.php'>Retour Acceuil</a> <br> ","Danger");
						echo flash();
					}
					else
					{	
						$_SESSION['auth'] = 1;
						$_SESSION['id'] = $UserId;
						$_SESSION['login'] = $UserLogin;
						$_SESSION['droit'] = $UserRole;
						$_SESSION['email'] = $UserEmail;
						$_SESSION['nom'] = $UserNom;
						$_SESSION['prenom'] = $UserPrenom;
						$_SESSION['tel'] = $UserTel;
						$_SESSION['membre_avatar'] = $UserAvatar;
						header("Location:".WEBROOT."index.php");
					}
				}
				else
				{
					setFlash("Veuillez verifier vos identifiants ou contacter un administrateur.<br/><a href='enregistrer.php'>Créer un compte</a> <br> ","Danger");
					echo flash();
				}
			}
			else
			{
				setFlash("Et votre mot de passe alors ?<a href='login.php'>Retry</a> <br> ","Danger");
				echo flash();
			}
		}
		else	
		{
			setFlash("Votre login a un format etrange<a href='login.php'>Retry</a> <br> ","Danger");
			echo flash();
		}
	}
?>

			<h1>Connexion</h1>
			<form method="post" action="">
				<div>
					<label for="login">Login:</label>
					<input type="text" name="login" id="login" placeholder="Login"/>
				</div>
				<div>
					<label for="password">Password:</label>
					<input type="password" name="password" id="password" placeholder="Password"/>
				</div>
				<input type="submit" value="Se Connecter" name="submit"/>
			</form>

<?php require_once("includes/footer.php"); ?>