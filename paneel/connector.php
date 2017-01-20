<?php
$hostname = ("localhost");
$database = ("wesleygrev_dj");
$username = ("wesleygrev_dj");
$password = ("Kk303pJyEJ");
@mysql_connect($hostname, $username, $password)
or exit("MySQL Connection Error");
@mysql_selectdb($database)
or exit("MySQL Database Selection Error, please check your details!");
?>