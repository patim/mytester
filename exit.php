<?php 
	session_start(); 
	require_once('Connections/trainer.php');
	
	if(isset($_SESSION['Username']) ) {
		unset($_SESSION['Username']);
		unset($_SESSION['Userrights']);
	  	echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL= index.php '></HEAD></HTML>";		
	}
?>