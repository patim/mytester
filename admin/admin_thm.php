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

	$query = "SELECT * FROM theme";
	$theme = mysql_query($query,  $trainer) or die(mysql_error());
	$row_theme = mysql_fetch_assoc($theme);
			
?>
<html>
<head>
<title>Администрирование тем</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>
<body>

<table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="18" colspan="2" valign="top"> 
      <?php include "adminutils/topadmin.php";?>
    </td>
  </tr>
  <tr> 
    <td width="15" rowspan="4" valign="top"><!--DWLayoutEmptyCell-->&nbsp; </td>
    <td width="745" valign="top"> <p class="header">Темы теста</p>
      <p class="link2"><a href="main.php" class="link2">администрирование</a> \ 
        темы теста</p>
      <p><a href="main.php" class="bodylink">Назад</a></p>
      <p class="body">Уважаемый(ая) администратор, перед изменением набора вопросов 
        в теме убедитесь, что тема скрыта от пользователя. Это поможет уберечь 
        последнего от некоторых сюрпризов ;)</p></td>
  </tr>
  <tr> 
    <td height="58" valign="bottom" class="instr"> <p align="justify"><font size="2">Внимание! 
        Новая добавленная тема по-умолчанию не доступна для пользователя. <br>
        Чтобы сделать её доступной используйте поле отображение.</font> </p></td>
  </tr>
  <tr> 
    <td height="39"> 
      <table  border=1 align="left" cellpadding=5 cellspacing=0 bordercolorlight=gray bordercolordark=white class=bodytable>
        <tr valign="middle" class="zagtable"> 
          <td width="354" height="38" class="tableheadercat">название темы</td>
          <td width="70" class="tableheadercat">вопросы/ скрытые </td>
          <td width="98" align="center" class="tableheadercat">дата публикации</td>
          <td width="89" align="center" class="tableheadercat">отображение</td>
          <td width="72" align="center" class="tableheadercat">действие</td>
        </tr>
        <?php 
  	if(mysql_num_rows($theme)) {
		do  {
			$query = "SELECT * FROM question WHERE id_theme=". $row_theme['id_theme'];
			$question = mysql_query($query,  $trainer) or die(mysql_error());
			$num_ques = mysql_num_rows($question);
		
			$query = "SELECT * FROM question WHERE id_theme=". $row_theme['id_theme']. " AND showhide='hide'";
			$hide_q = mysql_query($query,  $trainer) or die(mysql_error());
			$num_hide_q = mysql_num_rows($hide_q);
			
			$onclick = ( !$num_ques) ? 
				"onclick=\"if(confirm('Вы действительно желаете удалить данную тему?')) return true; else return false;\" LANGUAGE=Javascript" : 
				"onclick=\"alert('Данный тему удалить нельзя, потому что она содержит вопросы. Удалите сначала их.');  return false;\" LANGUAGE=Javascript";
		
			echo "  <tr align=center valign=middle>";
			echo "<td height=32 class=rowtxt align=left><a href='adminqus.php?id_theme=".$row_theme['id_theme']."' class=rowtxt>".$row_theme['name']."</td>\n";
			echo   "<td class=rowtxt>".$num_ques."/".$num_hide_q."</td>";
     		echo 	"<td class=rowtxt>".$row_theme['date']."</td>";
    		echo 	"<td ><a href='showhide.php?id_theme_sh=".$row_theme['id_theme']."' class=rowtxt>".( ($row_theme['showhide'] == 'show') ? "скрыть": "показать")."</a></td>";

			echo 	"<td class=rowtxt><a href='editthmform.php?id_theme=".$row_theme['id_theme']."&qs=".$num_ques."'><img src=../img/edit.gif border=0 alt='редактировать' align=absmiddle></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='delform.php?id_theme=".$row_theme['id_theme']."' ".$onclick."><img src=../img/del.gif  border=0 alt='удалить'></a></td>";

			echo "</tr>";
		}
		while($row_theme = mysql_fetch_assoc($theme));
	}
	else	echo "<tr align=center valign=middle><td colspan=5 class=rowtxt>Темы не добавлены</td></tr>" 
  ?>
      </table>	
	</td>
  </tr>
  <tr> 
    <td height="64" valign="top"> <form action="addthm.php" method="post" name="add_th" id="add_th">
        <p class="body">&nbsp;</p>
        <p class="body">Добавить новую тему:<br>
  		<?php if( isset($_SESSION['no_theme'] )) { 
						unset($_SESSION['no_theme']);
						echo "<span class=body><font color=#FF0000 size=2>Название темы не может быть пустым!</font></span><br>";
					}
					else echo "<br>";
					?>	
          <input name="thm_name" type="text" class="editbt2" id="thm_name" size="60">
          &nbsp;&nbsp;&nbsp; 
          <input name="add_th" type="submit" class="button2" id="add_th" value="Добавить">
        </p>
      </form></td>
  </tr>
  <tr> 
    <td height="18" colspan="2"  valign="bottom"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>

