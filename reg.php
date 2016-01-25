<?php
	session_start();
	require_once('Connections/trainer.php');
		
	if( isset($_POST['email']) ) $_SESSION['email_tmp'] = $_POST['email'];

	if( isset($_POST['name']) ) {
		$_SESSION['name_tmp'] = $_POST['name']; 
		$query = "SELECT * FROM user WHERE name='".$_POST['name']."'";
		$user = mysql_query($query);
		$error = "?";
		if( mysql_num_rows($user) or $_POST['name'] == 'guest' ) $error = "?wname=1&";
	}
	
	if( $_POST['pass'] != $_POST['pass2']) $error = $error."wpass=1";
	
	if(isset($_POST['name']) and isset($_POST['email']) and isset($_POST['pass']) and $error == "?") {
		$query = "INSERT INTO user (name, password, email, regdate) VALUES ('".$_POST['name']."', '".$_POST['pass']."', '".$_POST['email']."', '".date("Y-m-d H:i:s")."')";
		mysql_query($query) or die(mysql_error());
		unset($_SESSION['name_tmp']);
		unset($_SESSION['email_tmp']);
		//echo $query;
		$_SESSION['Username'] = $_POST['name'];
		$_SESSION['Userrights'] = 'user';
		header("Location: regok.php");
	}
	else 
		header("Location: regform.php".$error);
		//echo "Location: adduserform.php?".$error;

?>