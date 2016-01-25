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

	if( isset($_POST['name']) and isset($_POST['id_user']) ) {
		$_SESSION['name_tmp'] = $_POST['name']; 
		$_SESSION['id_user_tmp'] = $_POST['id_user']; 
		$query = "SELECT * FROM user WHERE name='".$_POST['name']."' AND id_user !=".$_POST['id_user'];
		$user = mysql_query($query);
		$error = "?";
		if( (mysql_num_rows($user) or $_POST['name'] == 'guest') ) $error = "?wname=1&";
	}
	
	if( isset($_POST['pass']) and isset($_POST['pass2']) and $_POST['pass'] != $_POST['pass2']) $error = $error."wpass=1";
	
	if(isset($_POST['name']) and isset($_POST['email']) and isset($_POST['id_user']) and $error == "?") {
		$is_pas = ( !empty($_POST['pass'])? ", password='".$_POST['pass']."'" : "");
		$query = "UPDATE user SET name='".$_POST['name']."', email='".$_POST['email']."'".$is_pas." WHERE id_user=".$_POST['id_user'];
		mysql_query($query) or die(mysql_error());
		
		$query = "SELECT rights FROM user WHERE id_user =".$_POST['id_user'];
		$rights = mysql_query($query) or die(mysql_error());	
		$row_rights =mysql_fetch_assoc($rights);
		//echo $_POST['id_user'];
		if( $_POST['name'] != $_SESSION['Username'] and $row_rights['rights'] == 'admin')	$_SESSION['Username'] = $_POST['name'];
		
		unset($_SESSION['name_tmp']);
		unset($_SESSION['email_tmp']);
		unset($_SESSION['id_user_tmp']);	
		//echo $_SESSION['Username'];
		header("Location: users.php");		
	}
	else 
		header("Location: edituserform.php".$error);
		//echo "Location: adduserform.php?".$error;

?>
