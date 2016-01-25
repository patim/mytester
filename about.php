<?php 
session_start(); 
require_once('Connections/trainer.php');

$auth = $_SERVER[ 'PHP_SELF' ];
if( isset($_POST['Submit']) ) {
	$name = $_POST['login'];
	$password =$_POST['pass'];
	$redirectLoginSuccess = $auth;
	$redirectLoginFailed =  $auth;

	$query = "SELECT * FROM user WHERE name='".$name."' AND password='".$password."'";
	$login =  mysql_query($query,  $trainer) or die(mysql_error());
	$loginFoundUser = mysql_num_rows($login);
	if ($loginFoundUser) {
		$row_login = mysql_fetch_assoc($login);
		$_SESSION['Userrights'] = $row_login['rights'];
		$_SESSION['Username'] = $name;
		unset($_SESSION['attempt']);
	  	echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL= ". $redirectLoginSuccess."'></HEAD></HTML>";
		exit;
	}
	else {  
		$_SESSION['attempt'] = 1;
		echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL= ".$redirectLoginFailed."'></HEAD></HTML>";
		exit;
	}
}
?>
<html>
<head>
<title>Главная</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="758" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="153" colspan="3" valign="top"> 
      <?php include "utils/top.php";?>
    </td>
  </tr>
  <tr> 
    <td width="281" valign="top"> 
       <?php include "utils/auth.php";?>     
    </td>
    <td width="15" valign="top" ><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="462"  align="left" valign="top" > <p class="body"><strong>О ПРОЕКТЕ</strong></p>
      <p class="body"><br>
        Данный проект создан в рамках магистерской диссертации по направлению 
        <font size="2" face="Georgia, Times New Roman, Times, serif"><strong>510422 
        Информационные процессы и системы</strong></font> на Физическом факультете 
        (Кафедра теоретической физики и волновых процессов). Его целью - дать 
        инструмент для самопровеки студентом(абитуриентом) своих знаний, оценки 
        уровня своей подготовки.</p>
      <p class="body">Руководитель проекта:<br>
        <strong>Хоперсков Александр Валентинович</strong><br>
        email: <a href="mailto:khoperskov@vlink.ru" class="body">khoperskov@vlink.ru</a></p>
      <p class="body">Консультант:<br>
        <strong>Феськов Сергей Владимирович<br>
        </strong>email: <a href="mailto:feskov@coltel.ru"  class="body">feskov@coltel.ru</a> 
      </p>
      <p class="body">Разработчик:<br>
        <strong>Залуцкий Максим Павлович<br>
        </strong>email: <a href="mailto:max-cpp@yandex.ru"  class="body">max-cpp@yandex.ru</a></p>
      <p class="body">&nbsp;</p></td>
  </tr>
  <tr valign="bottom"> 
    <td colspan="3"> 
      <?php include "utils/bottom.php";?>
    </td>
  <tr>
    <td></td>
    <td></td>
    <td><img src="img/spacer.gif" alt="" width="462" height="1"></td>
  </tr>
</table>
</body>
</html>
