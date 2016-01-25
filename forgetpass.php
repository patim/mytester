<?php 
	if( isset($_POST['email']) ) {
		require_once('Connections/trainer.php');
		$query = "SELECT * FROM user WHERE email='".$_POST['email']."'";
		$user = mysql_query($query);
		if( mysql_num_rows($user) )  {
			$row_user = mysql_fetch_assoc($user);
			$result = true;
			$e_mail =  $_POST['email'];
			$theme = "Доставка пароля";
			$msg = "Ваш пароль: ". $row_user['password'] . "\r\n";
			$sender = "From: passdeliver@".$_SERVER['SERVER_NAME'];
			//mail($e_mail, $theme, $msg, $sender);
			echo  $e_mail, $theme, $msg, $sender;
		}		
		else  
			$result = false;
	}
	
?>
<html>
<head>
<title>Забыли пароль?</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="760" height="153" valign="top"> 
      <?php include "utils/top.php";?>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?php 
		if(!isset($result) or !$result)
			echo "<p class=body><br>Забыли пароль? 
        			Введите e-mail, указанный Вами при регистрации,<br>
        			и система автоматически вышлет Ваш пароль</p>
      				<form name=form1 method=post action=forgetpass.php>
        			<span class=reg>e-mail:</span> 
        			&nbsp;<input name=email type=text id=email>
        			<br><br>
        			<input name=send type=submit id=send  class=button2 value=Отправить>
      				</form>
      				</p>";		

	  	if( isset($result) ) 
	  		if($result) echo "<p>Сообщение послано</p>"; 
			else echo "e-mail не найден";
		else
			echo "&nbsp;";
 ?></p>
      </td>
  </tr>
  <tr> 
    <td valign="bottom" width="10%"> 
      <?php include "utils/bottom.php";?>
    </td>
  </tr>
</table>
</body>
</html>

