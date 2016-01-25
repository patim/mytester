<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<script language="JavaScript">
<!--
function change(name){
  if (document.getElementById(name).style.display=='none') {
    document.getElementById(name).style.display=''; 
  } else 
  { document.getElementById(name).style.display='none';}
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
//-->
</script>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<a href="javascript: location.replace('test2.php?id=1')">Ссылка на тест 2</a> 
<br>
<a href="test2.php?id=1">Простая ссылка на тест 2</a> <br>
  
<input name=play type="button" class="play" id="play" onClick="MM_controlSound('play','document.CS1147348553906','files/temp.mid')"  value="play   " >
  
<form action="test2.php"  method="get" >
  <input name="f" type="text" >
  <input name=Submit type="submit"  value="1" >
</form>
<form action="test2.php" method=post name="form2" id="form2" >
  <select name=select[1] class="instr">
    <option value="0" selected></option>
    <option value="1">Один</option>
    <option value="2">Два</option>
  </select>
  <br>
  <br>
  <select name=select[2]>
    <option value=1>Один</option><br>
    <option value=2>Два</option>
  </select>
  <br>
  <br>
  <input type=submit value=ответить>
</form>
<A class=link_menu href='javascript:change('name')'>Продажа</A> 
<DIV id='name' style="DISPLAY: none;"> <A class="open_menu" href="<?php echo $path;?>commerce/sell/office/index.php?id_type=1&action=sell">Офисные 
  помещения</A><br>
  <A class="open_menu" href="<?php echo $path;?>commerce/sell/sklad/index.php?id_type=2&action=sell">Складские 
  помещения</A><br>
  <A class="open_menu" href="<?php echo $path;?>commerce/sell/torg/index.php?id_type=3&action=sell">Торговые 
  помещения</A><br>
  <A class="open_menu" href="<?php echo $path;?>commerce/sell/industrial/index.php?id_type=4&action=sell">Индустриальная 
  недвижимость</A><br>
  <A class="open_menu" href="<?php echo $path;?>commerce/sell/other/index.php?id_type=5&action=sell">Прочее</A> 
</DIV>
Поставить соответствие<BR>
<A href="javascript:change('name1')">Лошадь</A> 
<DIV id=name1 style="DISPLAY: none"> 
  <INPUT type=checkbox value=1 
name=corr[19][1]>
  Банан<BR>
  <INPUT type=checkbox value=2 
name=corr[19][2]>
  Овес<BR>
  <INPUT type=checkbox value=3 
name=corr[19][3]>
  Рыба<BR>
  <INPUT type=checkbox value=4 
name=corr[19][4]>
  Пиво<BR>
  <INPUT type=checkbox value=5 
name=corr[19][5]>
  Сено<BR>
</DIV>
<A href="javascript:change('name2')">Обезьяна</A> 
<DIV id=name2 style="DISPLAY: none">
  <INPUT type=checkbox value=1 
name=corr[20][1]>
  Банан<BR>
  <INPUT type=checkbox value=2 
name=corr[20][2]>
  Овес<BR>
  <INPUT type=checkbox value=3 
name=corr[20][3]>
  Рыба<BR>
  <INPUT type=checkbox value=4 
name=corr[20][4]>
  Пиво<BR>
  <INPUT type=checkbox value=5 
name=corr[20][5]>
  Сено<BR>
</DIV>
<A href="javascript:change('name3')">Кошка</A> 
<DIV id=name3 style="DISPLAY: none"> 
  <INPUT type=checkbox value=1 
name=corr[21][1]>
  Банан<BR>
  <INPUT type=checkbox value=2 
name=corr[21][2]>
  Овес<BR>
  <INPUT type=checkbox value=3 
name=corr[21][3]>
  Рыба<BR>
  <INPUT type=checkbox value=4 
name=corr[21][4]>
  Пиво<BR>
  <INPUT type=checkbox value=5 
name=corr[21][5]>
  Сено<BR>
</DIV>
<table width="605" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <!--DWLayoutTable-->
  <tr> 
    <td width="32" height="31" valign="top"><img src="img/finger.gif" width="17" height="33"></td>
    <td width="" valign="middle" bgcolor="#CCCCCC" ><a href="#" class="answer">Вася</a></td>
  </tr>
  <tr> 
    <td height="31" valign="top"><img src="img/finger.gif" width="17" height="33"></td>
    <td valign="middle">Коля</td>
  </tr>
  <tr> 
    <td height="33" colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
  </tr>
  <tr> 
    <td height="63">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
