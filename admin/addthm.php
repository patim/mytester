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
	// Îáðàáîòêà âñòàâêè òåìû
	if( !empty($_POST['thm_name']) )	{
		$query = "INSERT INTO theme(name, date, showhide) VALUES ('".$_POST['thm_name']."', '".date("Y-m-d H:i:s")."', 'hide')";
		mysql_query($query) or die(mysql_error());
		header("Location: admin_thm.php");
	}
	else  {
		$_SESSION['no_theme'] = 1;
		header("Location: admin_thm.php");
	}
?>
