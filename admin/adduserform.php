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
<title>Администрирование. Добавление пользователя</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript">
function validate(form) {
	if (form.name.value == "" || form.pass.value == "" || form.email.value == "") {
    	if(form.name.value == "") 
			alert("Пожалуйста, введите имя!");
    
		if(form.pass.value == "") 
			alert("Пожалуйста, введите пароль!");
	
		if(form.email.value == "") 
			alert("Пожалуйста, введите email!");								
	}
	else 
		if (form.email.value.indexOf('@', 0) == -1) 
			alert("email задан не правильно!");
   		else  {	
			form.submit();
			return;
		}
}
</script>
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>

<body>

<table width="775" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="152" colspan="2" valign="top"><?php include "adminutils/topadmin.php";?></td>
  </tr>
  <tr> 
    <td width="15"  valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="760" valign="top"> <p class="header">Добавление пользователя</p>
      <p><a href="users.php" class="bodylink">Назад на список пользователей</a></p>
      <br>
        <?php 
	if( isset($_GET['wname']) ) echo "<p><font color=#FF0000>Данное имя уже существует</font></p>";
	if( isset($_GET['wpass']) ) echo "<p><font color=#FF0000>Пороль и подтверждение пароля должны соответствовать</font></p>";
?>
<form name="form1" method="post" action="adduser.php">
        <p class="reg">Имя&nbsp;&nbsp; 
          <input name="name" type="text" id="name" value="<?php echo ( isset($_SESSION['name_tmp']) ? $_SESSION['name_tmp'] :"");?>">
  </p>
        <p class="reg">Пароль&nbsp;&nbsp; 
          <input name="pass" type="password" id="pass">
  </p>
        <p class="reg">Подтверждение пароля&nbsp;&nbsp; 
          <input name="pass2" type="password" id="pass2">
  </p>
        <p class="reg">e-mail&nbsp;&nbsp; 
          <input name="email" type="text" id="email" value="<?php echo ( isset($_SESSION['email_tmp']) ? $_SESSION['email_tmp'] :"");?>">
  </p>
  <p>
          <input name="useradd" type="button" class="button2" id="useradd" onClick="validate(this.form)" value="Добавить">
  </p>
</form>
<p>&nbsp; </p>	
	</td>
  </tr>
  <tr valign="bottom"> 
    <td height="27" colspan="2"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
