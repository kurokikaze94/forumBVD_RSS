<?php
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=webforumrss','root','');
		$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
		$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
	}
	catch(Exception $e)
	{
		die('Erreur de Connexion: '.$e->getMessage());
	}
?>