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
	

	if( isset($_GET['id_theme']) ) {
		$query = "SELECT * FROM theme WHERE id_theme=".$_GET['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);
	}	
	if( isset($_POST['id_theme']) )	 {
		$query = "SELECT * FROM theme WHERE id_theme=".$_POST['id_theme'];
		$theme = mysql_query($query,  $trainer) or die(mysql_error());
		$row_theme = mysql_fetch_assoc($theme);
	}	
	
	if( isset($_GET['id_ques']) ) {
		$query = "SELECT * FROM question WHERE id_ques=".$_GET['id_ques'];
		$ques = mysql_query($query,  $trainer) or die(mysql_error());
		$row_ques = mysql_fetch_assoc($ques);

		$query = "SELECT * FROM answer WHERE id_ques=".$_GET['id_ques'];
		$ans = mysql_query($query,  $trainer) or die(mysql_error());
		$num_ans = mysql_num_rows($ans);

		$query = "SELECT * FROM answer_corr WHERE id_ques=".$_GET['id_ques'];
		$ans_corr = mysql_query($query,  $trainer) or die(mysql_error());
		$num_ans_corr = mysql_num_rows($ans_corr);
		
		$enbl = ($num_ans_corr or $num_ans);	
		
		$init_txt = $row_ques['que_text'];
		$init_mark = $row_ques['mark'];
		($row_ques['picture']) ? $init_picture = "<a href='../".$row_ques['picture']."' target=_blank><img src=../img/see.gif alt='просмотр добавленного изображения' border=0></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='delpict.php?id_theme=".$row_theme['id_theme']."&id_ques=".
																		$_GET['id_ques']."' onclick=\"if(confirm('Вы действительно желаете удалить данное изображение?')) return true; else return false;\" LANGUAGE=\"Javascript\"><img src=../img/del.gif alt='удалить изображение' border=0 align=absmiddle></a><br>" : $init_picture = "";
		
		($row_ques['sound']) ? $init_sound =  "<input name=play type=button class=play value='play   ' onClick=\"MM_controlSound('play','document.CS1147854152515','".
																	$row_ques['sound']."')\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='delsound.php?id_theme=".$row_theme['id_theme']."&id_ques=".$_GET['id_ques']."' onclick=\"if(confirm('Вы действительно желаете удалить данный звуковой файл?')) return true; else return false;\" LANGUAGE=\"Javascript\">
																	<img src=../img/del.gif alt='удалить звуковой файл' border=0 align=absmiddle></a><br><br><EMBED NAME='CS1147854152515' SRC='../".$row_ques['sound'].
																	"' LOOP=false AUTOSTART=false MASTERSOUND HIDDEN=true WIDTH=0 HEIGHT=0></EMBED>" : $init_sound = "";
		
		($row_ques['showhide'] == 'show') ? $init_sh = "checked" : $init_sh = "";
		
		
		for($i=0; $i < 6; $i++) $type[$i] = '';
			
		switch($row_ques['qtype']) {
			case 'single': $type[1] = 'selected';	break;			
			case  'many': $type[2] = 'selected';	break;								
			case 'order':	$type [3]= 'selected'; 	break;						
			case  'correspond': $type[4] = 'selected';	break;						
			case 'enter': $type[5] = 'selected';		break;
		}
		$id_ques_input = "<input name=id_ques type=hidden value=".$_GET['id_ques']." >";
	}
	else {
		$init_txt = "";
		$init_mark = "";
		$init_picture = "";
		$init_sound = "";
		$init_sh = "checked";
		$type[0] = 'selected';
		for($i=1; $i < 6; $i++) $type[$i] = '';
	}
?>
<html>
<head>
<title>Администрирование. Добавление вопроса - шаг 1</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript">
<!-- Скрыть

function del_confirm() {
 if(confirm("Вы действительно желаете удалить данное изображение?"))	
 	return true;
 else 
 	return false;
}

function check(input, check) {
  var ok = true;

  for (var i = 0; i < input.length; i++) {
    var chr = input.charAt(i);
    var found = false;
    for (var j = 0; j < check.length; j++) 
      if (chr == check.charAt(j)) 
	  	found = true;
    if (!found) ok = false;
  }
 
  return ok;
}

function vlidate(form) {
	if (form.que_text.value == "" || form.qtype.value == 0 || form.mark.value == "") {
    	if(form.que_text.value == "") 
			alert("Пожалуйста, введите формулировку вопроса!");
    
		if(form.qtype.value == 0) 
			alert("Пожалуйста, введите тип вопроса!");
	
		if(form.mark.value == "") 
			alert("Пожалуйста, введите количество балов!");								
	}
	else 
		if ( !check(form.mark.value, "1234567890") ) 
			alert("Количество балов должно быть числом");
   		else  {	
			form.submit();
			return;
		}
}

function MM_controlSound(x, _sndObj, sndFile) { //v3.0
  var i, method = "", sndObj = eval(_sndObj);
  if (sndObj != null) {
    if (navigator.appName == 'Netscape') method = "play";
    else {
      if (window.MM_WMP == null) {
        window.MM_WMP = false;
        for(i in sndObj) if (i == "ActiveMovie") {
          window.MM_WMP = true; break;
      } }
      if (window.MM_WMP) method = "play";
      else if (sndObj.FileName) method = "run";
  } }
  if (method) eval(_sndObj+"."+method+"()");
  else window.location = sndFile;
}
// -->
</script>
<link href="../styles.css" rel="stylesheet" type="text/css">
</head>

<body>

<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td height="152" colspan="2" valign="top"> 
      <?php include "adminutils/topadmin.php";?>
    </td>
  </tr>
  <tr> 
    <td width="15" >&nbsp;</td>
    <td width="745" valign="top"> <p class="body" ><span class="header">Генератор 
        вопросов</span><br>
        Добавление вопроса - шаг 1(из 3-х). </p>
      <p><a class="bodylink" href="adminqus.php?id_theme=<?php echo $row_theme['id_theme'];?>">Назад 
        на список тем</a></p>
      <p class="body"><strong>Тема:</strong> <?php echo $row_theme['name'];?></p>
      <?php 
if(isset($_GET['sound'])) echo "<p><font color=#FF0000>Большой звуковой файл!</font></p>";
if(isset($_GET['pict'])) echo "<p><font color=#FF0000>Большой файл изображения!</font></p>";
if(isset($_GET['not_sound'])) echo "<p><font color=#FF0000>Не файл аудио!</font></p>";
if(isset($_GET['not_pict'])) echo "<p><font color=#FF0000>Неграфический файл!</font></p>";
?>
      <span class="instr">Поля помеченные <font color="#FF0000">*</font> обязательны 
        к заполнению</span>
      <form action="addanform.php" method="post" enctype="multipart/form-data" name="form1" onSubmit="validate(this.form)">
        <input name="id_theme" type="hidden" value="<?php echo $row_theme['id_theme'];?>">
        <?php echo isset($id_ques_input) ? $id_ques_input : "";?> 
        <p class="reg">Введите формулировку вопроса<font color="#FF0000">*</font><br>
          <br>
          <textarea  name="que_text" cols="90" rows="6" class="editbt2" id="que_text"><?php echo $init_txt;?></textarea>
          <br>
          <br>
          <br>
          <br>
          <?php 
	echo (isset($enbl) and $enbl) ? "<font color=#CC3366>Вы не можете изменить тип вопроса.<br> Сначала удалите ответы к нему</font><br><input name=qtype type=hidden value=".$_GET['id_ques'].">": " Укажите тип вопроса<font color=#FF0000>*</font><br><br>";
	?>
          <select name="qtype" class="editbt2" id="qtype" <?php echo (isset($enbl) and $enbl) ? " disabled ": "";?>>
            <option value="0" selected <?php echo $type[0]; ?>></option>
            <option value="single" <?php echo $type[1]; ?>>1. 
            Выбор единственного правильного ответа</option>
            <option value="many" <?php echo $type[2]; ?>>2. 
            Выбор возможных правильных ответов</option>
            <option value="order" <?php echo $type[3]; ?>>3. 
            Установить последовательность ответов</option>
            <option value="correspond" <?php echo $type[4]; ?>>4. 
            Установить соответствия ответов</option>
            <option value="enter" <?php echo $type[5]; ?>>5. 
            Ввод ответа вручную с клавиатуры</option>
          </select>
          <br>
          <br>
          <br>
          <br>
          Количество балов<font color="#FF0000">*</font><br>
          <br>
          <input name="mark" type="text" class="editbt2" id="mark" value="<?php echo $init_mark;?>" size="2">
          <br>
          <br>
          <br>
          <?php echo $init_picture;?> Выберите изображение <br>
          <br>
          <input name="pict" type="file" class="editbt2" id="pict2" size="40">
          <br>
          <br>
          <br>
          <?php echo $init_sound;?> Выберите звуковой файл <br>
          <br>
          <input name="sound" type="file" class="editbt2" id="sound" size="40">
          <br>
          <br>
          <br>
          <br>
          Показывать 
          <input name="sh" type="checkbox" id="sh" value="checkbox" <?php echo $init_sh;?>>
        </p>
        <p> 
          &nbsp;<input name="step1" type="button" class="button2" id="step1" onClick="vlidate(this.form)" value="Далее">
        </p>
      </form>
      <p>&nbsp;</p></td>
  </tr>
  <tr> 
    <td colspan="2" valign="bottom"> 
      <?php include "adminutils/bottomadmin.php";?>
    </td>
  </tr>
</table>
</body>
</html>
