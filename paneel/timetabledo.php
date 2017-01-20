<?php
include("connector.php"); // Requires config.php.
include("functions.php");
session_start();
if(!isset($_SESSION["kristall_username"]) || empty($_SESSION["kristall_username"]) || empty($_SESSION["kristall_level"]) || !isset($_SESSION["kristall_level"]) || !isset($_SESSION["kristall_ip"]) || empty($_SESSION["kristall_ip"]) || !isset($_SESSION["kristall_hostname"]) || empty($_SESSION["kristall_hostname"]))
	{
		errorheader("You're not logged in!");
		echo("You are currently not logged in, this could be for a variety of reasons:<br />- You have not logged in.<br />- Your session has timed out.<br />- An error has occured.<br /><br />If all of the following items below are set, then please contact an administrator immediatley.<br>Username: {$_SESSION["kristall_username"]}<br />Level: {$_SESSION["kristall_level"]}<br />IP: {$_SESSION["kristall_ip"]}<br />Hostname: {$_SESSION["kristall_hostname"]}");
		errorfooter();
		session_destroy();
		session_unset();
		exit;
	}
if(isset($_GET["day"]) && $_GET["day"] != "undefined")
{
	$date = clean($_GET["day"]);	
}
else
{
	$date = gmdate("l");
}
$sql = mysql_fetch_array(mysql_query("SELECT * FROM `timetable` WHERE `day` = '$date'"));
if($_POST["action"] == "book")
{
	$username = clean($_SESSION["kristall_username"]);
 	$slot = clean($_POST["slot"]);
	if($sql[$slot] == "" || $_SESSION["kristall_level"] == "Senior DJ" || $_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
	{
		mysql_query("UPDATE `timetable` SET `$slot` = '$username' WHERE `day` = '{$_POST["day"]}'");
	}
	else
	{
	}
}
elseif($_POST["action"] == "unbook")
{
	$slot = clean($_POST["slot"]);
	if($sql[$slot] == $_SESSION["kristall_username"] || $_SESSION["kristall_level"] == "Senior DJ" || $_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
	{
		mysql_query("UPDATE `timetable` SET `$slot` = '' WHERE `day` = '{$_POST["day"]}'");
	}
	else
	{
	}
}
elseif($_POST["action"] == "clear" && $_SESSION["kristall_level"] == "Super Administrator")
{
	for($num = 0; $num < 25; $num++)
	{
		mysql_query("UPDATE `timetable` SET `$num` = '' WHERE `day` = '{$_POST["day"]}'");
	}
}
else
{
$variable = array("", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty", "twentyone", "twentytwo", "twentythree", "twentyfour"); // Holds a list of all the words thou shalt need, and OH YES, a new, highly efficient timetable.
$printtimes = array("", "00:01 - 01:00", "01:01 - 02:00", "02:01 - 03:00", "03:01 - 04:00", "04:01 - 05:00", "05:01 - 06:00", "06:01 - 07:00", "07:01 - 08:00", "08:01 - 09:00", "09:01 - 10:00", "10:01 - 11:00", "11:01 - 12:00", "12:01 - 13:00", "13:01 - 14:00", "14:01 - 15:00", "15:01 - 16:00",  "16:01 - 17:00", "17:01 - 18:00", "18:01 - 19:00", "19:01 - 20:00", "20:01 - 21:00", "21:01 - 22:00", "22:01 - 23:00", "23:01 - 00:00");
for($num = 1; $num < 25; $num++)
{
	$slot = $variable[$num];
	if(preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"]))
	{
		$hack = " href=\"#\"";
	}
	if($sql[$num] == "")
	{
		$$slot = ("<a$hack onclick=\"saveData('book', '$num', '$date')\">Book Slot</a>");
	}
	elseif($sql[$num] == $_SESSION["kristall_username"] || $_SESSION["kristall_level"] == "Administrator" && $sql[$num] == "" || $_SESSION["kristall_level"] == "Super Administrator" && $sql[$num] == "" || $_SESSION["kristall_level"] == "Senior DJ" && $sql[$num] == "")
	{
		$$slot = ("<a$hack onclick=\"saveData('unbook', '$num', '$date');\">{$sql[$num]} - (Unbook)</a>");
	}
	else
	{
		$$slot = $sql[$num];
	}
}
echo("<table cellpadding=\"1\" cellspacing=\"0\" width=\"60%\" align=\"center\">
	<tr>
		<td align=\"center\" width=\"50%\" height=\"11\">
		<b><div class=\"centered\">Time</div></b></td>
		<td align=\"center\" width=\"50%\" height=\"11\">
		<b><div class=\"centered\">DJ Booked</div></b></td>
	</tr>");
for($num = 1; $num < 25; $num++)
{
 	$lol = $variable[$num];
 	echo("<tr>
		<td align=\"center\" width=\"50%\" height=\"11\">
		<div class=\"centered\">$printtimes[$num]</div></td>
		<td align=\"center\" width=\"50%\" height=\"11\"><div class=\"centered\"><span id=\"$num\">");
	echo $$lol;
	echo("</span></div></td></tr>");
}
if($_SESSION["kristall_level"] == "Super Administrator")
{
echo("<tr>
		<td align=\"center\" width=\"50%\" height=\"11\">
		<div class=\"centered\">Clear $date</div></td>
		<td align=\"center\" width=\"50%\" height=\"11\"><div class=\"centered\">
		<span id=\"$num\"><a$hack onclick=\"saveData('clear', '$num', '$date');\">Clear $date</a></span>
		</div></td></tr>
		");
}
echo("</table>");
}
?>