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
<?php
if(isset($_POST["djsays"]))
{
	if($_POST["djsays"] == "")
	{
		notice("You've left a field blank!");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=djsays\">You haven't filled all the required fields in, please fill these in:<br />> DJ Says Message<br />You are now being taken back to the DJ Says form.<br /><br />If you'd prefer not to wait, please click <a href=\"?view=djsays\">Here</a>");
		endnotice();
	}
	else
	{
		$djsays = clean($_POST["djsays"]);
		mysql_query("UPDATE `djsays` SET `username` = '{$_SESSION["kristall_username"]}', `shoutout` = '$djsays'");
		notice("DJ Says Updated!");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=djsays\">Thanks for filling in the DJ Says message, all users on the site will be updated with this message. You are now being taken back to the DJ Says form<br /><br />If you'd prefer not to wait, please click <a href=\"?view=djsays\">Here</a>");
		endnotice();
	}
}
else
{
?>
<form action="" method="post">
<span class="title">DJ Says</span><br />
Welcome to the DJ Says page, here you can post a "DJ Says" message for the public to view!<hr><div align="center">
	<table cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td valign="top" align="center">
<fieldset>
<legend><b>Your Username</b></legend>
<?php echo("{$_SESSION["kristall_username"]}"); ?></fieldset>
		<br />
		<fieldset>
<legend><b>Message</b></legend>
		The message you wish to be displayed<br>
<input type="text" name="djsays" size="28"></fieldset>
</td>
		</tr>
	</table>
</div>
<p align="center">
<input type="submit" name="submit" value="Post DJ Says" class="formitem"></p></form>
<?php
echo("<span class=\"subtitle\">Current DJ Says</span><br />");
djsays();
}
?>