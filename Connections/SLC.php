<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_SLC = "localhost";
$database_SLC = "slc";
$username_SLC = "root";
$password_SLC = "";
$SLC = mysql_pconnect($hostname_SLC, $username_SLC, $password_SLC) or trigger_error(mysql_error(),E_USER_ERROR); 
?>