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

	$act = $_SERVER[ 'PHP_SELF' ];
		
	if(isset($_GET['id_ques']))	{
		$id_q = $_GET['id_ques'];
		$query = "SELECT * FROM question WHERE id_ques=".$id_q;
		$ques = mysql_query($query,  $trainer) or die(mysql_error());
		$row_ques = mysql_fetch_assoc($ques);
		
		$query = "SELECT * FROM theme WHERE id_theme=".$row_ques['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme= mysql_fetch_assoc($theme);		
	}

	if( isset($_POST['id_theme']) ) {
		$query = "SELECT * FROM theme WHERE id_theme=".$_POST['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);		
	}	

	if( isset($_POST['addansub']) or isset($_POST['ans_text']) )	{
		$id_q = $_POST['id_ques'];
		$query = "SELECT * FROM question WHERE id_ques=".$id_q;
		$ques = mysql_query($query,  $trainer) or die(mysql_error());
		$row_ques = mysql_fetch_assoc($ques);
				
		$query = ( ($row_ques['qtype'] == 'enter') ? "INSERT INTO answer (id_ques, ans_text, correct) VALUES(".$_POST['id_ques'].", '".$_POST['ans_text']."', 'yes' )" : 
																		"INSERT INTO answer (id_ques,  ans_text) VALUES(".$_POST['id_ques'].", '".$_POST['ans_text']."')");
	
		mysql_query($query,  $trainer) or die(mysql_error());	
		header("Location: adminqus.php?id_theme=".$_POST['id_theme']."&id_ques=".$_POST['id_ques']);
	}	
?>
<html>
<head>
<title>Администрирование. Добавление нового варианта ответа</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="152" colspan="2" valign="top"><?php include "adminutils/topadmin.php";?></td>
  </tr>
  <tr> 
    <td width="13" height="105">&nbsp;</td>
    <td width="747" valign="top">


<p class="header">Добавление нового ответа</p>
      <p ><a href="<?php echo "adminqus.php?id_theme=".$row_theme['id_theme']."&id_ques=".$id_q;?>" class="bodylink">Назад 
        на список вопросов</a></p>
      <p class="body"><strong>Тема:</strong> <?php echo $row_theme['name'];?><br>
        <strong><font size="2">Текст вопроса:</font></strong> <em><font size="2"><?php echo $row_ques['que_text'];?></font></em> </p>
<form action="<?php echo $act;?>" method="post" enctype="multipart/form-data" name="addan">
        <p class="body"> Введите новый вариант ответа</p>
    <?php 
		$query = "SELECT * FROM answer WHERE id_ques=".$id_q;
		$ans = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($ans)){
			echo "<span class=rowtxt>Добавлены: <br></span>";
			while($row_ans = mysql_fetch_assoc($ans))
				echo "&nbsp;&nbsp;<span class=answer>".$row_ans['ans_text']."</span ><br>";				
		}
		else echo "&nbsp;&nbsp;&nbsp;<span class=notadd>Ответы не добавлены</span>";
	?>
    <p>
    <input name="ans_text" type="text" id="ans_text" size="40" tabindex="1">
    &nbsp;&nbsp; 
    <input name="id_theme" type="hidden" value="<?php echo $row_theme['id_theme'];?>">
    <input name="id_ques" type="hidden" value="<?php echo $id_q;?>">
          <input name="addansub" type="submit" class="button2" id="addan" value="Добавить">
</form>	
	</td>
  </tr>
  <tr valign="bottom"> 
    <td colspan="2"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
