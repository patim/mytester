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
	
	$act = $_SERVER[ 'PHP_SELF' ];
	
	if(isset($_GET['id_theme']))	 {
		$query = "SELECT * FROM theme WHERE id_theme=".$_GET['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);
	}
	
	if( isset($_POST['edit_thm']) ) {
		if($_POST['showhide']) $sh = 'show';
		else  $sh = 'hide';	
		$query = "UPDATE theme SET name='".$_POST['edit_thm_name']."', showhide='".$sh."', date='".date("Y-m-d H:i:s")."' WHERE id_theme=".$_POST['id_thm'];
		mysql_query($query);
		header("Location: admin_thm.php");
	}
		
?>
<html>
<head>
<title>Редактирование темы</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="152" colspan="2" valign="top"><?php include "adminutils/topadmin.php";?></td>
  </tr>
  <tr> 
    <td width="15" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="745" valign="top">
<p class="header">Редактирование темы</p>
      <p><a href="admin_thm.php" class="bodylink">Назад</a></p>
<form name="form1" method="post" action="<?php echo $act;?>">
        <p class="reg">Тема: 
          <input name="id_thm" type="hidden" value="<?php echo $_GET['id_theme'];?>">
          <input name="edit_thm_name" type="text" class="editbt2" value="<?php echo $row_theme['name'];?>" size="60">
          </p> 
        </p>
        <p class="reg">Дата: <?php echo date("d-m-Y H:i:s",  strtotime($row_theme['date']));?></p>
        <p class="reg">Количество вопросов: <?php echo $_GET['qs'];?></p>
        <p class="reg"> 
          <input name="showhide" type="checkbox" id="showhide" <?php echo ($row_theme['showhide'] == 'show') ? "checked" : ""; ?>>
    Показывать </p>
  <p>
          <input name="edit_thm" type="submit" class="button2" id="edit_thm" value="Изменить">
</form>
<p>&nbsp;</p>
    </td>
  </tr>
  <tr valign="bottom"> 
    <td colspan="2"><?php include "adminutils/bottomadmin.php";?></td>
  </tr>
</table>
</body>
</html>

