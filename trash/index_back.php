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
<title>�������</title>
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
          <td align="center" bgcolor="#2B2C91"><font color="#FFFFFF" size="1"><strong>�����������</strong></font></td>
          <td colspan="2"><img name="auth_r1_c3" src="img/auth_r1_c3.gif" width="34" height="26" border="0" alt=""></td>
          <td><img src="img/spacer.gif" width="1" height="26" border="0" alt=""></td>
        </tr>
        <tr> 
          <td height="145" colspan="3" valign="top" bgcolor="#2B2C91">
<?php 
	if(!isset($_SESSION['Username'])) {
		if( isset($_SESSION['attempt']) )
			echo "������������ ��� ��� ������";
		echo "<p><form action=$auth method=post name=auth id=auth>
       				���:<input name=login type=text id=login size=10><br>
					������:<input name=pass type=password id=pass size=10><br>
        			<input type=submit name=Submit value=�����!>
      			</form></p>";
		$query = "SELECT * FROM user WHERE name='guest' AND password='guest'";
		$guest = mysql_query($query);
		echo (mysql_num_rows($guest) ?"����������� ����� <strong>guest</strong>, ������ <strong>guest</strong> ��� ��������� �����" : "&nbsp;");
		echo "      <p><a href='regform.php'>�����������</a>&nbsp;&nbsp;&nbsp;<a href='forgetpass.php'>������ ������</a></p>";	
		}
		else  {
			if($_SESSION['Username'] != 'guest') {
				if( $_SESSION['Username'] == 'admin')	
					echo "<p><a href='admin/'>�����������������</a>&nbsp;&nbsp;<a href='exit.php'>�����</a></p>";
				else			
					echo  "<p><a href='statistics.php'>����������</a>&nbsp;&nbsp;<a href='exit.php'>�����</a>&nbsp;&nbsp;<a href='themes.php'>���� �����</a>
								&nbsp;&nbsp;<a href='editform.php'>��������� ��������</a></p>";
			}
			else {
				echo "<p>����������� 
    					<form action=$auth method=post name=auth id=auth>
       					���:<input name=login type=text id=login><br>
						������:<input name=pass type=password id=pass><br>
        				<input type=submit name=Submit value=�����!>
      				</form></p>";				
				echo  "<p>�������� �������� ����<br><a href='themes.php'>���� �����</a></p";
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
