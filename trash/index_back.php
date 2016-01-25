<?php 
session_start(); 
require_once('Connections/trainer.php');

$auth = $_SERVER[ 'PHP_SELF' ];
if( isset($_POST['Submit']) ) {
	$name = $_POST['login'];
	$password =$_POST['pass'];
	$redirectLoginSuccess = "index.php";
	$redirectLoginFailed =  "index.php";

	$query = "SELECT * FROM user WHERE name='".$name."' AND password='".$password."'";
	$login =  mysql_query($query,  $trainer) or die(mysql_error());
	$loginFoundUser = mysql_num_rows($login);
	if ($loginFoundUser) {
		$_SESSION['Username'] = $name;
		unset($_SESSION['attempt']);
	  	echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL= ". $redirectLoginSuccess."'></HEAD></HTML>";
	}
	else {  
		$_SESSION['attempt'] = 1;
		echo "<HTML><HEAD>	<META HTTP-EQUIV='Refresh' CONTENT='0; URL= ".$redirectLoginFailed."'></HEAD></HTML>";
	}
}
?>
<html>
<head>
<title>Главная</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="41" colspan="2" valign="top"> 
      <?php include "utils/top.php";?>
    </td>
  </tr>
  <tr> 
    <td width="206" height="205" valign="top"> <table border="0" cellpadding="0" cellspacing="0" width="169">
        <!--DWLayoutTable-->
        <tr> 
          <td width="26"><img src="img/spacer.gif" width="26" height="1" border="0" alt=""></td>
          <td><img src="img/spacer.gif" width="109" height="1" border="0" alt=""></td>
          <td><img src="img/spacer.gif" width="23" height="1" border="0" alt=""></td>
          <td><img src="img/spacer.gif" width="11" height="1" border="0" alt=""></td>
          <td><img src="img/spacer.gif" width="1" height="1" border="0" alt=""></td>
        </tr>
        <tr> 
          <td><img name="auth_r1_c1" src="img/auth_r1_c1.gif" width="26" height="26" border="0" alt=""></td>
          <td align="center" bgcolor="#2B2C91"><font color="#FFFFFF" size="1"><strong>авторизация</strong></font></td>
          <td colspan="2"><img name="auth_r1_c3" src="img/auth_r1_c3.gif" width="34" height="26" border="0" alt=""></td>
          <td><img src="img/spacer.gif" width="1" height="26" border="0" alt=""></td>
        </tr>
        <tr> 
          <td height="145" colspan="3" valign="top" bgcolor="#2B2C91">
<?php 
	if(!isset($_SESSION['Username'])) {
		if( isset($_SESSION['attempt']) )
			echo "Неправильное имя или пароль";
		echo "<p><form action=$auth method=post name=auth id=auth>
       				имя:<input name=login type=text id=login size=10><br>
					пароль:<input name=pass type=password id=pass size=10><br>
        			<input type=submit name=Submit value=Войти!>
      			</form></p>";
		$query = "SELECT * FROM user WHERE name='guest' AND password='guest'";
		$guest = mysql_query($query);
		echo (mysql_num_rows($guest) ?"Используйте логин <strong>guest</strong>, пароль <strong>guest</strong> для гостевого входа" : "&nbsp;");
		echo "      <p><a href='regform.php'>регистрация</a>&nbsp;&nbsp;&nbsp;<a href='forgetpass.php'>забыли пароль</a></p>";	
		}
		else  {
			if($_SESSION['Username'] != 'guest') {
				if( $_SESSION['Username'] == 'admin')	
					echo "<p><a href='admin/'>администрирование</a>&nbsp;&nbsp;<a href='exit.php'>выход</a></p>";
				else			
					echo  "<p><a href='statistics.php'>статистика</a>&nbsp;&nbsp;<a href='exit.php'>выход</a>&nbsp;&nbsp;<a href='themes.php'>темы теста</a>
								&nbsp;&nbsp;<a href='editform.php'>изменение настроек</a></p>";
			}
			else {
				echo "<p>Авторизация 
    					<form action=$auth method=post name=auth id=auth>
       					имя:<input name=login type=text id=login><br>
						пароль:<input name=pass type=password id=pass><br>
        				<input type=submit name=Submit value=Войти!>
      				</form></p>";				
				echo  "<p>Выполнен гостевой вход<br><a href='themes.php'>темы теста</a></p";
			}
		}
	  ?>		  
		  </td>
          <td background="img/auth_r2_c4.gif"><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td><img src="img/spacer.gif" width="1" height="145" border="0" alt=""></td>
        </tr>
        <tr> 
          <td><img name="auth_r3_c1" src="img/auth_r3_c1.gif" width="26" height="33" border="0" alt=""></td>
          <td><img name="auth_r3_c2" src="img/auth_r3_c2.gif" width="109" height="33" border="0" alt=""></td>
          <td colspan="2"><img name="auth_r3_c3" src="img/auth_r3_c3.gif" width="34" height="33" border="0" alt=""></td>
          <td><img src="img/spacer.gif" width="1" height="33" border="0" alt=""></td>
        </tr>
      </table></td>
    <td  align="left" valign="top" class="copyright"></td>
  </tr>
  <tr> 
    <td height="41" colspan="2" valign="top"> 
      <?php include "utils/bottom.php";?>
    </td>
</table>
</body>
</html>
