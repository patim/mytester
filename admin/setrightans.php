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
	
	if(isset($_POST['id_ques'])) $id_q = $_POST['id_ques'];
	if(isset($_GET['id_ques'])) $id_q = $_GET['id_ques'];
	
	$query = "SELECT * FROM question WHERE id_ques=".$id_q;
	$ques = mysql_query($query,  $trainer) or die(mysql_error());
	$row_ques = mysql_fetch_assoc($ques);
	
	if( isset($_POST['step3']) and isset($_POST['answ'])) {	
		switch($row_ques['qtype']) {
		// Формирование вариантов ответа на вопрос 1-го типа (единств. ответ) 
			case 'single': 
				$query = "UPDATE answer SET correct='yes' WHERE id_ans=".$_POST['answ'];
				mysql_query($query) or die(mysql_error());
				break;
		
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 2-го типа (неединств. ответ) 			
			case  'many': 
				foreach($_POST['answ'] as $ans) {
					$query = "UPDATE answer SET correct='yes' WHERE id_ans=".$ans;
					mysql_query($query) or die(mysql_error());
				}
				break;
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 3-го типа (задание порядка ответов) 			
			case 'order': 				
				
				foreach($_POST['answ'] as $ans => $ord) {
					$query = "UPDATE answer SET qorder=".$ord." WHERE id_ans=".$ans;
					mysql_query($query) or die(mysql_error());
				}
				break;
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 4-го типа (соответствие ответов) 			
			case  'correspond': 
				$query = "DELETE FROM correspond WHERE id_ques=".$row_ques['id_ques'];
				mysql_query($query);
				foreach($_POST['answ'] as $id_ans => $ans_corr_ar)
					foreach($ans_corr_ar as $ans_corr) {
						$query = "INSERT INTO correspond(id_ques, id_ans, id_ans_corr) VALUES(".$row_ques['id_ques'].", ".$id_ans.", ".$ans_corr.")";
						mysql_query($query) or die(mysql_error());
					}						
				break;
			case 'enter':
				foreach($_POST['answ'] as $id_ans) {
					$query = "UPDATE answer SET correct='yes' WHERE id_ans=".$id_ans;
					mysql_query($query) or die(mysql_error());
				}
		}
	}	
	if( isset($_POST['step3']) ) header("Location: adminqus.php?id_theme=".$row_ques['id_theme'] );
?>
<html>
<head>
<title>Администрирование. Выставление правильных ответов <?php if(!isset($_POST['corr'])) echo " - шаг 3";?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript">
<!--
// Расспахивающееся меню
function change(name){
  if (document.getElementById(name).style.display=='none') {
    document.getElementById(name).style.display=''; 
  } else 
  { document.getElementById(name).style.display='none';}
}
<!-- Изменение цвета шрифта на кнопке
function flashMe(eSrc, sColor) {
  eSrc.style.color=sColor
}
//-->

function MM_controlSound(x, _sndObj, sndFile) { //v3.0
  var i, method = "", sndObj = eval(_sndObj);
  if (sndObj != null) {
    if (navigator.appName == 'Netscape') method = "play";
    else {
      if (window.MM_WMP == null) {
        window.MM_WMP = false;
        for(i in sndObj) if (i == "ActiveMovie") {
          window.MM_WMP = true; break;
      } }
      if (window.MM_WMP) method = "play";
      else if (sndObj.FileName) method = "run";
  } }
  if (method) eval(_sndObj+"."+method+"()");
  else window.location = sndFile;
}
//-->
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
    <td width="15"  valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="745" valign="top">
