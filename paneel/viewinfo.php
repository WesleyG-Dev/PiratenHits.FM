<?php
if(!defined("kristall"))
{
 	session_start();
	include("functions.php");
	loginerror();
	exit;
}
else
{
	login();
}
$sql = mysql_fetch_array(mysql_query("SELECT * FROM `radioinfo`"));
notice("Radio Information");
echo("Welcome to the Radio Information page, here you can view the current SHOUTcast radio information, please enter this into your desired broadcaster carefully!<hr>
<b>The current radio IP is:</b> {$sql["ip"]}<br />
<b>The current radio Port is:</b> {$sql["port"]}<br />
<b>The current radio Password is:</b> {$sql["pass"]}");
?>