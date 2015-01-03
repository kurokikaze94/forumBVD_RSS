<?php
	function setFlash($message, $type = 'success')
	{
		$_SESSION['flash']['message']=$message;
		$_SESSION['flash']['type']=$type;
	}

	function flash()
	{
		if(isset($_SESSION['flash']))
		{
			extract($_SESSION['flash']);
			unset($_SESSION['flash']);
			echo "<div class='container theme-showcase'><div class='alert alert-".$type."'><h3><b><font color='red'>".$message."</font></b></h3></div></div>";
		}
	}
?>