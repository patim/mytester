<?php 
	session_start();
	$restrictGoTo=  "index.php";	
	if ( !isset($_SESSION['Username']) )	header("Location: ". $restrictGoTo);
	else {
		require_once('Connections/trainer.php');
		$query = "SELECT id_user FROM user WHERE name='". $_SESSION['Username']."'";
		$id_u = mysql_query($query, $trainer) or die(mysql_error());
		$id_u_row = mysql_fetch_assoc($id_u);
	}
	//print_r($_SESSION);
?>
<html>
<head>
<title>Тест закончен! </title>
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
//-->
</script>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td width="760" height="153" valign="top"><?php include "utils/top.php";?></td>
  </tr>
  <tr> 
    <td valign="top"> <p ><a href="themes.php" class="bodylink">Назад 
        на список тем</a> </p>
      <p class="body">Тест закончен!<?php //echo " sess ".$_SESSION['passed'];?></p>
      <p class="body"> <strong>
        <?php if( isset($_SESSION['thm']) ) {?>
        <?php 
	$theme = mysql_query( "SELECT name FROM theme WHERE id_theme=".$_SESSION['thm'] );
	$row_thm = mysql_fetch_assoc($theme);
	echo "Тема. ".$row_thm['name'];
	
	$query = "SELECT * FROM stat_theme WHERE id_stat_th =". $_SESSION['stat_th'];
	$stat = mysql_query($query);
	$row_stat= mysql_fetch_assoc($stat);
?>
        </strong></p>
      <p class="body">Общее количество балов: <?php echo array_sum($_SESSION['marks'])." (из ".$row_stat['topmark'].")";?> 
        <br>
<?php 
	
	echo "Всего вопросов: ".$row_stat['allques']."<br>";
	
	// Подсчёт вопрсов с правильными ответатми	
	$ra = 0;
	foreach($_SESSION['marks'] as $v) 
		if($v)	 $ra++;
	
	// Подсчёт вопрсов с ошибочными ответатми
	$query = "SELECT * FROM stat_wrans WHERE id_stat_th =".$_SESSION['stat_th']." ORDER BY num";
	$wrans = mysql_query($query); 
	while( $row_wrans = mysql_fetch_assoc($wrans) )	{
		$i = $row_wrans['id_ques'];
		if( !isset($q[$i]) )	{
			$query = "SELECT que_text, qtype FROM question WHERE id_ques=".$i;
			$wq = mysql_query($query);
			$row_wq = mysql_fetch_assoc($wq);
			$row_wq['que_text'] = "Вопрос №".$row_wrans['num'].". <b>".$row_wq['que_text']."</b><br>";
			$q[$i] =  $row_wq;			
		}
	}
	
//	echo "Пропущенно вопросов: ". ($num_ques - ( $ra + (isset($q) ? sizeof($q) : 0) )) . "<br>";
	echo "Пропущенно вопросов: ". ($row_stat['allques'] - $row_stat['giv_qu']) . "<br>";
//	echo "Заданно вопросов: ".$row_giv_qu['giv_qu'] . "<br>";

	echo "Правильных ответов: ".$ra."<br>";
	echo "Неверных ответов: ".(isset($q) ? sizeof($q) : 0)."<br>";
?>
      </p>
      <?php if(isset($q)) {?>
      <a href="javascript:change('error')" class="bodylink">&nbsp;&nbsp;&nbsp;Просмотр 
      ошибок</a> 
      <DIV id=error style="DISPLAY: none"> 
        <p class="body"><em>&nbsp;&nbsp;Допущенные ошибки </em> <hr></p>
        <?php
	
	// Цикл по ошибочным вопросам
	foreach( $q as $id_q => $row_wq)	{
		echo "<p class=rowtxt>";
		echo $row_wq['que_text']."<br>";
		$query = "SELECT * FROM stat_wrans WHERE id_ques=".$id_q." AND id_stat_th=".$_SESSION['stat_th']." ORDER BY qorder";
		$wan = mysql_query($query);		
		
		switch(	$row_wq['qtype'] )	{
			case 'single':			
				$row_wan = mysql_fetch_assoc($wan);
				
				$query = "SELECT ans_text  FROM answer WHERE id_ans=".$row_wan['id_ans'];
				$wan_txt = mysql_query($query);
				$row_wan_txt = mysql_fetch_assoc($wan_txt);
				
				echo "<b>Дан ответ:</b> <font color=#CC3300>".$row_wan_txt['ans_text']."</font><br>"; 
				
				$query = "SELECT ans_text  FROM answer WHERE id_ques=".$id_q." AND correct='yes'";
				$an_txt = mysql_query($query);
				$row_an_txt = mysql_fetch_assoc($an_txt);
				echo "<b>Правильный ответ:</b> " . $row_an_txt['ans_text']."<br>";			
				break;
			
			case  'many': 
				$query = "SELECT ans_text  FROM answer WHERE id_ques=".$id_q;
				$wan_txt = mysql_query($query);
				
				echo "<b>Дан ответ:</b> <font color=#CC3300>";
				while( $row_wan = mysql_fetch_assoc($wan) )	{
					$query = "SELECT ans_text FROM answer WHERE id_ans=".$row_wan['id_ans'];
					$wan_txt = mysql_query($query);
					$row_wan_txt = mysql_fetch_assoc($wan_txt);
					echo $row_wan_txt['ans_text']."; ";
				}
				echo "</font>";
				echo "<br>";
				$query = "SELECT ans_text  FROM answer WHERE id_ques=".$id_q." AND correct='yes'";
				$an_txt = mysql_query($query);
				
				echo "<b>Правильный ответ:</b>"; 
				while($row_an_txt = mysql_fetch_assoc($an_txt)) 	{
					echo $row_an_txt['ans_text']."; ";
				}
				echo "<br>";
				break;
			
			case 'order': 
				
				echo "<b>Дан ответ:</b> <font color=#CC3300>";
				while( $row_wan = mysql_fetch_assoc($wan) )	{
					$query = "SELECT ans_text FROM answer WHERE id_ans=".$row_wan['id_ans'];
					$wan_txt = mysql_query($query);
					$row_wan_txt = mysql_fetch_assoc($wan_txt);
					echo $row_wan['qorder']. ". " . $row_wan_txt['ans_text']."; ";
				}
				echo "</font>";
				echo "<br>";
				$query = "SELECT ans_text, qorder FROM answer WHERE id_ques=".$id_q." ORDER BY qorder";
				$an_txt = mysql_query($query);
				
				echo "<b>Правильный ответ:</b> "; 
				while($row_an_txt = mysql_fetch_assoc($an_txt)) 	{
					echo $row_an_txt['qorder'].". ". $row_an_txt['ans_text']."; ";
				}	
				break;
			
			case  'correspond': 	
				echo "<b>Дан ответ:</b><br><font color=#CC3300>";				
				$query = "SELECT * FROM answer WHERE id_ques=".$id_q;
				$ans = mysql_query($query);
				while ($row_ans = mysql_fetch_assoc($ans) ) {
					echo "&nbsp;&nbsp;".$row_ans['ans_text'].": ";
					$query = "SELECT * FROM stat_wrans WHERE id_ans=".$row_ans['id_ans'] . " AND id_stat_th=".$_SESSION['stat_th'];
					$corr = mysql_query($query);
					while( $row_corr = mysql_fetch_assoc($corr) ) {
						$query = "SELECT ans_corr_text FROM answer_corr WHERE id_ans_corr=".$row_corr['id_ans_corr'];
						$corr_txt = mysql_query($query);
						$row_corr_txt = mysql_fetch_assoc($corr_txt);
						echo "&nbsp;&nbsp;".$row_corr_txt['ans_corr_text']." ";
					}
					echo "<br>";
				}
				echo "</font>";
		
				echo "<b>Правильный ответ:</b><br>";				
				$query = "SELECT id_ans FROM answer WHERE id_ques=".$id_q;
				$ans = mysql_query($query);
				while ($row_ans = mysql_fetch_assoc($ans) ) {
					$query = "SELECT * FROM answer WHERE id_ans=".$row_ans['id_ans'];
					$ans_txt = mysql_query($query);
					$row_ans_txt = mysql_fetch_assoc($ans_txt);
					echo "&nbsp;&nbsp;".$row_ans_txt['ans_text'].": ";
					
					$query = "SELECT id_ans_corr FROM correspond WHERE id_ans=".$row_ans['id_ans'];
					$corr = mysql_query($query);
					while( $row_corr = mysql_fetch_assoc($corr) ) {
						$query = "SELECT ans_corr_text FROM answer_corr WHERE id_ans_corr=".$row_corr['id_ans_corr'];
						$corr_txt = mysql_query($query);
						$row_corr_txt = mysql_fetch_assoc($corr_txt);
						echo "&nbsp;&nbsp;".$row_corr_txt['ans_corr_text']." ";
					}
					echo "<br>";
				}
				
				break;
			
			case 'enter': 
				echo "<b>Дан ответ:</b> <font color=#CC3300>";
				$row_wan = mysql_fetch_assoc($wan);
				echo $row_wan['wr_ans_text']."<br>";
				echo "</font>";
				echo "<b>Правильный ответ:</b> ";
				$query = "SELECT ans_text FROM answer WHERE id_ques=".$id_q;
				$an_txt = mysql_query($query);
				for($i = 1, $N = mysql_num_rows($an_txt); $row_an_txt = mysql_fetch_assoc($an_txt); $i++)
					echo ($i < $N) ? $row_an_txt['ans_text']." или " : $row_an_txt['ans_text'];
				break;
		}
		echo "<hr><br>";
		echo "</p>";
	}
?></p>
        </DIV>
      <?php } 
else echo "<p>Ошибок нет</p>";?>
      <?php }
else echo "<p>Статистика не доступна. Пожалуйста, <a href='regform.php'>зарегистрируйтесь</a>.</p>";
?>
      <p>&nbsp;</p></td>
  </tr>
  <tr> 
    <td  valign="bottom"> 
      <?php include "utils/bottom.php";?>
    </td>
  </tr>
</table>
</body>
</html>
<?php 
unset($_SESSION['num']);
//unset($_SESSION['thm']);
unset($_SESSION['single']);
//unset($_SESSION['passed']);
if(isset($_SESSION['anstxt'])) unset($_SESSION['anstxt']);

	if($_SESSION['Username'] == 'guest' and isset($_SESSION['stat_th']) ) {
		$query = "DELETE FROM stat_wrans WHERE id_stat_th=".$_SESSION['stat_th'];
		mysql_query($query, $trainer) or die(mysql_error());
		$query = "DELETE FROM stat_theme WHERE id_stat_th=".$_SESSION['stat_th'];
		mysql_query($query, $trainer);
	}
	//unset($_SESSION['stat_th']); 
?>
