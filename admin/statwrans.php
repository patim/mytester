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
	
	if( isset($_GET['id_stat_th']) )	{
		$query = "SELECT * FROM stat_theme WHERE id_stat_th=".$_GET['id_stat_th'];
		$stat_theme = mysql_query($query);
		$row_stat_theme = mysql_fetch_assoc($stat_theme);
		 
		$query = "SELECT name FROM user WHERE id_user=".$row_stat_theme['id_user'];
		$user = mysql_query($query);
		$row_user= mysql_fetch_assoc($user);
		 	
	}
?>
<html>
<head>
<title>Администрирование. Управление пользователями</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>

<body>

<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="33" colspan="2" valign="top"><?php include "adminutils/topadmin.php";?></td>
  </tr>
  <tr> 
    <td width="15" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="745" valign="top">
<p><a href="stat.php<?php echo "?id_user=".$row_stat_theme['id_user'];?>" class="bodylink">Назад</a></p>
      <p class="body">Статистика пользователя <strong><?php echo $row_user['name'];?></strong></p>
<?php 
if( isset($row_stat_theme['id_theme']) ) {
	$theme = mysql_query( "SELECT name FROM theme WHERE id_theme=".$row_stat_theme['id_theme'] );
	$row_thm = mysql_fetch_assoc($theme);
	echo "<p class=body>Тема: <b>".$row_thm['name']."</b></p>";

	$query = "SELECT * FROM question WHERE id_theme=". $row_stat_theme['id_theme']." AND showhide='show'";
	$question = mysql_query($query,  $trainer) or die(mysql_error());
	$num_ques = mysql_num_rows($question);
	
	$query = "SELECT giv_qu FROM stat_theme WHERE id_stat_th =". $_GET['id_stat_th'];
	$giv_qu = mysql_query($query);
	$row_giv_qu = mysql_fetch_assoc($giv_qu);
}
	
	// Подсчёт вопрсов с ошибочными ответатми
	$query = "SELECT * FROM stat_wrans WHERE id_stat_th =".$_GET['id_stat_th'] ." ORDER BY num";
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

if(isset($q)) {	
	echo "<p class=body><em>&nbsp;&nbsp;Допущенные ошибки</em><hr></p>";
	// Цикл по ошибочным вопросам
	foreach( $q as $id_q => $row_wq)	{
		echo "<p class=rowtxt>";
		echo $row_wq['que_text']."<br>";
		$query = "SELECT * FROM stat_wrans WHERE id_ques=".$id_q." AND id_stat_th=".$_GET['id_stat_th']." ORDER BY qorder";
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
				
				echo "<b>Правильный ответ:</b> <font color=#CC3300>"; 
				while($row_an_txt = mysql_fetch_assoc($an_txt)) 	{
					echo $row_an_txt['ans_text']."; ";
				}
				echo "</font>";
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
					$query = "SELECT * FROM stat_wrans WHERE id_ans=".$row_ans['id_ans'] . " AND id_stat_th=".$_GET['id_stat_th'];
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
} 
else echo "<p class=rowtxt>Ошибок нет</p>";?>
</p>
<p>&nbsp; </p>	
	</td>
  </tr>
  <tr valign="bottom"> 
    <td  colspan="2"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
