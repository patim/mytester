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

	if( isset($_GET['id_user']) ) {
		$query = "SELECT id_stat_th FROM stat_theme WHERE id_user=".$_GET['id_user'];
		$id_stat = mysql_query($query);
		while( $row_id_stat = mysql_fetch_assoc($id_stat) ) {
			$query = "DELETE FROM stat_wrans WHERE id_stat_th=".$row_id_stat['id_stat_th'];
			mysql_query($query) or die(mysql_error());

			$query = "DELETE FROM stat_theme WHERE id_stat_th=".$row_id_stat['id_stat_th'];
			mysql_query($query) or die(mysql_error());			
		}		
		header("Location: stat.php?id_user=".$_GET['id_user']);
	}
?>