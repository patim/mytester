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
	$query = "SELECT * FROM user WHERE name != 'guest' ORDER BY rights ASC";
	$user = mysql_query($query);
				
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
    <td width="15" rowspan="4" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="745" valign="top"> <p class="header">Пользователи </p>
      <p><a href="main.php" class="bodylink">Назад</a></p>
      <?php 
$query = "SELECT * FROM user WHERE name='guest'";
$guest = mysql_query($query);
echo  (mysql_num_rows($guest) ? "<p class=body>Запись гостя включена &nbsp;&nbsp;<span class=rowtxt><<<a href='guestswitch.php?off=1' class=rowtxt>отключить</a></span></p>": "<p class=body>Запись гостя отключена &nbsp;&nbsp;<span class=rowtxt><<<a href='guestswitch.php?on=1' class=rowtxt>включить</a></span></p>");

?>
      <p><a href="adduserform.php" class="rowtxt">Добавить нового пользователя</a><br>
      </p></td>
  </tr>
  <tr>
    <td height="20" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top"> <table  border=1 align="left" cellpadding=5 cellspacing=0 bordercolorlight=gray bordercolordark=white class=bodytable>
        <!--DWLayoutTable-->
        <tr align="center" valign="middle" class="zagtable" > 
          <td width="190" height="22" align="left" class="tableheadercat" >имя</td>
          <td width="169" class="tableheadercat" >e-mail</td>
          <td width="78" class="tableheadercat" >статус</td>
          <td width="146" class="tableheadercat" >дата регистрации</td>
          <td width="100" class="tableheadercat" >действие</td>
        </tr>
        <?php 
	if(mysql_num_rows($user))
		while ( $row_user = mysql_fetch_assoc($user) ) {
			echo "<tr valign=top align=center>
    					 <td align=left class=rowtxt>&nbsp;".( ($row_user['rights'] == 'admin') ? "<b>".$row_user['name']."</b>": $row_user['name'])."<br>".( (($row_user['rights'] != 'admin') ) ? "<div align=right><<<<a href='stat.php?id_user=".$row_user['id_user']."' class=rowtxt>статистика</a></div>": "")."</td>
           				 <td align=left class=rowtxt valign=center>".( ($row_user['rights'] == 'admin') ? "".$row_user['email']."</b>": $row_user['email'])."</td>
   					 	 <td class=rowtxt valign=center>".( ($row_user['rights'] == 'admin') ? "".$row_user['rights']."</b>": $row_user['rights'])."</td>
    				 	 <td class=rowtxt valign=center>".( ($row_user['rights'] == 'admin') ? "".$row_user['regdate']."</b>": $row_user['regdate'])."</td>
						 <td class=rowtxt valign=center>".( ($row_user['rights'] == 'admin') ? "<a href='edituserform.php?id_user=".$row_user['id_user']."'><img src='../img/edit.gif' border=0 alt='редактировать' align=absmiddle></a></b>" : 
						 "<a href='edituserform.php?id_user=".$row_user['id_user']."'><img src='../img/edit.gif' border=0 alt='редактировать' align=absmiddle></a>&nbsp;&nbsp;&nbsp;<a href='deluser.php?id_user=".$row_user['id_user'].
						 "' onClick=\"if(confirm('Вы действительно желаете удалить данного пользователя?')) return true; else return false;\" LANGUAGE=\"Javascript\"><img src='../img/del.gif' border=0 alt='удалить' align=absmiddle></a>" )."</td>
  					 </tr>";
  		}
	else
		echo "<tr align=center valign=middle><td colspan=5 class=rowtxt>Нет пройденных тем</td></tr>" 
?>
      </table></td>
  </tr>
  <tr> 
    <td valign="top"><p><a href="adduserform.php" class="rowtxt"><br>
        Добавить нового пользователя </a></p>
      <p>&nbsp; </p></td>
  </tr>
  <tr valign="bottom"> 
    <td  colspan="2"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
