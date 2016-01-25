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
	
	
	if( isset($_POST['email']) ) $_SESSION['email_tmp'] = $_POST['email'];

	if( isset($_POST['name']) ) {
		$_SESSION['name_tmp'] = $_POST['name']; 
		$query = "SELECT * FROM user WHERE name='".$_POST['name']."'";
		$user = mysql_query($query);
		$error = "?";
		if( mysql_num_rows($user) or $_POST['name'] == 'guest' ) $error = "?wname=1&";
	}
	
	if( isset($_POST['pass']) and isset($_POST['pass2']) and $_POST['pass'] != $_POST['pass2']) $error = $error."wpass=1";
	
	if(isset($_POST['name']) and isset($_POST['email']) and isset($_POST['pass']) and $error == "?") {
		$query = "INSERT INTO user (name, password, email, regdate) VALUES ('".$_POST['name']."', '".$_POST['pass']."', '".$_POST['email']."', '".date("Y-m-d H:i:s")."')";
		mysql_query($query) or die(mysql_error());
		unset($_SESSION['name_tmp']);
		unset($_SESSION['email_tmp']);
		//echo $query;
		header("Location: users.php");
	}
	else 
		header("Location: adduserform.php".$error);
		//echo "Location: adduserform.php?".$error;

?>