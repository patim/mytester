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
	
	if( isset($_GET['id_ques']) ) {	
		$query = "SELECT * FROM question WHERE id_ques=".$_GET['id_ques'];
		$ques = mysql_query($query,  $trainer) or die(mysql_error());
		$row_ques = mysql_fetch_assoc($ques);

		$query = "SELECT * FROM theme WHERE id_theme=".$row_ques['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);
	
		if( isset($_GET['id_ans_corr']) )	{
			$query = "SELECT * FROM answer_corr WHERE id_ans_corr=".$_GET['id_ans_corr'];
			$ans_corr = mysql_query($query,  $trainer) or die(mysql_error());
			$row_ans_corr = mysql_fetch_assoc($ans_corr);
		}
		else{
			$query = "SELECT * FROM answer WHERE id_ans=".$_GET['id_ans'];
			$ans = mysql_query($query,  $trainer) or die(mysql_error());
			$row_ans = mysql_fetch_assoc($ans);
		}	
	}
	if( isset($_POST['ans_edit']) )	{
		if(!$_GET['id_ans_corr']) {
			$query = "SELECT qtype FROM question WHERE id_ques=".$_POST['id_ques'];
			$qtype = mysql_query($query,  $trainer) or die(mysql_error());
			$row_qtype  = mysql_fetch_assoc($qtype );
			
			if($row_qtype['qtype'] == 'single') {
				$query = "UPDATE answer SET correct=NULL WHERE id_ques=".$_POST['id_ques'];
				mysql_query($query) or die(mysql_error());
			}
		
			if( isset($_POST['right']) ) $ch = "'yes'";
			else 
				if($row_qtype['qtype'] != 'enter')
					$ch = 'NULL';
				else 
					$ch = "'yes'";
			
			$query = "UPDATE answer SET ans_text='".$_POST['ans_edit']."',  correct=".$ch." WHERE id_ans=".$_POST['id_ans'];
			mysql_query($query) or die(mysql_error());
		}
		else {
			$query = "UPDATE answer_corr SET ans_corr_text='".$_POST['ans_edit']."' WHERE id_ans_corr=".$_POST['id_ans_corr'];
			mysql_query($query) or die(mysql_error());
		}
			
		if(!$_GET['corr']) 		
			header("Location: adminqus.php?id_theme={$_POST['id_theme']}&id_ques={$_POST['id_ques']}");
		else
			header("Location: editans_corr.php?id_ques={$_POST['id_ques']}");
	}
?>
<html>
<head>
<title>Администрирование. Редактирование вопроса</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript">
<!-- Скрыть

function vlidate(form) {
	if (form.ans_edit.value == "") 
    		alert("Пожалуйста, введите вопрос!");								
	else 
     		form.submit();
}
// -->
</script>
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>

<body>

<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="152" colspan="2" valign="top"><?php include "adminutils/topadmin.php";?></td>
  </tr>
  <tr> 
    <td width="15" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="745" valign="top">
      <p class="header">Редактирование ответа</p>
      <?php
if( isset($_GET['corr']) )
	echo "<p><a href='editans_corr.php?id_ques=".$_GET['id_ques']."' class=bodylink>Назад</a></p>";	
else 
	echo "<p><a href='adminqus.php?id_theme=".$row_ques['id_theme']."&id_ques=".$_GET['id_ques']."' class=bodylink>Назад</a></p>";

?>
      <p class="body"><strong>Тема:</strong> <?php echo $row_theme['name'];?><br>
        <strong><font size="2">Вопрос:</font></strong> <font size="2"><em><?php echo $row_ques['que_text'];?></em></font></p>
      <form action="" method="post" name="editform" id="editform" onSubmit="validate(this.form)">
        <p class="reg"> 
          <input name="id_theme" type="hidden" value="<?php echo $row_theme['id_theme'];?>">
          <input name=id_ques type=hidden value="<?php echo $_GET['id_ques'];?>" >
          <input name=<?php echo (isset($_GET['id_ans']) ? "id_ans": "id_ans_corr");?> type=hidden value="<?php echo (isset($_GET['id_ans']) ? $_GET['id_ans' ]: $_GET['id_ans_corr'] );?>" >
          <input name="ans_edit" type="text" class="editbt2" id="ans_edit" value="<?php echo (isset($_GET['id_ans_corr']) ? $row_ans_corr['ans_corr_text'] : $row_ans['ans_text'] );?>" size="50">
          <?php if(!isset($_GET['corr'])) {?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Правильный 
          <input name="right" <?php echo ($row_ques['qtype'] == 'enter') ? "disabled" : "";?> type="checkbox" id="right" value="checkbox" <?php echo ($row_ans['correct'] == 'yes') ? "checked" : "";?> >
          <?php }?></p>
          </p>
        <p> &nbsp;&nbsp;&nbsp; 
          <input name="change" type="button" class="button2" id="change" onClick="vlidate(this.form)" value="Изменить">
  </p>
</form>
<p>&nbsp;</p>	
	</td>
  </tr>
  <tr valign="bottom"> 
    <td height="22" colspan="2"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
