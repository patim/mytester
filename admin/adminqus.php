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

	if( isset($_GET['id_theme']) ) {
		$query = "SELECT * FROM theme WHERE id_theme=".$_GET['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);
		
		$query = "SELECT * FROM question WHERE id_theme=". $_GET['id_theme']. " ORDER BY id_ques DESC";
		$q = mysql_query($query, $trainer);
		$row_q = mysql_fetch_assoc($q);		
	}			
?>
<html>
<head>
<title>Администрирование. Вопросы</title>
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
</script>
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
    <td width="15" rowspan="4" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="745" height="59" valign="top"> <p><a href="admin_thm.php" class="bodylink">На 
        список тем</a></p>
      <p><span class=header>Вопросы темы: </span><span class="body"><strong><br>
        &nbsp;<?php echo $row_theme['name'];?></strong></span> </p>
	  <p class="link2"><a href="main.php" class="link2">администрирование</a> \  
<a href="admin_thm.php" class="link2">темы теста</a> \ вопросы теста</p> 
      <br> 
	  </td>
  </tr>
  <tr> 
    <td height="109" valign="top"><p class="body">Типы вопросов:<br>
        <strong>1</strong> - единственно верный ответ; <strong>2</strong> - могут 
        быть несколько верных; <strong>3</strong> - задать последовательность 
        ответов; <strong>4</strong> - задать соответствие ответов; <strong>5</strong> 
        - ввести ответ с клавиатуры.</p>
      <p><a href="<?php echo "addqform.php?id_theme=".$_GET['id_theme'];?>" class="rowtxt">Добавить 
        вопрос</a></p></td>
  </tr>
  <tr> 
    <td height="36"> <table border=1 align="left" cellpadding=5 cellspacing=0 bordercolorlight=gray bordercolordark=white class=bodytable >
        <!--DWLayoutTable-->
        <tr align="center" valign="middle" class="zagtable"> 
          <td width="369" height="34" align="left" class="tableheadercat">формулировка 
            вопроса</td>
          <td width="56" align="center" class="tableheadercat">ответы</td>
          <td width="27" class="tableheadercat">тип</td>
          <td width="28" class="tableheadercat">бал</td>
          <td width="87" valign="middle" class="tableheadercat">мультимедиа</td>
          <td width="104" valign="middle" class="tableheadercat">действие</td>
        </tr>
        <?php 
  	if(mysql_num_rows($q)) {
		do  {

			if( $row_q['picture'] or $row_q['sound'] ) {
				$img ='';
				if($row_q['picture'])
					$img = "<img src='../img/pict.jpg' alt='изображение' width=14 height=14>";
				if($row_q['sound'] )
					$img = ( ($img != '') ? $img."&nbsp;&nbsp;" : $img)."<img src='../img/sound.gif' alt='звук' width=14 height=14>";
			}
			else 
				$img = "&nbsp;";
			
			switch($row_q['qtype']) {
				case 'single': $type = 1;
				break;			
				case  'many': $type = 2;
				break;								
				case 'order':	$type = 3; 					
				break;						
				case  'correspond': $type = 4;
				break;						
				case 'enter': $type = 5;		
				break;
			}
			
			$query = "SELECT * FROM answer WHERE id_ques=".$row_q['id_ques']." ORDER BY qorder ASC";
			$ans = mysql_query($query,  $trainer) or die(mysql_error());
			$num_ans = mysql_num_rows($ans);
			
			if($type == 4) {
				$query = "SELECT * FROM answer_corr WHERE id_ques=".$row_q['id_ques'];
				$ans_corr = mysql_query($query,  $trainer) or die(mysql_error());
				$num_ans_corr = mysql_num_rows($ans_corr);
				$nac_txt = "/".$num_ans_corr;
			
				while($row_ans_corr = mysql_fetch_assoc($ans_corr)) 
					$corr[$row_ans_corr['id_ans_corr']] =  $row_ans_corr['ans_corr_text'];
			}		
			else  {
				$num_ans_corr = 0;
				$nac_txt = "";
			}	
			$onclick = ( !$num_ans and !$num_ans_corr) ? 
				"onclick=\"if(confirm('Вы действительно желаете удалить данный вопрос?')) return true; else return false;\" LANGUAGE=Javascript" : 
				"onclick=\"alert('Данный вопрос нельзя удалить, потому что он содержит ответы. Удалите сначала их.');  return false;\" LANGUAGE=Javascript";
			
			if( isset($_GET['id_ques']) and $_GET['id_ques'] == $row_q['id_ques'] ) $display = "";
			else  $display = "style=\"DISPLAY: none\"";			
			$ct = "<img src='../img/tick.gif' width=12 height=12 alt='верный'>";
			
			$edit =  (($type == 4 || $type == 3) ? "<a href='editans_corr.php?id_ques=".$row_q['id_ques']."'><img src=../img/editsmall.gif border=0 alt='редактировать ответ' align=absmiddle></a>" : "");
			$editadd =  (($type == 4 || $type == 3) ? "<a href='editans_corr.php?id_ques=".$row_q['id_ques']."'><img src=../img/add.gif border=0 alt='добавить ответ' align=absmiddle></a>" : "");		
			$add = ( ($type==1 || $type==2 || $type==5) ? "<a href='addans.php?id_ques=".$row_q['id_ques']."'><img src=../img/add.gif border=0 alt='добавить ответ' align=absmiddle></a>" : "");
			
			echo 	"<tr align=center valign=middle>";
			echo	"<td height=34 align=left class=rowtxt>".$row_q['que_text']."<br><br>";
			
			if($num_ans) {				
				echo	"<a href=\"javascript: change('ques[".$row_q['id_ques']."]')\" class=rowtxt><img src=../img/down.gif border=0 align=absmiddle></a>&nbsp;<a href=\"javascript: change('ques[".$row_q['id_ques']."]')\" class=rowtxt>Ответы</a>&nbsp;&nbsp;&nbsp;".$edit."".$add."
								<div id=ques[".$row_q['id_ques']."] ".$display.">";
				while($row_ans = mysql_fetch_assoc($ans)) {
					if($type == 4) {
						echo "&nbsp;<a href=\"javascript: change('ans[".$row_ans['id_ans']."]')\" class=rowtxt>".$row_ans['ans_text']."</a>";
						echo "<div id=ans[".$row_ans['id_ans']."] style=\"DISPLAY: none\">";
						foreach($corr as $id_corr => $txt) {
							$query = "SELECT * FROM correspond WHERE id_ans=".$row_ans['id_ans']." AND id_ans_corr=".$id_corr;
							$c = mysql_query($query);
							$n = mysql_num_rows($c);						
							echo "&nbsp;".($n ? $ct : "&nbsp;&nbsp;&nbsp;")."&nbsp;".$txt."<br>";						
						}
						echo "</div><br>";
					}
					else 
						if(!$row_ans['qorder']) 
							echo (($row_ans['correct'] == 'yes') ? $ct : "&nbsp;&nbsp;&nbsp;")."&nbsp;".$row_ans['ans_text'].
									"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='delans.php?id_ques=".$row_q['id_ques']."&id_ans=".$row_ans['id_ans']."'><img src='../img/delsmall.gif'  border=0 alt='удалить ответ' align=absmiddle></a>
									&nbsp;&nbsp;&nbsp;&nbsp;<a href='editans.php?id_ques=".$row_q['id_ques']."&id_ans=".$row_ans['id_ans']."'><img src='../img/editsmall.gif' border=0 alt='редактировать ответ' align=absmiddle></a><br>";
						else 	
							echo $row_ans['qorder'].".&nbsp;".$row_ans['ans_text']."<br>";	
				}
				echo "</div>";
			}
			else 
				echo ($type == 4 || $type == 3) ?$editadd :$add;
					
			echo   "<br></td>";
			echo   "<td class=rowtxt>".$num_ans."".$nac_txt."</td>";
			echo   "<td class=rowtxt>".$type."</td>";
     		echo 	"<td class=rowtxt>".$row_q['mark']."</td>";
    		echo 	"<td class=rowtxt>".$img."</td>";
    		echo 	"<td class=rowtxt><a href='editqform.php?id_ques=".$row_q['id_ques']."'><img src='../img/edit.gif' alt='редактировать' border=0 align=absmiddle></a>&nbsp;&nbsp;&nbsp;
								<a ".$onclick." href='delques.php?id_ques=".$row_q['id_ques']."'><img src='../img/del.gif' alt='удалить' border=0></a><br><br>
								<a href='showhide.php?id_ques_sh=".$row_q['id_ques']."&id_theme=".$_GET['id_theme']."' class=rowtxt>".( ($row_q['showhide'] == 'show') ? "скрыть": "показать")."</a>
						</td>";
			echo 	"</tr>";
		}
		while($row_q = mysql_fetch_assoc($q));
	}
	else echo "<tr align=center valign=middle class=rowtxt><td colspan=6>Вопросы не добавлены</td></tr>";
  ?>
      </table></td>
  </tr>
  <tr> 
    <td height="98" valign="top"> <p><a href="<?php echo "addqform.php?id_theme=".$_GET['id_theme'];?>" class="rowtxt"><br>
        Добавить вопрос</a></p>
      <p><a href="admin_thm.php" class="bodylink">На список тем</a></p>
      <p>&nbsp;</p>	
	</td>
  </tr>
  <tr valign="bottom"> 
    <td height="18" colspan="2"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>

