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
	  		echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL= ".$restrictGoTo."'></HEAD></HTML>";
			exit();
		}
	}

	if( isset($_POST['id_theme']) ) {
		$query = "SELECT * FROM theme WHERE id_theme=".$_POST['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);		
	}	

	if( isset($_POST['addansub']) or isset($_POST['ans_text']) )	{
		$query = "INSERT INTO answer (id_ques,  ans_text) VALUES(".$_POST['id_ques'].", '".$_POST['ans_text']."')";
		mysql_query($query,  $trainer) or die(mysql_error());
		
		if(isset($_POST['corr']))
	  		echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL=editans_corr.php?id_theme=".$_POST['id_theme']."&id_ques=".$_POST['id_ques']."'></HEAD></HTML>";			
		else	
	  		echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL=addanform.php?id_theme=".$_POST['id_theme']."&id_ques=".$_POST['id_ques']."'></HEAD></HTML>";			
			
	}	
	if( isset($_POST['addansub_cr']) or isset($_POST['ans_corr_text']) )	{
		$query = "INSERT INTO answer_corr (id_ques,  ans_corr_text) VALUES(".$_POST['id_ques'].", '".$_POST['ans_corr_text']."')";
		mysql_query($query,  $trainer) or die(mysql_error());
		
		if(isset($_POST['corr']))
	  		echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL=editans_corr.php?id_theme=".$_POST['id_theme']."&id_ques=".$_POST['id_ques']."'></HEAD></HTML>";			
		else	
	  		echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL=addanform.php?id_theme=".$_POST['id_theme']."&id_ques=".$_POST['id_ques']."'></HEAD></HTML>";			

	}
?>
