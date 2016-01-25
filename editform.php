<?php
	session_start();
	$restrictGoTo=  "index.php";	
	if ( !isset($_SESSION['Username']) )	header("Location: ". $restrictGoTo);
	else {
		require_once('Connections/trainer.php');
		$query = "SELECT * FROM user WHERE name='". $_SESSION['Username']."'";
		$id_u = mysql_query($query, $trainer) or die(mysql_error());
		$id_u_row = mysql_fetch_assoc($id_u);
	}	
?>
<html>
<head>
<title>Редактирование данных о пользователе</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript">
function validate(form) {
	if (form.name.value == ""  || form.email.value == "") {
    	if(form.name.value == "") 
			alert("Пожалуйста, введите имя!");
    
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
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="153" >
	<?php include "utils/top.php";?>
    </td>
  </tr> 
  <tr> 
    <td width="760" valign="top"> <br> 
      <p class="bodylink"><a href="index.php">Назад </a></p>
      <p class="body"> Редактирование данных о пользователе</p>
      <?php 
	if( isset($_GET['wname']) ) echo "<p><font color=#FF0000>Данное имя уже существует</font></p>";
	if( isset($_GET['wpass']) ) echo "<p><font color=#FF0000>Пороль и подтверждение пароля должны соответствовать</font></p>";
	
?>
      <form name="form1" method="post" action="edit.php">
        <input name="id_user" type="hidden" value="<?php echo $id_u_row['id_user'];?>">
        <p class="reg">Имя&nbsp;&nbsp; 
          <input name="name" type="text" id="name" value="<?php echo ( isset($_SESSION['name_tmp']) ? $_SESSION['name_tmp'] : $_SESSION['Username']);?>">
        </p>
        <p class="reg">Новый пароль&nbsp;&nbsp; 
          <input name="pass" type="password" id="pass">
        </p>
        <p class="reg">Подтверждение пароля&nbsp;&nbsp; 
          <input name="pass2" type="password" id="pass2">
        </p>
        <p class="reg">e-mail&nbsp;&nbsp; 
          <input name="email" type="text" id="email" value="<?php echo ( isset($_SESSION['email_tmp']) ? $_SESSION['email_tmp'] :$id_u_row['email']);?>">
        </p>
        <p> 
          <input name="useredit" type="button" class="button2" id="useredit" onClick="validate(this.form)" value="Изменить">
        </p>
      </form>
      <p>&nbsp; </p></td>
  </tr>
  <tr> 
    <td height="18" valign="bottom">
      <?php include "utils/bottom.php";?>
    </td>
  </tr>
</table>
</body>
</html>
