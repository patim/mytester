      <?php 
if(!isset($_SESSION['Username']) or $_SESSION['Username'] == 'guest') {

?>
      <form action=<?php echo $auth;?> method=post name=auth id=auth>
        <table width="169" border="0" cellpadding="0" cellspacing="0" bgcolor="#2B2C91">
          <!--DWLayoutTable-->
          <tr> 
            <td width="26" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="26" height="1" border="0" alt=""></td>
            <td colspan="2" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="220" height="1" border="0" alt=""></td>
            <td width="23" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="23" height="1" border="0" alt=""></td>
            <td width="11" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="11" height="1" border="0" alt=""></td>
            <td width="1" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="1" height="1" border="0" alt=""></td>
          </tr>
          <tr> 
            <td><img name="auth_r1_c1" src="img/auth_r1_c1.gif" width="26" height="26" border="0" alt=""></td>
            <td colspan="2" align="center" bgcolor="#2B2C91" class="captauth">авторизация</td>
            <td colspan="2"><img name="auth_r1_c3" src="img/auth_r1_c3.gif" width="34" height="26" border="0" alt=""></td>
            <td bgcolor="#FFFFFF"><img src="img/spacer.gif" width="1" height="26" border="0" alt=""></td>
          </tr>
          <tr> 
            <td rowspan="4" valign="top" bgcolor="#2B2C91"> <p></p></td>
            <td width="67" height="35" align="right" valign="middle" bgcolor="#2B2C91"> 
              <p class="captauth">имя: </p></td>
            <td width="153" valign="middle" bgcolor="#2B2C91"> <input name=login type=text id=login2> 
              <br> </td>
            <td rowspan="4" valign="top" bgcolor="#2B2C91"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td rowspan="4" background="img/auth_r2_c4.gif"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td rowspan="4" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="1" height="140" border="0" alt=""></td>
          </tr>
          <tr> 
            <td height="35" align="right" valign="middle" bgcolor="#2B2C91" class="captauth"><p>пароль: 
              </p></td>
            <td valign="middle" bgcolor="#2B2C91"> <input name=pass type=password id=pass2></td>
          </tr>
          <tr> 
            <td height="55" align="right" valign="middle" bgcolor="#2B2C91"><input name=Submit type=submit class="button2" value=Войти!> 
            </td>
            <td align="right" valign="top" bgcolor="#2B2C91"> <a href='regform.php' class="usermenu">регистрация</a><a href='forgetpass.php' class="usermenu"><br>
              <br>
              забыли пароль?</a></td>
          </tr>
          <tr> 
            <td height="75" colspan="2" valign="top" bgcolor="#2B2C91"> 
              <?php 
	if(isset($_SESSION['Username'])  and $_SESSION['Username'] == 'guest') echo "<p class=guest>Выполнен гостевой вход<br><br><a href='themes.php' class=usermenu>темы теста</a></p>";
		if( isset($_SESSION['attempt']) ) {
			unset($_SESSION['attempt']);
			echo "<p class=badlogin >Неправильное имя или пароль</p>";
		}
?>
            </td>
          </tr>
          <tr> 
            <td><img name="auth_r3_c1" src="img/auth_r3_c1.gif" width="26" height="33" border="0" alt=""></td>
            <td colspan="2" background="img/auth_r3_c2.gif"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td colspan="2"><img name="auth_r3_c3" src="img/auth_r3_c3.gif" width="34" height="33" border="0" alt=""></td>
            <td bgcolor="#FFFFFF"><img src="img/spacer.gif" width="1" height="33" border="0" alt=""></td>
          </tr>
        </table>
      </form>
<?php  
} // для if(!isset($_SESSION['Username']) or $_SESSION['Username'] != 'guest') 
	else {
?>
      <table width="281" border="0" cellpadding="0" cellspacing="0" bgcolor="#2B2C91">
        <!--DWLayoutTable-->
        <tr> 
          <td width="26" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="26" height="1" border="0" alt=""></td>
          <td colspan="2" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="220" height="1" border="0" alt=""></td>
          <td width="23" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="23" height="1" border="0" alt=""></td>
          <td width="11" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="11" height="1" border="0" alt=""></td>
          <td width="20" bgcolor="#FFFFFF"><img src="img/spacer.gif" width="1" height="1" border="0" alt=""></td>
        </tr>
        <tr> 
          <td><img name="auth_r1_c1" src="img/auth_r1_c1.gif" width="26" height="26" border="0" alt=""></td>
          <td colspan="2" align="center" bgcolor="#2B2C91" class="captauth"><?php echo $_SESSION['Username']." ";?> вошёл</td>
          <td colspan="2"><img name="auth_r1_c3" src="img/auth_r1_c3.gif" width="34" height="26" border="0" alt=""></td>
          <td bgcolor="#FFFFFF"><img src="img/spacer.gif" width="1" height="26" border="0" alt=""></td>
        </tr>
        <tr> 
          <td rowspan="2" valign="top" bgcolor="#2B2C91"> </td>
          <td colspan="2" align="right" valign="middle" bgcolor="#2B2C91"> </td>
          <td rowspan="2" valign="top" bgcolor="#2B2C91"><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td rowspan="2" background="img/auth_r2_c4.gif"><img src="img/spacer.gif" width="1" height="140" border="0" alt=""></td>
          <td rowspan="2" bgcolor="#FFFFFF"><!--DWLayoutEmptyCell-->&nbsp;</td>
        </tr>
        <tr> 
          <td height="75" colspan="2" valign="top" bgcolor="#2B2C91"> 
            <?php 
			if( $_SESSION['Userrights'] == 'admin')	
				echo "<a href='admin/' class=usermenu>администрирование<br><br></a><a href='exit.php' class=usermenu>выход</a>";
			else {
?>
            <a href='statistics.php' class="usermenu">статистика<br>
            <br>
            </a> <a href='themes.php' class="usermenu">темы теста<br>
            <br>
            </a><a href='editform.php' class="usermenu">изменение настроек<br>
            </a><a href='exit.php' class="usermenu"><br>
            выход</a> 
            <?php 
			}
?>
          </td>
        </tr>
        <tr> 
          <td><img name="auth_r3_c1" src="img/auth_r3_c1.gif" width="26" height="33" border="0" alt=""></td>
          <td colspan="2" background="img/auth_r3_c2.gif"><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td colspan="2"><img name="auth_r3_c3" src="img/auth_r3_c3.gif" width="34" height="33" border="0" alt=""></td>
          <td bgcolor="#FFFFFF"><img src="img/spacer.gif" width="1" height="33" border="0" alt=""></td>
        </tr>
      </table>
      <?php 
}
?>      
