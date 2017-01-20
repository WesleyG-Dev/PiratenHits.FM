<?php
session_start();
include("connector.php");
include("functions.php");
login();
$username = clean($_SESSION["kristall_username"]);
$sql = mysql_fetch_array(mysql_query("SELECT `pin` FROM `users` WHERE `username` = '$username'"));
if($sql["pin"] == "0")
{
	$pin = rand(1, 9);
	$pin .= rand(1, 9);
	$pin .= rand(1, 9);
	$pin .= rand(1, 9);
	mysql_query("UPDATE `users` SET `pin` = '$pin' WHERE `username` = '$username'");
	errorheader("Your pin number has been set!");
	echo("Welcome to Kristall-Panel for the first time, {$_SESSION["kristall_username"]}!<br />
We're glad you're using this panel, but before we let you in to use all the features available, we need to give you a secret pin number you can use, just incase you suspect anything has gone wrong with your account.<br /><br />
Your secret pin number is: <b>$pin</b><br />
Write it down!<br /><br />
To continue to the panel, click <a href=\"main.php\">here</a>.");
}
else
{
	header("Location: main.php");
}
?>