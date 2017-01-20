
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
if($_GET["act"] == "update")
{
	$ip = clean($_POST["ip"]);
	$port = clean($_POST["port"]);
	$pass = clean($_POST["pass"]);
	if($ip == "")
	{
		$ip = "http://server-66.stream-server.nl";
	}
	if(!is_numeric($port) || $port == "")
	{
		$port = "8844";
	}
	if($pass == "")
	{
		$pass = "default";
	}

	mysql_query("UPDATE `radioinfo` SET `ip` = '$ip', `port` = '$port', `pass` = '$pass'");
	notice("Radio Info Updated");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=radioinfo\">Thanks for updating the Radio Info, all users on the panel will be updated with these details. You are now being taken back to the Radio info form<br /><br />If you'd prefer not to wait, please click <a href=\"?view=radioinfo\">Here</a>");
		endnotice();
}
else
{
	notice("Update Radio Information");
	echo("Welcome to the update radio information page, here you can update the current SHOUTcast radio information for users of your panel to see and use when connecting to the radio.");
	$sql = mysql_fetch_array(mysql_query("SELECT * FROM `radioinfo`"));
	$ip = " value=\"{$sql["ip"]}\"";
	$port = " value=\"{$sql["port"]}\"";
	$pass = " value=\"{$sql["pass"]}\"";
?>

<form action="?view=radioinfo&act=update" method="post">
<hr><div align="center">
	<table cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td valign="top" align="center">
<fieldset>
	<legend><b>Radio IP</b></legend>
		The IP of the SHOUTcast radio<br />
	<input type="text" name="ip" size="28"<?php echo("$ip"); ?>>
</fieldset>
<br />
<fieldset>
	<legend><b>Radio Port</b></legend>
		The Port of the SHOUTcast radio<br />
	<input type="text" name="port" size="28"<?php echo("$port"); ?>>
</fieldset>
<br />
<fieldset>
	<legend><b>Radio Password</b></legend>
		The Password of the SHOUTcast radio<br />
	<input type="text" name="pass" size="28"<?php echo("$pass"); ?>>
</fieldset>
</td>
		</tr>
	</table>
</div>
<p align="center">
<input type="submit" name="submit" value="Update Radio Info"></p></form>
<?php
}
?>
