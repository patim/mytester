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
?>
<html>
<head>
<title>Администрирование</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr valign="top"> 
    <td height="152" colspan="4"> 
      <?php include "adminutils/topadmin.php";?>
      <p> </p></td>
  </tr>
  <tr> 
    <td width="13" height="67" ></td>
    <td colspan="3" valign="top" > <p class="body">Здравствуйте администратор!</p></td>
  </tr>
  <tr> 
    <td height="74" ></td>
    <td width="188" >&nbsp;</td>
    <td width="364" align="center" valign="top" > <table width="100%" border="0" cellpadding="0" cellspacing="1">
        <!--DWLayoutTable-->
        <tr valign="middle"> 
          <td height="36" class="boxmenu"><a href="admin_thm.php"><img src="../img/key.gif" alt="Конструктор" width="52" height="32" border="0" align="absmiddle"></a>&nbsp;&nbsp; 
            <a href="admin_thm.php"  class="topmenuadmin">Управление тестом</a> 
          </td>
        </tr>
        <tr> 
          <td height="56" valign="top" class="boxmenu"><a href="users.php"><img src="../img/users.gif" alt="Пользователи" width="39" height="32" border="0" align="absmiddle"></a> 
            &nbsp; &nbsp;&nbsp;&nbsp;<a href="users.php" class="topmenuadmin">Управление 
            пользователями</a> </td>
        </tr>
      </table></td>
    <td width="195" >&nbsp;</td>
  </tr>
  <tr>
    <td height="28" ></td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr valign="bottom"> 
    <td  colspan="4"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
