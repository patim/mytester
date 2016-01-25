<?php
	session_start();
	$restrictGoTo=  "index.php";	
	if (  !isset($_SESSION['Username'])  )	header("Location: ". $restrictGoTo);
		else {
		require_once('../Connections/trainer.php');
		$query = "SELECT id_user, rights FROM user WHERE name='". $_SESSION['Username']."'";
		$u = mysql_query($query, $trainer) or die(mysql_error());
		$u_row = mysql_fetch_assoc($u);
		if($u_row['rights'] != 'admin') { 
			header("Location: ". $restrictGoTo);
			exit();
		}
	}

	$act = $_SERVER[ 'PHP_SELF' ];

	if(isset($_GET['id_theme']))	 {
			$query = "DELETE FROM theme WHERE id_theme=".$_GET['id_theme'];
			mysql_query($query) or die(mysql_error());
			header("Location: admin_thm.php");
	}
?>