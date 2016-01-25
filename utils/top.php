<table border="0" cellpadding="0" cellspacing="0" width="760">
  <!--DWLayoutTable-->
  <tr> 
    <td width="15" height="1"><img src="img/spacer.gif" width="15" height="1" border="0" alt=""></td>
    <td width="7"><img src="img/spacer.gif" width="7" height="1" border="0" alt=""></td>
    <td width="88" valign="top"><img src="img/spacer.gif" width="88" height="1" border="0" alt=""></td>
    <td width="174"><img src="img/spacer.gif" width="174" height="1" border="0" alt=""></td>
    <td width="451"><img src="img/spacer.gif" width="451" height="1" border="0" alt=""></td>
    <td width="8"><img src="img/spacer.gif" width="8" height="1" border="0" alt=""></td>
    <td width="7"><img src="img/spacer.gif" width="7" height="1" border="0" alt=""></td>
    <td width="8"><img src="img/spacer.gif" width="8" height="1" border="0" alt=""></td>
    <td width="2"><img src="img/spacer.gif" width="1" height="1" border="0" alt=""></td>
  </tr>
  <tr> 
    <td colspan="3" rowspan="4" valign="top"><img name="top_r1_c1" src="img/top_r1_c1.jpg" width="110" height="87" border="0" alt=""></td>
    <td height="13"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td><img src="img/spacer.gif" width="1" height="13" border="0" alt=""></td>
  </tr>
  <tr> 
    <td rowspan="3" valign="top"><img name="top_r2_c4" src="img/top_r2_c4.jpg" width="174" height="74" border="0" alt=""></td>
    <td rowspan="3" valign="top" bgcolor="#2B2C91" class="captop">ON-LINE тренажер</td>
    <td height="19" colspan="3"><img name="top_r2_c6" src="img/top_r2_c6.gif" width="23" height="19" border="0" alt=""></td>
    <td><img src="img/spacer.gif" width="1" height="19" border="0" alt=""></td>
  </tr>
  <tr> 
    <td height="39" colspan="2" bgcolor="#2B2C91">&nbsp;</td>
    <td><img name="top_r3_c8" src="img/top_r3_c8.gif" width="8" height="39" border="0" alt=""></td>
    <td><img src="img/spacer.gif" width="1" height="39" border="0" alt=""></td>
  </tr>
  <tr> 
    <td rowspan="2" colspan="3"><img name="top_r4_c6" src="img/top_r4_c6.gif" width="23" height="25" border="0" alt=""></td>
    <td height="16"><img src="img/spacer.gif" width="1" height="16" border="0" alt=""></td>
  </tr>
  <tr> 
    <td height="9" colspan="2"><img name="top_r5_c1" src="img/top_r5_c1.gif" width="22" height="9" border="0" alt=""></td>
    <td colspan="3" background="img/top_r8_c2.gif"></td>
    <td><img src="img/spacer.gif" width="1" height="9" border="0" alt=""></td>
  </tr>
  <tr> 
    <td height="11" colspan="5"></td>
    <td></td>
    <td></td>
    <td></td>
    <td><img src="img/spacer.gif" width="1" height="11" border="0" alt=""></td>
  </tr>
  <tr> 
    <td height="34"><img name="top_r7_c1" src="img/top_r7_c1.gif" width="15" height="34" border="0" alt=""></td>
    <td bgcolor="#FFCA05">&nbsp;</td>
    <td colspan="3" bgcolor="#FFCA05"><a href='index.php' class="topmenu">на главную</a>&nbsp;<font color="#FFFFFF"> 
      | </font>&nbsp;<a href='themes.php' class="topmenu">темы теста</a>&nbsp;<font color="#FFFFFF"> 
      | </font>&nbsp;<a href='about.php' class="topmenu">о проекте</a>&nbsp;<font color="#FFFFFF"> 
      | </font>&nbsp; 
      <?php 
	if(isset($_SESSION['Username']) and $_SESSION['Username'] != 'guest')
		echo  ( ($_SESSION['Username'] != 'guest') ? "<a href='exit.php' class=topmenu>выход</a>&nbsp;<font color=#FFFFFF> | </font>&nbsp;" : "");
	else 
		echo "<a href='reg.php' class=topmenu>регистрация</a>";
	?>
      <?php 
		if(isset($_SESSION['Userrights']) and $_SESSION['Userrights'] == 'admin') echo "<a href='admin/'  class=topmenu>администрирование</a>&nbsp;<font color=#FFFFFF> | </font>&nbsp;";
	?>
    </td>
    <td bgcolor="#FFCA05">&nbsp;</td>
    <td rowspan="2" colspan="2"><img name="top_r7_c7" src="img/top_r7_c7.gif" width="15" height="44" border="0" alt=""></td>
    <td><img src="img/spacer.gif" width="1" height="34" border="0" alt=""></td>
  </tr>
  <tr> 
    <td height="10"><img name="top_r8_c1" src="img/top_r8_c1.gif" width="15" height="10" border="0" alt=""></td>
    <td colspan="4" background="img/top_r8_c2.gif"></td>
    <td background="img/top_r8_c2.gif"></td>
    <td><img src="img/spacer.gif" width="1" height="10" border="0" alt=""></td>
  </tr>
</table>

