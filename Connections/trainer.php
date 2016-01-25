<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

// Имя домена (хоста)
$hostname_trainer = "";

// Имя базы дынных. На платном хостинге обычно совпадает с именем
// пользователя ftp
$database_trainer = "";

// Имя пользователя для базы данных
$username_trainer = "";

//Пароль
$password_trainer = "";

$trainer = mysql_pconnect($hostname_trainer, $username_trainer, $password_trainer) or die(mysql_error());
mysql_select_db($database_trainer, $trainer);
?>
