<?php
include("../connector.php"); // Requires config.php.
include("../functions.php");
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
if($_GET["act"] == "view")
{
	$color1 = "#f0f0f0"; 
	$color2 = "#FFFFFF"; 
	$rowcount = "0";  
	
	$countsql = mysql_query("SELECT * FROM `shoutbox`");
	$rows = mysql_num_rows($countsql);
	if($rows < 16)
	{
		$startfrom = "0";
		$countup = "15";
	}
	else 
	{
		$startfrom = $rows - 16;
		$countup = $rows + 1;
	}
	$sql = mysql_query("SELECT * FROM `shoutbox` ORDER BY `id` ASC LIMIT $startfrom, $countup");
	echo("<table width=\"100%\" cellspacing=\"0\">");
	while ($fetch = mysql_fetch_array($sql))
	{ 
	$rowcolour = ($rowcount % 2) ? $color1 : $color2;
	echo("
		<tr>
			<td bgcolor=\"$rowcolour\" width=\"10%\" valign=\"top\" style=\"padding-left: 3px\">{$fetch["name"]}:</td>
			<td bgcolor=\"$rowcolour\" width=\"90%\" valign=\"top\">{$fetch["message"]}</td>
		</tr>
		");
	$rowcount++;
	}
	echo("</table>");
}
elseif($_GET["act"] == "send")
{
	$name = clean($_SESSION["kristall_username"]);
	$level = clean($_SESSION["kristall_level"]);
	$time = time();
	if($level == "Administrator" || $level == "Super Administrator")
	{
		$name = "<span style=\"color: #941515\"><strong>$name</strong></span>";
		$message = clean($_POST["shout"], "nofilter", "dosmilies");
	}
	else 
	{
		$name = "<strong>$name</strong>";
		$message = clean($_POST["shout"], "", "dosmilies");
	}
	$antispamsql = mysql_query("SELECT `date` FROM `shoutbox` WHERE `ip` = '{$_SERVER["REMOTE_ADDR"]}' ORDER BY `id` DESC");
	if(mysql_num_rows($antispamsql) != "0")
	{
		$fetchantispam = mysql_fetch_array($antispamsql);
		$sumup = $time - $fetchantispam["date"];
		if($sumup < 7)
		{
			exit;
		}
	}
	if(strlen($message) > 80)
	{
		$message = substr($message, 0, 81);
	}
	if($message == "/prune" && $level == "Administrator" || $message == "/prune" && $level == "Super Administrator")
	{
		mysql_query("TRUNCATE TABLE `shoutbox`");
		mysql_query("INSERT INTO `shoutbox` (`name`, `message`, `ip`, `date`) VALUES ('$name', 'Shoutbox Pruned.', '{$_SERVER["REMOTE_ADDR"]}', '$time')");
	}
	else 
	{
	mysql_query("INSERT INTO `shoutbox` (`name`, `message`, `ip`, `date`) VALUES ('$name', '$message', '{$_SERVER["REMOTE_ADDR"]}', '$time')");
	}
	exit;
}
else 
{
	exit;
}
?>