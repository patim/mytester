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

	$query = "SELECT * FROM theme WHERE showhide='show'";
	$theme = mysql_query($query,  $trainer) or die(mysql_error());
	$row_theme = mysql_fetch_assoc($theme);
	
?>
<html>
<head>
<title>Тренажер. Список тем</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="153" colspan="3"  valign="top"> 
      <?php include "utils/top.php";?>
    </td>
  </tr>
  <tr> 
    <td width="281" valign="top"> 
           <?php include "utils/auth.php";?> 
    </td>
    <td width="15" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="464" valign="top"><p class="body"><strong>ТЕМЫ ТЕСТА</strong></p>
        
      <br>
      <?php
					echo "      <p class=body>Добро пожаловать"; 
					if(isset($_SESSION['Username']))	
						echo (($_SESSION['Username'] == 'guest') ? ", Гость" : ", ".$_SESSION['Username'] ); 
					echo "!";
					
					if( !isset($_SESSION['Username']) ) {
						$query = "SELECT * FROM user WHERE name='guest' AND password='guest'";
						$guest = mysql_query($query);
						echo " Вы не можете начать тест.  Если вы зарегистрированный пользователь Вам нужно авторизоваться, 
										если  нет, то пройдите процедуру <a href='reg.php' class=bodylink>регистрации</a>".
										(mysql_num_rows($guest) ? " или авторизуйтесь под именем <strong>guest</strong>, паролем 
																				<strong>guest</strong> для гостевого входа." : ".");
					}
	
?></p>
      <p class="body"><font size="4">Список тем:</font></p>
      <?php 
	if(mysql_num_rows($theme)) {
		echo "<p class=body>";
		do  {
			$query = "SELECT * FROM question WHERE id_theme=". $row_theme['id_theme']." AND showhide='show'";
			$question = mysql_query($query,  $trainer) or die(mysql_error());
			$num_ques = mysql_num_rows($question);

		    echo "<form action=questions.php method=post name=theme_form>";
			echo "<input name=id_theme type=hidden value=".$row_theme['id_theme'].">".$row_theme['name']."&nbsp;&nbsp;";
			if($num_ques and isset($_SESSION['Username'])) echo "<input name=start type=submit class=button2 value=Начать!>";
			echo "<br><span class=qamount>количество вопросов: ".$num_ques."</span><br><br>";
			echo "</form>";
		}
		while($row_theme = mysql_fetch_assoc($theme));
		echo "</p><br>";
	}
	else echo "<p>Темы не добавлены</p>";
?>
    </td>
  </tr>
  <tr valign="bottom"> 
    <td colspan="3"> 
      <?php include "utils/bottom.php";?>
    </td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td><img src="img/spacer.gif" alt="" width="464" height="1"></td>
  </tr>
</table>
</body>
</html>

