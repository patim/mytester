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
	if( isset($_GET['id_user']) )	{
		$query = "SELECT * FROM user WHERE id_user=".$_GET['id_user'];
		$user = mysql_query($query);
		$row_user = mysql_fetch_assoc($user);
		
		$query = "SELECT * FROM stat_theme WHERE id_user=".$_GET['id_user']." ORDER BY date DESC";
		$stat_theme = mysql_query($query);		
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
    <td height="152" colspan="2" valign="top"> 
      <?php include "adminutils/topadmin.php";?>
    </td>
  </tr>
  <tr> 
    <td width="15" rowspan="5" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="745" valign="top"> <p><a href="users.php" class="bodylink">Назад 
        на список пользователей</a></p>
      <p class="body">Статистика пользователя <strong><?php echo $row_user['name'];?></strong></p></td>
  </tr>
  <tr> 
    <td height="32" valign="middle"> <p>
	<?php  echo mysql_num_rows($stat_theme)  ? "<a href='clearstat.php?id_user=".$_GET['id_user']."' class=rowtxt> очистить 
        статистику</a>" :"&nbsp;";?></p></td>
  </tr>
  <tr> 
    <td height="3" valign="top"> 
	 <table  border=1 align="left" cellpadding=5 cellspacing=0 bordercolorlight=gray bordercolordark=white class=bodytable>
        <tr valign="middle" class="zagtable"> 
          <td width="324" height="22" class="tableheadercat" >тема</td>
          <td width="140" align="center" class="tableheadercat" >дата</td>
          <td width="35" align="center" class="tableheadercat" >балы</td>
          <td width="93" align="center" class="tableheadercat" >задано/всего</td>
          <td width="91" align="center" class="tableheadercat" >ошибки</td>
        </tr>
        <?php 
  	if(mysql_num_rows($stat_theme))		
		while($row_stat_theme= mysql_fetch_assoc($stat_theme)) {
			$query = "SELECT * FROM theme WHERE id_theme=".$row_stat_theme['id_theme'];
			$theme = mysql_query($query);
			
			$query = "SELECT * FROM question WHERE id_theme=".$row_stat_theme['id_theme'] . " AND showhide='show'";
			$question = mysql_query($query);
			
			$query = "SELECT * FROM stat_wrans WHERE id_stat_th=".$row_stat_theme['id_stat_th'];
			$wrans = mysql_query($query);
						
			$row_theme = mysql_fetch_assoc($theme);		
			echo "<tr valign=top align=center>
    					 <td align=left class=rowtxt>&nbsp;". $row_theme['name']."</td>
           				 <td align=left class=rowtxt>".$row_stat_theme['date']."</td>
   					 	 <td class=rowtxt>". $row_stat_theme['mark']."</td>
    				 	 <td class=rowtxt>".$row_stat_theme['giv_qu']."/".mysql_num_rows($question)."</td>
						 <td class=rowtxt>".( mysql_num_rows($wrans) ? "<a href='statwrans.php?id_stat_th=".$row_stat_theme['id_stat_th']."' class=rowtxt>перейти</a>->" : "нет ошибок")."</td>
  					 </tr>";			
		}
	else
		echo "<tr align=center valign=middle><td colspan=5 class=rowtxt>Нет пройденных тем</td></tr>" 	
  ?>
     </table>
	</td>
  </tr>
  <tr> 
<td height="32" valign="middle"> <p><?php  echo mysql_num_rows($stat_theme)  ? "<a href='clearstat.php?id_user=".$_GET['id_user']."' class=rowtxt> очистить 
        статистику</a>" :"&nbsp;";?></p></td>
  </tr>
  <tr> 
    <td height="14" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr valign="bottom"> 
    <td  colspan="2"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
