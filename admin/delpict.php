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
	if(isset($_GET['id_ques'])) {
		$query = "SELECT * FROM question WHERE id_ques=".$_GET['id_ques'];
		$ques = mysql_query($query,  $trainer) or die(mysql_error());
		$row_ques = mysql_fetch_assoc($ques);

		if($row_ques['picture']) unlink("../".$row_ques['picture']);
							
		$query = "UPDATE question SET picture=NULL WHERE id_ques=".$_GET['id_ques'];
		mysql_query($query) or die(mysql_error());				
		
		// Довольно грубый способ определения страницы с которой пришли. Нужно в будущем переделать...
		if(isset($_GET['id_theme'])) 
			header("Location: addqform.php?id_theme=".$row_ques['id_theme']."&id_ques=".$_GET['id_ques']);
		else 
			header("Location: editqform.php?id_ques=".$_GET['id_ques']);
	}

?>
