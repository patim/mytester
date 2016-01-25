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
    <td width="462"  align="left" valign="top" > <p class="body"><strong>ГЛАВНАЯ</strong></p>
      <p class="body"><br>
        Добро пожаловать на страницу on-line тестирования!</p>
      <?php
		$query = "SELECT * FROM user WHERE name='guest' AND password='guest'";
		$guest = mysql_query($query);
		echo ( mysql_num_rows($guest) ?"<p class=body>Используйте имя <strong>guest</strong>, пароль <strong>guest</strong> для гостевого входа. 
			Или пройдите процедуру <a  href='reg.php' class=bodylink>регистрации</a> чтобы иметь доступ к статистике.</p>
		" : "<p class=body>Для прохождения теста необходимо <a  href='reg.php' class=bodylink>зарегистрироваться</a></p>");

?>
      <p class="body">Для прохождения теста необходим браузер Microsoft Internet 
        Explorer или MYIE2. В других браузерах корректное прохождение теста не 
        гарантируется</p>
      <p class="body">При прохождение теста не рекомендуется использовать кнопки 
        перехода браузера (назад, вперед). Используйте вместо этого навигацию 
        самой страницы.</p>
      <p class="body">Желаем удачи!</p>
      <p class="author" >&nbsp;</p></td>
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