<?php

		$query = "SELECT * FROM theme WHERE id_theme=".$row_ques['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);		
		
		if(!isset($_POST['corr'])) echo "<p class=body><span class=header>Генератор вопросов</span> <br>Добавление ответов - шаг 3 (из 3-х)</p>";
?>
      <p><a href="<?php echo (isset($_POST['corr']) ? "editans_corr.php?id_ques=".$_POST['id_ques'] :"addanform.php?id_theme=".$row_ques['id_theme']."&id_ques=".$id_q);?>" class="bodylink">Назад</a></p>
      <p class="body"><strong>Тема:</strong> <?php echo $row_theme['name'];?><br>
        <span class="body"><strong><font size="2">Текст вопроса:</font></strong></span> 
        <em><font size="2"><?php echo $row_ques['que_text'];?></font></em> </p>
<form action="" method="post" enctype="multipart/form-data" name="form2" id="form2">
  <input name="id_ques" type="hidden" value="<?php echo $id_q;?>">
  
<?php 
		if( $row_ques['picture'] )   
			echo "<p><img src='../".$row_ques['picture']."' ></p>";
	
		if( $row_ques['sound'] ) 
			echo "<input name=play type=button class=play value='play   ' onClick=\"MM_controlSound('play','document.CS1147854152515','".
					$row_ques['sound']."')\"><br><EMBED NAME='CS1147854152515' SRC='../".$row_ques['sound'].
					"' LOOP=false AUTOSTART=false MASTERSOUND HIDDEN=true WIDTH=0 HEIGHT=0></EMBED>";
	
  		$query = "SELECT * FROM answer WHERE id_ques=".$id_q;
		$answer = mysql_query($query,  $trainer) or die(mysql_error());
  
  		if( mysql_num_rows($answer) )
  		switch($row_ques['qtype']) {
		// Формирование вариантов ответа на вопрос 1-го типа (единств. ответ) 
			case 'single': 
				echo "<span class=instr>Выберите правильный вариант</span><br>"; 	
				while( $row_ans =  mysql_fetch_assoc($answer) )  
					echo "<input name=answ  type=radio value=".$row_ans['id_ans']."><span class=answer>".$row_ans['ans_text']."</span><br>";
				break;
		
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 2-го типа (неединств. ответ) 			
			case  'many': 
				echo "<span class=instr>Выберите правильный(ые) варинат(ы)</span><br>"; 
				for($i = 1; $row_ans =  mysql_fetch_assoc($answer); $i++)  						
					echo "<input name=answ[$i]  type=checkbox value=".$row_ans['id_ans']."><span class=answer>".$row_ans['ans_text']."</span><br>";	
				break;
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 3-го типа (задание порядка ответов) 			
			case 'order': 
				echo "<span class=instr>Задайте последовательность</span><br>"; 				
				$numrows =  mysql_num_rows($answer);
				while( $row_ans =  mysql_fetch_assoc($answer) )  {
					echo "<p><select class=instr name=answ[".$row_ans['id_ans']."] size=1>\n";
					echo "<option></option>";
					for ($j = 1; $j <= $numrows; $j++) 
						echo "<option value=$j >$j</option>";
					echo  "</select>";
					echo "&nbsp;<span class=answer>".$row_ans['ans_text']."</span></p>";
				}
								
				break;
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 4-го типа (соответствие ответов) 			
			case  'correspond': 
				
				echo "<span class=instr>Поставить соответствие</span><br>"; 
  				$query = "SELECT * FROM answer_corr WHERE id_ques=".$row_ques['id_ques'];
				$ans_corr = mysql_query($query,  $trainer) or die(mysql_error());

				$query = "SELECT * FROM correspond WHERE id_ques=".$row_ques['id_ques'];
				$r_ans = mysql_query($query, $trainer);
				while( $giv_ans = mysql_fetch_assoc($r_ans) ) 
					$giv_ans_ar[$giv_ans['id_ans']] [$giv_ans['id_ans_corr']] = $giv_ans['id_ans_corr'];


				for ($j = 1; $row_ans_corr =  mysql_fetch_assoc($ans_corr); $j++)
					$arr_ans_corr[$j] =  $row_ans_corr;				
				for($i = 1; $row_ans =  mysql_fetch_assoc($answer); $i++)  {
					echo "<a href=\"javascript:change('name$i')\" class=answer><img src=../img/down.gif border=0 alt='соответствие' align=absmiddle>&nbsp;&nbsp;".$row_ans['ans_text']."</a><br>";
					echo "<DIV id='name$i' style='DISPLAY: none;'>";
					for ($j = 1; $j <= count($arr_ans_corr); $j++) {
						$ans =  $row_ans['id_ans'];
						$ans_cr = $arr_ans_corr[$j]['id_ans_corr'];
						if( isset($giv_ans_ar) and isset($giv_ans_ar[$ans][$ans_cr]) and !isset($notfilled) )
							$ch = "checked";
						else 	$ch = "";
						
						echo "&nbsp;&nbsp;&nbsp;<input name=answ[".$row_ans['id_ans']."][$j] type=checkbox ".$ch." value=".$arr_ans_corr[$j]['id_ans_corr']."><span class=corr>".$arr_ans_corr[$j]['ans_corr_text']."</span><br>";
					}
					echo "</DIV><br>";
				}
				break;
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 4-го типа (ввод ответа с клавиатуры) 			
			case 'enter': 
				echo "<p clss=rowtxt>Правильный(ые) ответ(ы):<br>";
				while( $row_ans =  mysql_fetch_assoc($answer) )  
					echo "<input name=answ[".$row_ans['id_ans']."]  type=hidden value=".$row_ans['id_ans'].">&nbsp;&nbsp;<span class=answer>".$row_ans['ans_text']. "</span><br>";
				echo "</p><p class=body>Для генерации ответов 4-го типа нажмите далее</p>" ;
			break;
		}
		else 
			echo "Ответы не добавлены";
  ?>  
  <p>
          <input name="step3" type="submit" class="button2" id="step3" value="<?php echo ( !isset($_POST['corr']) ?"Завершить" : "Задать");?>">
  </p>
</form>
<p>&nbsp;</p>	
	</td>
  </tr>
  <tr valign="bottom"> 
    <td height="20" colspan="2"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
