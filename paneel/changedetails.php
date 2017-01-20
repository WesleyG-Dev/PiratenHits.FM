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
<span class="title">Change Your Details</span><br />
Welcome to the Change Your Details page, here you can update your msn or skype address for people to see on the panel (Staff only) and also, should you suspect your account is insecure, ban your account by entering your secret 4 digit code.<hr>
<?php
$username = clean($_SESSION["kristall_username"]);
if($_GET["act"] == "changedetails")
{
	$msn = clean($_POST["msn"]);
	$skype = clean($_POST["skype"]);
	mysql_query("UPDATE `users` SET `skype` = '$skype', `msn` = '$msn' WHERE `username` = '$username'");
	notice("Details Updated!");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=changedetails\">Thanks for filling in your details, they have been successfully updated. You are now being taken back to the \"change Your Details\" form<br /><br />If you'd prefer not to wait, please click <a href=\"?view=changedetails\">Here</a>");
	endnotice();
}
elseif($_GET["act"] == "lockout")
{
	$pin = clean($_POST["pin"]);
	$sql = mysql_num_rows(mysql_query("SELECT `pin` FROM `users` WHERE `pin` = '$pin' AND `username` = '$username'"));
	if($sql == "1")
	{
		mysql_query("UPDATE `users` SET `ban` = 'Yes' WHERE `username` = '$username'");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL=?view=welcome\">");
	}
	else
	{
		notice("Incorrect PIN");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=changedetails\">The PIN number you provided for this account is incorrect, you have <b>NOT</b> been banned.");
	}
}
else
{
	$sql = mysql_fetch_array(mysql_query("SELECT `skype`, `msn` FROM `users` WHERE `username` = '$username'"));
	$msn = " value=\"{$sql["msn"]}\"";
	$skype = " value=\"{$sql["skype"]}\"";
?>
<span class="subtitle">Update Contact Details</span>
<form action="?view=changedetails&act=changedetails" method="post">
<div align="center">
	<table cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td valign="top" align="center">
		<br />
		<fieldset>
<legend><b>MSN Messenger Address</b></legend>
		Your MSN Messenger address<br>
<input type="text" name="msn" size="28"<?php echo("$msn"); ?>></fieldset>
<br />
<fieldset>
<legend><b>Skype Address</b></legend>
		Your Skype address (Usually email or username)<br>
<input type="text" name="skype" size="28"<?php echo("$skype"); ?>></fieldset>
</td>
		</tr>
	</table><br />
<input type="submit" value="Update Contact Details">
</form></div><br /><hr size="1"><br />
<span class="subtitle">Lockout Your Account</span><br />
This will automatically ban your account with immediate effect, so only use it if you think someone else might be able to get into your account! (You entered your 4 digit PIN when you entered the panel for the first time)<br /><br />
<form action="?view=changedetails&act=lockout" method="post">
<div align="center">
	<table cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td valign="top" align="center">
		<br />
		<fieldset>
<legend><b>PIN Number</b></legend>
		Your secret PIN number.<br>
<input type="text" name="pin" size="28"></fieldset>
</td>
		</tr>
	</table><br />
<input type="submit" value="Lockout your account">
</form></div>
<?php
}
?>
<br><br>

  </div>
