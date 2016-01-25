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
	
	$query = "SELECT * FROM user WHERE name='guest' AND password='guest'";
	$guest = mysql_query($query);
	if(mysql_num_rows($guest) and isset($_GET['off'])) {
		$query = "DELETE FROM user WHERE name='guest' AND password='guest'";
		mysql_query($query);
		header("Location: users.php");
	}
	else {
		if( isset($_GET['on']) ) {
			$query = "INSERT INTO user (name, password) VALUES ('guest', 'guest')";
			mysql_query($query) or die(mysql_error());
			header("Location: users.php");
		}	
	}
?>