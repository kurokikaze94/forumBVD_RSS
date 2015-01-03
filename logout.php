<?php include("lib/includes.php"); ?>
<?php include("includes/header.php"); ?>
<?php
	//$_SESSION['auth'] = 0;
	session_destroy();
	session_start();

	header("Location:".WEBROOT."index.php");
?>
<?php include("includes/footer.php"); ?>