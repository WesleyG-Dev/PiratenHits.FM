<span class="title">System Settings</span><br />
Welcome ot the system settings page, here you can edit all the options available on the panel, ranging from such things as the bbcode system, to the amount of warnings you  want users to be able to have.<hr>
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
login("admin");
if($_GET["act"] == "update")
{
	$bbcode = clean($_POST["bbcode"]);
	$warnings = clean($_POST["amount"]);
	$filter = clean($_POST["filter"], "nofilter");
	$welcome = clean($_POST["welcome"]);
	$site = clean($_POST["site"]);
	if($bbcode == "")
	{
		$code1 = "'Yes',";
	}
	else 
	{
		$code1 = "'$bbcode',";
	}
	if(!is_numeric($warnings))
	{
		$code2 = "'3',";
	}
	else 
	{
		$code2 = "'$warnings',";
	}
	if($filter == "")
	{
		$code3 = "'fuck shit ass wank cunt bitch piss',";
	}
	else 
	{
		$code3 = "'$filter',";
	}
	if($site == "")
	{
		$code4 = "'Kristall-Panel',";
	}
	else 
	{
		$code4 = "'$site',";
	}
	if($welcome == "")
	{
		$code5 = "'Welcome to Kristall-Panel, www.kristall-services.com'";
	}
	else 
	{
		$code5 = "'$welcome'";
	}
	$code3 = str_replace(" ", ";", $code3);
	$code3 = str_replace(";;", ";", $code3);
	mysql_query("UPDATE `settings` SET `bbcode` = $code1 `totalwarnings` = $code2 `filter` = $code3 `sitename` = $code4 `welcome` = $code5");
	echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=settings\">Thanks for changing the settings, they have been successfully updated. You are now being taken back to the \"Update Settings\" form<br /><br />If you'd prefer not to wait, please click <a href=\"?view=settings\">Here</a>");
	
}
else 
{
	$fetch = mysql_fetch_array(mysql_query("SELECT * FROM `settings`"));
	$dobbcode = $fetch["bbcode"];
	$dowarnings = $fetch["totalwarnings"];
	$dofilter = $fetch["filter"];
	$dofilter = str_replace(";", " ", $dofilter);
	$dowelcome = reversebb($fetch["welcome"]);
	$dosite = $fetch["sitename"];
	$doip = $fetch["ip"];
	$doport = $fetch["port"];
	$dopass = $fetch["pass"];
	$dobbcode2 = "<option value=\"$dobbcode\" selected>$dobbcode</option>
						<option value=\"Yes\">Yes</option>
						<option value=\"No\">No</option>";
	$dobbcode2 = str_replace("<option value=\"$dobbcode\">$dobbcode</option>", "", $dobbcode2);
?>
<div align="center">
<form action="?view=settings&act=update" method="post">
	<table cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td valign="top" align="center">
		<br />
		<fieldset>
			<legend>
				<b>BBCode Parsing</b>
			</legend>
					Do you wish to allow users to use BBCode in the panel, E.g. [b]<strong>bold text</strong>[/b]<br />
					<select name="bbcode">
						<?php echo("$dobbcode2"); ?>
					</select>
		</fieldset>
	<br />
		<fieldset>
			<legend>
				<b>Total Warnings</b>
			</legend>
					The total amount of warnings you wish to allow before the user is automatically banned. (Numerical only)<br />
					<input type="text" name="amount" value="<?php echo("$dowarnings"); ?>" />
		</fieldset>
		<br />
		<fieldset>
			<legend>
				<b>Word Filter</b>
			</legend>
					Please enter new words, or remove words, simply seperate them with a space, nothing else.<br /><strong>Note:</strong> It is highly recommended NOT to add "ass" to the filter as it could affect the navigation of the panel.<br />
					<textarea cols="70" rows="4" name="filter"><?php echo("$dofilter"); ?></textarea>
		</fieldset>
		<br />
		<fieldset>
			<legend>
				<b>Welcome Message</b>
			</legend>
					This is the message that appears on the panel as soon as you login, please use nothing but bbcode, new lines are automatically converted and all html is removed :)<br />
					<textarea cols="70" rows="4" name="welcome"><?php echo("$dowelcome"); ?></textarea>
		</fieldset>
		<br />
		<fieldset>
			<legend>
				<b>Site Name</b>
			</legend>
					This is the site name that appear in the title and other various locations in the page.<br />
					<input type="text" size="28" name="site" value="<?php echo("$dosite"); ?>"/>
		</fieldset>
</td>
		</tr>
	</table>
	<br />
	<input type="submit" value="Update System Settings"></form></div>
<?php
}
?>