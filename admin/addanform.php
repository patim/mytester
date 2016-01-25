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
	
	if( isset($_POST['id_theme']) ) {
		$query = "SELECT * FROM theme WHERE id_theme=".$_POST['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);		
	}

	if( isset($_GET['id_theme']) ) {
		$query = "SELECT * FROM theme WHERE id_theme=".$_GET['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);		
	}
	
	if( !empty($_POST['mark']) and !empty($_POST['qtype']) and !empty($_POST['que_text']) )	{				
		if(!isset($_POST['id_ques'])) {
			if( !empty($_FILES['pict']['tmp_name']) and substr($_FILES['pict']['type'], 0, strpos($_FILES['pict']['type'], "/")) == 'image') {
				$ext = strrchr($_FILES['pict']['name'], ".");
				$img_b =  "files/".date("YmdHis",time())."$ext"; 
				$img =  "../files/".date("YmdHis",time())."$ext"; 
				if (copy($_FILES['pict']['tmp_name'],  $img) ) {
						unlink($_FILES['pict']['tmp_name']);
					    chmod($img, 0644);
				}
				$not_pct = "";
			}
			else {
	     		$not_pct = "";
				if(substr($_FILES['pict']['type'], 0, strpos($_FILES['pict']['type'], "/")) != 'image' and  !empty($_FILES['pict']['tmp_name']))
					$not_pct = "&not_pict=1";
			}
			
			if( !empty($_FILES['sound']['tmp_name']) and substr($_FILES['sound']['type'], 0, strpos($_FILES['sound']['type'], "/")) == 'audio') {
				$ext = strrchr($_FILES['sound']['name'], ".");
				$sound_b =  "files/".date("YmdHis",time())."$ext"; 
				$sound =  "../files/".date("YmdHis",time())."$ext"; 
				if (copy($_FILES['sound']['tmp_name'],  $sound) ) {
						unlink($_FILES['sound']['tmp_name']);
					    chmod($sound, 0644);
				}
				$not_snd = "";
			}	
			else {
				$not_snd = "";	
				if(substr($_FILES['sound']['type'], 0, strpos($_FILES['sound']['type'], "/")) != 'audio' and  !empty($_FILES['sound']['tmp_name']))
		    		$not_snd = "&not_sound=1";
			}								
			
			$query = "INSERT INTO question (id_theme, qtype".(isset($img_b) ? ', picture' : '')."".(isset($sound_b) ? ', sound' : '').", que_text, mark, showhide) VALUES(".$_POST['id_theme'].", '".$_POST['qtype'].
							"'" .(isset($img_b) ? ", '".$img_b."' " : '')."".(isset($sound_b) ? ", '".$sound_b."' " : '').", '".$_POST['que_text']."', ".$_POST['mark'].", '".(isset($_POST['sh']) ? 'show' : 'hide')."')";
			mysql_query($query) or die(mysql_error());
			$id_q = mysql_insert_id();
		}
		else {
			$id_q = $_POST['id_ques'];
			
			$query = "SELECT * FROM question WHERE id_ques=".$_POST['id_ques'];
			$ques = mysql_query($query,  $trainer) or die(mysql_error());
			$row_ques = mysql_fetch_assoc($ques);
		
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
			header("Location: addqform.php?id_theme=".$row_theme['id_theme']."&id_ques=".$id_q."".$error."".$not_pctsnd);		
		//else	
			//header("Location: adminqus.php?id_theme=".$row_ques['id_theme']);		

	}
	if(isset($_GET['id_ques'])) $id_q = $_GET['id_ques'];		
?>
<html>
<head>
<title>Администрирование. Добавление ответов - шаг 2</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>

<body>

<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="152" colspan="2" valign="top">
      <?php include "adminutils/topadmin.php";?>
    </td>
  </tr>
  <tr> 
    <td width="15" valign="top"> </td>
    <td width="745" valign="top"> 
      <?php
		$query = "SELECT * FROM question WHERE id_ques=".$id_q;
		$ques = mysql_query($query,  $trainer) or die(mysql_error());
		$row_ques = mysql_fetch_assoc($ques);
?>
      <p class="body"><span class="header">Генератор вопросов</span><br>
			Добавление ответов - шаг 2 (из 3-х).</p>
      <p><a href="<?php echo "addqform.php?id_theme=".$row_theme['id_theme']."&id_ques=".$id_q;?>" class="bodylink">Назад</a></p>
      <p class="body"><strong>Тема:</strong> <?php echo $row_theme['name'];?><br>
        <strong><font size="2">Текст вопроса:</font></strong> <font size="2"><em><?php echo $row_ques['que_text'];?></em></font> 
        <?php if($row_ques['qtype'] != 'correspond')  {?>
      </p>
      <form action="addan.php" method="post" enctype="multipart/form-data" name="addan">
        <p class="body"> Введите варианты ответов</p>
          <?php 
		$query = "SELECT * FROM answer WHERE id_ques=".$id_q;
		$ans = mysql_query($query);
		if(mysql_num_rows($ans)){
			echo "<span class=rowtxt>Добавлены: <br></span>";
			while($row_ans = mysql_fetch_assoc($ans))
				echo "&nbsp;&nbsp;<span class=answer>".$row_ans['ans_text']."</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='delans.php?id_ques=".$id_q."&id_ans=".$row_ans['id_ans']."&id_theme=".$row_theme['id_theme']."'><img src=../img/del.gif border=0></a><br>";				
		}
		else echo "&nbsp;&nbsp;&nbsp;<span class=notadd>Ответы не добавлены</span>";
	?>
			<p class="reg"> Новый ответ           
          <input name="ans_text" type="text" class="editbt2" id="ans_text" tabindex="1" size="40" checked>
          &nbsp;&nbsp; 
          <input name="id_theme" type="hidden" value="<?php echo $row_theme['id_theme'];?>">
          <input name="id_ques" type="hidden" value="<?php echo $id_q;?>">
          <input name="addansub" type="submit" class="button2" id="addan" value="Добавить">
      </form>
      <?php }
else {
?>
      <form action="addan.php" method="post" enctype="multipart/form-data" name="addan">
        <p class="body"> Введите варианты ответов</p>
          
          <?php 
		$query = "SELECT * FROM answer WHERE id_ques=".$id_q;
		$ans = mysql_query($query);
		if(mysql_num_rows($ans)){
			echo "<span class=rowtxt>Добавлены:</span> <br>";
			while($row_ans = mysql_fetch_assoc($ans))
				echo "&nbsp;&nbsp;<span class=answer>".$row_ans['ans_text']."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='delans.php?id_ques=".$id_q."&id_ans=".
						$row_ans['id_ans']."&id_theme=".$row_theme['id_theme']."'><img src=../img/del.gif border=0></a><br>";				
		}
		else echo "&nbsp;&nbsp;&nbsp;<span class=notadd>Ответы не добавлены</span>";
	?>
        <p class="reg"> 
          Новый ответ 
          <input name="ans_text" type="text" class="editbt2" id="ans_text" tabindex="1" size="40">
          &nbsp;&nbsp; 
          <input name="id_theme" type="hidden" value="<?php echo $row_theme['id_theme'];?>">
          <input name="id_ques" type="hidden" value="<?php echo $id_q;?>">
          <input name="addansub" type="submit" class="button2" id="addan" value="Добавить">
      </form>
      <form action="addan.php" method="post" enctype="multipart/form-data" name="addan">
        <p class="body"> Введите варианты для соответствий (4-ый тип вопроса)</p>
          
          <?php 
		$query = "SELECT * FROM answer_corr WHERE id_ques=".$id_q;
		$ans_corr = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($ans_corr)){
			echo "<span class=rowtxt>Добавлены:</span><br>";
			while($row_ans_corr = mysql_fetch_assoc($ans_corr))
				echo "&nbsp;&nbsp;<span class=answer>".$row_ans_corr['ans_corr_text']."</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='delans.php?id_ques=".$id_q.
					"&id_ans_corr=".$row_ans_corr['id_ans_corr']."&id_theme=".$row_theme['id_theme']."'><img src=../img/del.gif border=0></a><br>";				
		}
		else echo "&nbsp;&nbsp;&nbsp;<span class=notadd>Ответы не добавлены</span>";	?>
        <p class="reg"> 
		Новый ответ 
          <input name="ans_corr_text" type="text" class="editbt2" id="ans_corr_text" tabindex="2" size="40">
          &nbsp;&nbsp; 
          <input name="id_theme" type="hidden" value="<?php echo $row_theme['id_theme'];?>">
          <input name="id_ques" type="hidden" value="<?php echo $id_q;?>">
          <input name="addansub_cr" type="submit" class="button2" id="addan" value="Добавить">
      </form>
      <?php }?>
      <form action="setrightans.php" method="post" enctype="multipart/form-data" name="form2" id="form2">
        <input name="id_ques" type="hidden" value="<?php echo $id_q;?>">
        <p> 
          <input name="step2" type="submit" class="button2" id="step2" tabindex="2" value="Далее">
        </p>
      </form>
      <p class="notadd">&nbsp;</p>	
	</td>
  </tr>
  <tr> 
    <td colspan="2" valign="bottom"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
