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
		
	if(isset($_GET['id_ques']))	{
		$id_q = $_GET['id_ques'];
		$query = "SELECT * FROM question WHERE id_ques=".$id_q;
		$ques = mysql_query($query,  $trainer) or die(mysql_error());
		$row_ques = mysql_fetch_assoc($ques);
		
		$query = "SELECT * FROM theme WHERE id_theme=".$row_ques['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme= mysql_fetch_assoc($theme);		
	}
?>
<html>
<head>
<title>Администрирование. Редактирование вопросов</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="24" colspan="2" valign="top"><?php include "adminutils/topadmin.php";?></td>
  </tr>
  <tr> 
    <td width="15" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="745" valign="top">
<p class="header">Редактирование наборов вопросов</p>
<p><a href="<?php echo "adminqus.php?id_theme=".$row_theme['id_theme']."&id_ques=".$id_q;?>" class="bodylink">Назад на список вопросов</a></p>
      <p class="body"><strong>Тема:</strong> <?php echo $row_theme['name'];?><br>
        <strong><span class="body"><font size="2">Текст вопроса:</font></span></strong> 
        <font size="2"><em><?php echo $row_ques['que_text'];?></em></font> </p>
<form action="addan.php" method="post" enctype="multipart/form-data" name="addan">
        <p class="body"> Введите варианты ответов</p>
          
        <?php 
		$query = "SELECT * FROM answer WHERE id_ques=".$id_q;
		$ans = mysql_query($query);
		if(mysql_num_rows($ans)){
			echo "<span class=rowtxt>Добавленные: </span><br>";
			while($row_ans = mysql_fetch_assoc($ans))
				echo "&nbsp;&nbsp;<span class=answer>".$row_ans['ans_text']."</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='delans.php?id_ques=".$id_q."&id_ans=".
							$row_ans['id_ans']."&id_theme=".$row_theme['id_theme']."&corr=1'><img src=../img/del.gif border=0></a>
							&nbsp;&nbsp;<a href='editans.php?id_ques=".$id_q."&id_ans=".$row_ans['id_ans']."&corr=1'><img src=../img/editsmall.gif border=0></a>
							<br>";				
		}
		else echo "&nbsp;&nbsp;&nbsp;<span class=notadd>Ответы не добавлены</span>";
	?>
        <p class="reg">
          Новый ответ 
          <input name="ans_text" type="text" class="editbt2" id="ans_text" tabindex="1" size="40">
          &nbsp;&nbsp; 
          <input name="id_theme" type="hidden" value="<?php echo $row_theme['id_theme'];?>">
          <input name="id_ques" type="hidden" value="<?php echo $id_q;?>">
          <input name="corr" type="hidden" value="1">
          <input name="addansub" type="submit" class="button2" id="addan" value="Добавить">
        </p>
      </form>
<?php if($row_ques['qtype'] == 'correspond') {?>
<form action="addan.php" method="post" enctype="multipart/form-data" name="addan">
        <p class="body"> Введите варианты для соответствий</p>
          <?php 
		$query = "SELECT * FROM answer_corr WHERE id_ques=".$id_q;
		$ans_corr = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($ans_corr)){
			echo "<span class=rowtxt>Добавленные: </span><br>";
			while($row_ans_corr = mysql_fetch_assoc($ans_corr))
				echo "&nbsp;&nbsp;<span class=answer>".$row_ans_corr['ans_corr_text']."</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='delans.php?id_ques=".$id_q."&id_ans_corr=".
						$row_ans_corr['id_ans_corr']."&id_theme=".$row_theme['id_theme']."&corr=1'><img src=../img/del.gif border=0></a>
						&nbsp;&nbsp;<a href='editans.php?id_ques=".$id_q."&id_ans_corr=".$row_ans_corr['id_ans_corr']."&corr=1'><img src=../img/editsmall.gif border=0></a>
						<br>";				
		}
		else echo "&nbsp;&nbsp;&nbsp;<span class=notadd>Ответы не добавлены</span>";
	?>
<p class="reg"> Новый ответ  
          <input name="ans_corr_text" type="text" class="editbt2" id="ans_corr_text" tabindex="2" size="40">
    &nbsp;&nbsp; 
    <input name="id_theme" type="hidden" value="<?php echo $row_theme['id_theme'];?>">
    <input name="id_ques" type="hidden" value="<?php echo $id_q;?>">
	<input name="corr" type="hidden" value="1">
    <input name="addansub_cr" type="submit" class="button2" id="addan" value="Добавить">	
</p>	
	</form>
<?php }
	$notnulcorr = ( ($row_ques['qtype'] == 'correspond') ? mysql_num_rows($ans_corr) : 1);
	if(mysql_num_rows($ans) and $notnulcorr) {?>
<form action="setrightans.php" method="post" enctype="multipart/form-data" name="form2" id="form2">
  <input name="corr" type="hidden" value="1">
  <input name="id_ques" type="hidden" value="<?php echo $id_q;?>">
  <p>
          <input name="sеt_corr" type="submit" class="button2" id="sеt_corr" tabindex="2" value="Задать <?php echo ($row_ques['qtype'] == 'correspond') ? "соответствие" : "порядок";?>">
  <?php }?>
  </p>
</form>
<p>&nbsp;</p>	
	</td>
  </tr>
  <tr valign="bottom"> 
    <td height="24" colspan="2"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
