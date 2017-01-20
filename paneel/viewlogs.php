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
	login("admin");
}
notice("View login logs");
echo("Welcome to the view login logs page, here you can view the current login logs for this panel, all logs are in a copy and pastable format for easy storage.<hr>");
$sql = mysql_query("SELECT * FROM `logs` ORDER BY `id` ASC");
while($fetch = mysql_fetch_array($sql))
{
	echo("ID{$fetch["id"]} - <b>{$fetch["name"]}</b> logged in at <b>{$fetch["date"]}</b> with the ip; <b>{$fetch["ip"]}</b> and <b>{$fetch["success"]}</b> the login checks<br />");
}
?>