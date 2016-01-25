<?php
	/*ini_set('display_errors', 1);
	error_reporting(E_ALL);
	print_r($_FILES);*/
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
	
	if( !empty($_POST['mark']) and !empty($_POST['qtype']) and !empty($_POST['que_text']) )	{						
		if(isset($_POST['id_ques'])) {
			$id_q = $_POST['id_ques'];
			
			$query = "SELECT * FROM question WHERE id_ques=".$_POST['id_ques'];
			$ques = mysql_query($query,  $trainer) or die(mysql_error());
			$row_ques = mysql_fetch_assoc($ques);
			
			$query = "SELECT * FROM theme WHERE id_theme=".$row_ques['id_theme'];
			$theme = mysql_query($query,  $trainer) or die(mysql_error());
			$row_theme = mysql_fetch_assoc($theme);
		
			$query = "SELECT * FROM answer WHERE id_ques=".$_POST['id_ques'];
			$ans = mysql_query($query,  $trainer) or die(mysql_error());
			$num_ans = mysql_num_rows($ans);
	
			$query = "SELECT * FROM answer_corr WHERE id_ques=".$_POST['id_ques'];
			$ans_corr = mysql_query($query,  $trainer) or die(mysql_error());
			$num_ans_corr = mysql_num_rows($ans_corr);		
			
			if($row_ques['que_text'] != $_POST['que_text']) {
				$query = "UPDATE question SET que_text='".$_POST['que_text']."' WHERE id_ques=".$_POST['id_ques'];
				mysql_query($query) or die(mysql_error());
			}
			
			$shh = (isset($_POST['sh'])) ? 'show' : 'hide';
			if($row_ques['que_text'] != $shh) {
				$query = "UPDATE question SET showhide='".$shh."' WHERE id_ques=".$_POST['id_ques'];
				mysql_query($query) or die(mysql_error());
			}
			
			if($row_ques['mark'] != $_POST['mark']) {
				$query = "UPDATE question SET mark=".$_POST['mark']." WHERE id_ques=".$_POST['id_ques'];
				mysql_query($query) or die(mysql_error());
			}
			
			if($row_ques['qtype'] != $_POST['qtype'] and !$num_ans_corr and !$num_ans) {
				$query = "UPDATE question SET qtype='".$_POST['qtype']."' WHERE id_ques=".$_POST['id_ques'];
				mysql_query($query) or die(mysql_error());
			}
			
			if( !empty($_FILES['pict']['tmp_name']) and substr($_FILES['pict']['type'], 0, strpos($_FILES['pict']['type'], "/")) == 'image') {
				if($row_ques['picture']) unlink("../".$row_ques['picture']);
								
				$ext = strrchr($_FILES['pict']['name'], ".");
				$img_b =  "files/".date("YmdHis",time())."$ext"; 
				$img =  "../files/".date("YmdHis",time())."$ext"; 
				if (copy($_FILES['pict']['tmp_name'],  $img) ) {
						unlink($_FILES['pict']['tmp_name']);
					    chmod($img, 0644);
				}
				$query = "UPDATE question SET picture='".$img_b."' WHERE id_ques=".$_POST['id_ques'];
				mysql_query($query) or die(mysql_error());
				$not_pct = "";				
			}
			else {
	     		$not_pct = "";
				if(substr($_FILES['pict']['type'], 0, strpos($_FILES['pict']['type'], "/")) != 'image' and  !empty($_FILES['pict']['tmp_name']))
					$not_pct = "&not_pict=1";
			}			

			if( !empty($_FILES['sound']['tmp_name']) and substr($_FILES['sound']['type'], 0, strpos($_FILES['sound']['type'], "/")) == 'audio') {
				if($row_ques['sound']) unlink("../".$row_ques['sound']);
				
				$ext = strrchr($_FILES['sound']['name'], ".");
				$sound_b =  "files/".date("YmdHis",time())."$ext"; 
				$sound =  "../files/".date("YmdHis",time())."$ext"; 
				if (copy($_FILES['sound']['tmp_name'],  $sound) ) {
						unlink($_FILES['sound']['tmp_name']);
					    chmod($sound, 0644);
				}
				$query = "UPDATE question SET sound='".$sound_b."' WHERE id_ques=".$_POST['id_ques'];
				mysql_query($query) or die(mysql_error());
				$not_snd = "";
			}
			else {
				$not_snd = "";	
				if(substr($_FILES['sound']['type'], 0, strpos($_FILES['sound']['type'], "/")) != 'audio' and  !empty($_FILES['sound']['tmp_name']))
		    		$not_snd = "&not_sound=1";
			}						
		}

		$error = "";
		if($_FILES['sound']['error'] == 1) 	$error = "&sound=1";
		if($_FILES['pict']['error'] == 1) 	$error = $error."&pict=1";
		/*
		echo  "pct=".$not_pct."<br> sound=".$not_snd;
		//echo substr($_FILES['sound']['type'], 0, strpos($_FILES['sound']['type'], "/"));
		echo "<br>";
		print_r($_FILES['pict']);
		print_r($_FILES['sound']);
		*/
		$not_pctsnd = $not_pct."".$not_snd;

		if($error != "" or $not_pctsnd != "")
			header("Location: editqform.php?id_ques=".$_POST['id_ques']."".$error."".$not_pctsnd);		
		else	
			header("Location: adminqus.php?id_theme=".$row_ques['id_theme']);	
	}	
?>
