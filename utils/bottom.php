<table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="22" colspan="2" valign="top">            
<table border="0" cellpadding="0" cellspacing="0" width="758">
  <tr> 
    <td><img src="img/spacer.gif" width="17" height="1" border="0" alt=""></td>
    <td colspan="2"><img src="img/spacer.gif" width="723" height="1" border="0" alt=""></td>
    <td><img src="img/spacer.gif" width="18" height="1" border="0" alt=""></td>
    <td><img src="img/spacer.gif" width="1" height="1" border="0" alt=""></td>
  </tr>
  <tr> 
    <td rowspan="2"><img name="botom_r1_c1" src="img/botom_r1_c1.gif" width="17" height="30" border="0" alt=""></td>
    <td height="24" bgcolor="#FFCA05"> <a href='index.php' class="bottommenu">на главную</a>&nbsp;<font color="#FFFFFF"> | </font>&nbsp;<a href='themes.php' class="bottommenu">темы 
      теста</a> <font color="#FFFFFF" > | </font> <a href='about.php' class="bottommenu">о 
      проекте</a> <font color="#FFFFFF" > | </font>
 <?php 
	if(isset($_SESSION['Username']) and $_SESSION['Username'] != 'guest')
		echo  ( ($_SESSION['Username'] != 'guest') ? "<a href='exit.php' class=bottommenu>выход</a><font color=#FFFFFF> | </font>" : "");
	else 
		echo "<a href='reg.php' class=bottommenu>регистрация</a><font color=#FFFFFF> | </font>";
	?>
            <?php 
		if(isset($_SESSION['Userrights']) and $_SESSION['Userrights'] == 'admin') echo "<a href='admin/'  class=bottommenu>администрирование</a> <font color=#FFFFFF> | </font>";
	?>	  
	   </td>
    <td bgcolor="#FFCA05"><img src="img/spacer.gif" width="1" height="24"></td>
    <td rowspan="2"><img name="botom_r1_c3" src="img/botom_r1_c3.gif" width="18" height="30" border="0" alt=""></td>
    <td><img src="img/spacer.gif" width="1" height="24" border="0" alt=""></td>
  </tr>
  <tr> 
    <td colspan="2" background="img/botom_r2_c2.gif"></td>
    <td><img src="img/spacer.gif" width="1" height="6" border="0" alt=""></td>
  </tr>
</table>		
	</td>
  </tr>
  <tr> 
    <td width="50" rowspan="2" valign="top"><img src="img/volsu.gif" alt="Герб ВолГУ" width="50" height="73"></td>
    <td width="710" height="43" align="left" valign="bottom" class=copyright>&nbsp;&nbsp;&nbsp;
     <em>2006 Волгоградский Государственный Университет</em><br>&nbsp;&nbsp;&nbsp;&nbsp;Кафедра теоретической физики и волновых процессов</td>
  </tr>
  <tr> 
    <td height="30" align="right" valign="bottom" class="author">development: <a href='mailto:max-cpp@yandex.ru' class="author">Maxim Zalutskiy</a></td>
  </tr>
</table>

