<?php session_start();?>
<html>
<head>
<title>Регистрация пользователя пользователя</title>
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
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="152" colspan="2" valign="top"> 
      <?php include "utils/top.php";?>
    </td>
  </tr>
  <tr> 
    <td width="15" valign="top" ><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="745" valign="top" > <p class="body"><strong>РЕГИСТРАЦИЯ</strong></p>
      <p class="body">Введите данные о новом пользователе.</p>
      <?php 
	if( isset($_GET['wname']) ) echo "<p><font color=#FF0000>Данное имя уже существует</font></p>";
	if( isset($_GET['wpass']) ) echo "<p><font color=#FF0000>Пороль и подтверждение пароля должны соответствовать</font></p>";
?>
      <form name="form1" method="post" action="reg.php">
        <p class="reg">Имя&nbsp;&nbsp; 
          <input name="name" type="text" class="editbt2" id="name" value="<?php echo ( isset($_SESSION['name_tmp']) ? $_SESSION['name_tmp'] :"");?>" size="30">
        </p>
        <p class="reg">Пароль&nbsp;&nbsp; 
          <input name="pass" type="password" class="editbt2" id="pass" size="30">
        </p>
        <p class="reg">Подтверждение пароля&nbsp;&nbsp; 
          <input name="pass2" type="password" class="editbt2" id="pass2" size="30">
        </p>
        <p class="reg">e-mail&nbsp;&nbsp; 
          <input name="email" type="text" class="editbt2" id="email" value="<?php echo ( isset($_SESSION['email_tmp']) ? $_SESSION['email_tmp'] :"");?>" size="30">
        </p>
        <p> 
          <input name="useradd" type="button" class="button2" id="useradd" onClick="validate(this.form)" value="Добавить">
        </p>
      </form>
      <p>&nbsp; </p></td>
  </tr>
  <tr> 
    <td height="18" colspan="2" valign="bottom"> 
      <?php include "utils/bottom.php";?>
    </td>
  </tr>
</table>

</body>
</html>
