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

	// Удаление со страницы добавления вопросов или редактирования (для вопросов 4-го и 3-го типов)
	if(isset($_GET['id_ans']) and isset($_GET['id_theme']) and isset($_GET['id_ques']) )	 {
			$query = "DELETE FROM answer WHERE id_ans=".$_GET['id_ans'];
			mysql_query($query);
			
			if(isset($_GET['corr'])) {
				$query = "DELETE FROM correspond WHERE id_ans=".$_GET['id_ans'];
				mysql_query($query);
				header("Location: editans_corr.php?id_theme=".$_GET['id_theme']."&id_ques=".$_GET['id_ques']);
			}
			else {	
				if(isset($_GET['ord']))
					header("Location: setrightans.php?id_ques=".$_GET['id_ques']);
				else 
					header("Location: addanform.php?id_theme=".$_GET['id_theme']."&id_ques=".$_GET['id_ques']);
				
			}
	}
	// Удаление со страницы вопросов (для вопросов 1-го, 2-го, 5-го типов)
	else 
		if( isset($_GET['id_ans']) and isset($_GET['id_ques']) ) {
			$query = "SELECT id_theme FROM question WHERE id_ques=".$_GET['id_ques'];
			$theme = mysql_query($query,  $trainer) or die(mysql_error());
			$row_theme = mysql_fetch_assoc($theme);
			
			$query = "DELETE FROM answer WHERE id_ans=".$_GET['id_ans'];
			mysql_query($query);
			header("Location: adminqus.php?id_theme=".$row_theme['id_theme']."&id_ques=".$_GET['id_ques']);
		}		
	
	if(isset($_GET['id_ans_corr']) and isset($_GET['id_theme']) and isset($_GET['id_ques']))	 {
			$query = "DELETE FROM answer_corr WHERE id_ans_corr=".$_GET['id_ans_corr'];
			mysql_query($query) or die(mysql_error());
			
			if(isset($_GET['corr'])) {
				$query = "DELETE FROM correspond WHERE id_ans_corr=".$_GET['id_ans_corr'];
				mysql_query($query);
				header("Location: editans_corr.php?id_theme=".$_GET['id_theme']."&id_ques=".$_GET['id_ques']);
			}
			else	
				header("Location: addanform.php?id_theme=".$_GET['id_theme']."&id_ques=".$_GET['id_ques']);
	}
	
?>
