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

	if( isset($id_u_row['id_user']) )	{
		$query = "SELECT * FROM stat_theme WHERE id_user=".$id_u_row['id_user']." ORDER BY date DESC";
		$stat_theme = mysql_query($query);		
	}			
?>
<html>
<head>
<title>Администрирование. Управление пользователями</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td width="760" height="153" valign="top" >
<?php include "utils/top.php";?>
	</td>
  </tr>
  <tr> 
    <td valign="top" > <p><a href="index.php" class="bodylink">На 
        главную</a></p>
      <p class="body">Статистика пользователя <strong><?php echo $_SESSION['Username'];?></strong></p>
      <table width="760"    border=1 align="center" cellpadding=5 cellspacing=0 bordercolorlight=gray bordercolordark=white class=bodytable >
        <!--DWLayoutTable-->
        <tr align="center" valign="middle" class="zagtable"> 
          <td width="327" height="22" align="left" class="tableheadercat" >&nbsp;тема</td>
          <td width="158" class="tableheadercat" >дата</td>
          <td width="39" class="tableheadercat" >балы</td>
          <td width="101" class="tableheadercat" >задано/всего</td>
          <td width="99" class="tableheadercat" >ошибки</td>
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
						 <td class=rowtxt>".( mysql_num_rows($wrans) ? "<a href='wrans.php?id_stat_th=".$row_stat_theme['id_stat_th']."' class=rowtxt>перейти</a >->" : "нет ошибок")."</td>
  					 </tr>";			
		}
	else
		echo "<tr align=center valign=middle><td colspan=5>Нет пройденных тем</td></tr>"
  	
  ?>
      </table>
      <p>&nbsp; </p></td>
  </tr>
  <tr> 
    <td valign="bottom" > 
      <?php include "utils/bottom.php";?>
    </td>
  </tr>
</table>
</body>
</html>
