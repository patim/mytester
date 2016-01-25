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
		
	// Обработка темы
	if( isset($_GET['id_theme_sh']) )	{
		
		$query = "SELECT showhide FROM theme WHERE id_theme=".$_GET['id_theme_sh'];
		$sh = mysql_query($query) or die(mysql_error());
		$row_sh = mysql_fetch_assoc($sh);
		if($row_sh['showhide'] == 'show')
			$sh_new = 'hide';
		else 
			$sh_new = 'show';
			
		$query  = "UPDATE theme SET showhide='".$sh_new."' WHERE id_theme=".$_GET['id_theme_sh'];
		mysql_query($query);			
		header("Location: admin_thm.php");
	}
	
		// Обработка вопроса
	if( isset($_GET['id_ques_sh']) )	{
		
		$query = "SELECT showhide FROM question WHERE id_ques=".$_GET['id_ques_sh'];
		$sh = mysql_query($query) or die(mysql_error());
		$row_sh = mysql_fetch_assoc($sh);
		if($row_sh['showhide'] == 'show')
			$sh_new = 'hide';
		else 
			$sh_new = 'show';
			
		$query  = "UPDATE question SET showhide='".$sh_new."' WHERE id_ques=".$_GET['id_ques_sh'];
		mysql_query($query);			
		header("Location: adminqus.php?id_theme=".$_GET['id_theme']);
	}
?>
