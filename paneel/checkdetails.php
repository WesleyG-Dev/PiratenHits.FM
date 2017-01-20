<?php
include("connector.php");
include("functions.php");
if(isset($_GET["username"]))
{
	$username = clean($_GET["username"]);
	$username = strtolower($username);
	$username = ucfirst($username);
	$sql = mysql_num_rows(mysql_query("SELECT `username` FROM `users` WHERE `username` = '$username'"));
	if($sql == "1")
	{
		echo("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"color: darkgreen;\"><strong>$username</strong> exists here</span>");
	}
	else 
	{
		echo("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"color: darkred;\"><strong>$username</strong> does not exists here</span>");
	}
}
else 
{
	exit("No access.");
}
?>