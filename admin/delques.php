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

	if(isset($_GET['id_ques']))	 {
		$query = "SELECT * FROM question WHERE id_ques=".$_GET['id_ques'];
		$ques = mysql_query($query,  $trainer) or die(mysql_error());
		$row_ques = mysql_fetch_assoc($ques);
		
		if($row_ques['picture']) unlink("../".$row_ques['picture']);
		if($row_ques['sound']) unlink("../".$row_ques['sound']);
			
		$query = "DELETE FROM question WHERE id_ques=".$_GET['id_ques'];
		mysql_query($query) or die(mysql_error());
  		echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL=adminqus.php?id_theme=".$row_ques['id_theme']."'></HEAD></HTML>";						
		exit();
	}
?>
