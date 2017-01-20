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
?>
<span class="title">User List</span><br />
Here you can find the MSN / email addresses and Skype addresses of users currently registered on the panel!<hr>
<?php
echo("<span class=\"subtitle\">Administrators</span><br />");
$sql = mysql_query("SELECT `username`, `skype`, `msn` FROM `users` WHERE `level` = 'Administrator' OR `level` = 'Super Administrator' ORDER BY `username` DESC");
echo("<div align=\"center\"><br />
		<table width=\"90%\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td width=\"30%\"><center><strong>Username</strong></center></td>
			<td width=\"30%\"><center><strong>MSN</strong></center></td>
			<td width=\"30%\"><center><strong>Skype</strong></center></td>
		</tr>"); // Setup the table.
while($fetch = mysql_fetch_array($sql))
{
	$username = $fetch["username"];
	$msn = $fetch["msn"];
	if($msn == "")
	{
		$msn = "<span style=\"color: #C0C0C0;\">Not set.</span>";
	}
	$skype = $fetch["skype"];
	if($skype == "")
	{
		$skype = "<span style=\"color: #C0C0C0;\">Not set.</span>";
	}
	echo("<tr>
			<td width=\"30%\"><center>$username</center></td>
			<td width=\"30%\"><center>$msn</center></td>
			<td width=\"30%\"><center>$skype</center></td>
		  </tr>");
}
echo("</table></div><hr>");
echo("<span class=\"subtitle\">Senior DJ's</span><br />");
$sql = mysql_query("SELECT `username`, `skype`, `msn` FROM `users` WHERE `level` = 'Senior DJ' ORDER BY `username` DESC");
echo("<div align=\"center\"><br />
		<table width=\"90%\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td width=\"30%\"><center><strong>Username</strong></center></td>
			<td width=\"30%\"><center><strong>MSN</strong></center></td>
			<td width=\"30%\"><center><strong>Skype</strong></center></td>
		</tr>"); // Setup the table.
while($fetch = mysql_fetch_array($sql))
{
	$username = $fetch["username"];
	$msn = $fetch["msn"];
	if($msn == "")
	{
		$msn = "<span style=\"color: #C0C0C0;\">Not set.</span>";
	}
	$skype = $fetch["skype"];
	if($skype == "")
	{
		$skype = "<span style=\"color: #C0C0C0;\">Not set.</span>";
	}
	echo("<tr>
			<td width=\"30%\"><center>$username</center></td>
			<td width=\"30%\"><center>$msn</center></td>
			<td width=\"30%\"><center>$skype</center></td>
		  </tr>");
}
echo("</table></div><hr>");
echo("<span class=\"subtitle\">DJ's</span><br />");
$sql = mysql_query("SELECT `username`, `skype`, `msn` FROM `users` WHERE `level` = 'DJ' ORDER BY `username` DESC");
echo("<div align=\"center\"><br />
		<table width=\"90%\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td width=\"30%\"><center><strong>Username</strong></center></td>
			<td width=\"30%\"><center><strong>MSN</strong></center></td>
			<td width=\"30%\"><center><strong>Skype</strong></center></td>
		</tr>"); // Setup the table.
while($fetch = mysql_fetch_array($sql))
{
	$username = $fetch["username"];
	$msn = $fetch["msn"];
	if($msn == "")
	{
		$msn = "<span style=\"color: #C0C0C0;\">Not set.</span>";
	}
	$skype = $fetch["skype"];
	if($skype == "")
	{
		$skype = "<span style=\"color: #C0C0C0;\">Not set.</span>";
	}
	echo("<tr>
			<td width=\"30%\"><center>$username</center></td>
			<td width=\"30%\"><center>$msn</center></td>
			<td width=\"30%\"><center>$skype</center></td>
		  </tr>");
}
echo("</table></div><hr>");
?>