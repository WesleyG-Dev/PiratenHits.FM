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
$userlevel = "<select name=\"level\">";
$userlevel .= "<option value=\"DJ\">Standard User - \"DJ\"</option>\n";
$userlevel .= "<option value=\"Senior DJ\">Advanced User - \"Senior DJ\"</option>\n";
if($_SESSION["kristall_level"] == "Super Administrator")
{
	$userlevel .= "<option value=\"Administrator\">System Administrator - \"Admin\"</option>\n";
}
$userlevel .= "</select>";
echo("<span class=\"title\">User Management</span><br />
		Welcome to the user management system, here you can add, delete and edit any users on the panel (Administrators can not edit super administrators)<hr>");
switch($_GET["act"])
{
	case "addform":
		echo("
		<form action=\"?view=usermanage&act=add\" method=\"post\"><table cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" align=\"center\">
		<tr>
			<td valign=\"top\" align=\"center\">
			<fieldset>
				<legend><b>Username</b></legend>
					The username you wish to create<br /><br />
					<input type=\"text\" name=\"username\" size=\"28\">
			</fieldset>
			<br />
			<fieldset>
				<legend><b>Password</b></legend>
					The password you wish to use with this account<br /><br />
					<input type=\"text\" name=\"password\" size=\"28\">
			</fieldset>
			<br />
			<fieldset>
				<legend><b>Email</b></legend>
					The email you wish to use with this account<br /><br />
					<input type=\"text\" name=\"email\" size=\"28\">
			</fieldset>
			<br />
			<fieldset>
				<legend><b>User Level</b></legend>
					The user level you wish to give this account<br /><br />
					$userlevel
			</fieldset>
			<br />
			</td>
		</tr>
	</table><br />
	<center><input type=\"submit\" value=\"Add This User\"></center></form><br />");
	break;
	
	case "add":
		$username = clean($_POST["username"], "nofilter");
		$username = ucfirst(strtolower($username));
		$level = clean($_POST["level"]);
		$email = clean($_POST["email"]);
		$password = clean($_POST["password"], "nofilter");
		$passwordencrypted = encrypt($password);
		if($username == "" || $level == "" || $email == "" || $password == "")
		{
			notice("Message not sent");
			echo("The user has not been created because you have left a field blank. You are now being taken back to the \"Add User\" form.<br /><br />If you'd prefer not to wait, please click <a href=\"?view=usermanage&act=addform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=usermanage&act=addform\">");
			endnotice();
		}
		$sql = mysql_query("SELECT `username` FROM `users` WHERE `username` = '$username'");
		if(mysql_num_rows($sql) != "0")
		{
			notice("User already exists!");
			echo("The user has not been created because the username ($username) is in use. You are now being taken back to the \"Add User\" form.<br /><br />If you'd prefer not to wait, please click <a href=\"?view=usermanage&act=addform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=usermanage&act=addform\">");
			endnotice();
		}
		mysql_query("INSERT INTO `users` (`username`, `level`, `msn`, `password`) VALUES ('$username', '$level', '$email', '$passwordencrypted')");
		notice("User Added");
			echo("The user ($username) with the password ($password) has been successfully added. You are now being taken back to the \"Add User\" form.<br /><br />If you'd prefer not to wait, please click <a href=\"?view=usermanage&act=addform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=usermanage&act=addform\">");
			endnotice();
	break;
	
	case "choosedit":
		if($_SESSION["kristall_level"] == "Administrator")
		{
			$extra = " WHERE `level` = 'Senior DJ' OR `level` = 'DJ'";
		}
		elseif($_SESSION["kristall_level"] == "Super Administrator")
		{
			$extra = " WHERE `level` = 'Senior DJ' OR `level` = 'DJ' OR `level` = 'Administrator' OR `level` = 'Super Administrator'";
		}
		$sql = mysql_query("SELECT `username` FROM `users`$extra ORDER BY `username` ASC");
		$douser = "<select name=\"username\">\n";
		while($fetch = mysql_fetch_array($sql))
		{
			$douser .= "<option value=\"{$fetch["username"]}\">{$fetch["username"]}</option>\n";
		}
		$douser .= "</select>";
		echo("
		<table cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" align=\"center\">
			<tr>
				<td valign=\"top\" align=\"center\">
				<form action=\"?view=usermanage&act=doedit\" method=\"post\">
					<fieldset>
						<legend><b>Username</b></legend>
							Please choose the username you wish to edit<br />
							$douser
					</fieldset>
					<br />
					<input type=\"submit\" value=\"Edit this user\">
				</form>
				</td>
			</tr>
		</table>");
	break;
	
	case "doedit":
		$username = clean($_POST["username"]);
		$sql = mysql_query("SELECT * FROM `users` WHERE `username` = '$username'");
		$num = mysql_num_rows($sql);
		$fetch = mysql_fetch_array($sql);
		$chooselevel = "<select name=\"level\">";
		$chooselevel .= "<option value=\"{$fetch["level"]}\" selected>{$fetch["level"]}</option>";
		if($_SESSION["kristall_level"] == "Super Administrator")
		{
			$chooselevel .= "<option value=\"Administrator\">Administrator</option>";
		}
			$chooselevel .= "<option value=\"Senior DJ\">Senior DJ</option>";
			$chooselevel .= "<option value=\"DJ\">Normal DJ</option>";
			$chooselevel .= "</select>";
			$chooselevel = str_replace("<option value=\"{$fetch["level"]}\">{$fetch["level"]}</option>", "", $chooselevel);
		
		$chooseban = "<select name=\"ban\">";
		$chooseban .= "<option value=\"{$fetch["ban"]}\" selected>{$fetch["ban"]}</option>";
		$chooseban .= "<option value=\"Yes\">Yes</option>";
		$chooseban .= "<option value=\"No\">No</option>";
		$chooseban .= "</select>";
		$chooseban = str_replace("<option value=\"{$fetch["ban"]}\">{$fetch["ban"]}</option>", "", $chooseban);
		if($num == "0")
		{
			notice("Access Denied.");
			echo("The person you have tried to edit ($username) is not registered at this panel.<br />You are now being taken back to the homepage.
				<meta http-equiv=\"refresh\" content=\"8;url=?view=welcome\">");
			endnotice();
		}
		if($fetch["level"] == "Administrator" && $_SESSION["kristall_level"] != "Super Administrator")
		{
			notice("Access Denied.");
			echo("You are not of the sufficient level to edit this user, the leveling system is as follows:<br />
			Super administrators can edit:<br />
			&nbsp;&nbsp;&nbsp;- Administrators<br />
			&nbsp;&nbsp;&nbsp;- Senior / Normal DJ's<hr>
			Administrators can edit:<br />
			&nbsp;&nbsp;&nbsp;- Senior / Normal DJ's<hr>You are now being taken away to the homepage (You have no choice :] )<br /><meta http-equiv=\"refresh\" content=\"8;url=?view=welcome\">");
			endnotice();
		}
		echo("
		<table cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" align=\"center\">
			<tr>
				<td valign=\"top\" align=\"center\">
				<form action=\"?view=usermanage&act=edit\" method=\"post\">
					<fieldset>
						<legend><b>Username</b></legend>
							The person you are currently editing is:<br />
							<strong>$username</strong>
							<input type=\"hidden\" name=\"username\" value=\"{$fetch["username"]}\">
					</fieldset>
					<br />
					<fieldset>
						<legend><b>Password</b></legend>
							This users new password (Leave blank for no change):<br />
							<input type=\"text\" name=\"password\" size=\"28\">
					</fieldset>
					<br />
					<fieldset>
						<legend><b>Email</b></legend>
							This users email / MSN:<br />
							<input type=\"text\" name=\"email\" value=\"{$fetch["msn"]}\" size=\"28\">
					</fieldset>
					<br />
					<fieldset>
						<legend><b>User Level:</b></legend>
							This users level:<br />
							$chooselevel
					</fieldset>
					<br />
					<fieldset>
						<legend><b>Skype:</b></legend>
							This users Skype Address:<br />
							<input type=\"text\" name=\"skype\" value=\"{$fetch["skype"]}\" size=\"28\">
					</fieldset>
					<br />
					<fieldset>
						<legend><b>Warnings:</b></legend>
							This users Warning Level:<br />
							<input type=\"text\" name=\"warning\" value=\"{$fetch["warning"]}\" size=\"28\">
					</fieldset>
					<br />
					<fieldset>
						<legend><b>Ban:</b></legend>
							Is / should this user be banned:<br />
							$chooseban
					</fieldset>
					<br />
					<input type=\"submit\" value=\"Edit this user\">
				</form>
				</td>
			</tr>
		</table>");
	break;
	
	/*
	omfg, I was like watching the C&C previews the other day right
	and I sat there and was like OMFG C00L!!11one
	so I need to go out at some point and buy it, better add that to the checklist
	and a new pc faster than aarons, i fucking hate him for it
	cpu needed: some crazy amd dual core thingy
	ram needed: more than 2gb
	graphics card needed: more than 512mb
	
	Fuck that, I'd rather just laugh at him when it melts or something
	*/
	
	case "edit":
		$username = clean($_POST["username"]);
		if($username == "")
		{
			notice("Blank Username.");
			echo("You have tried to access this page without specifying a username, this means there is no user to edit.
			<hr>You are now being taken away to the homepage (You have no choice :] )<br /><meta http-equiv=\"refresh\" content=\"8;url=?view=welcome\">");
			endnotice();
		}
		else 
		{
			$password = clean($_POST["password"], "nofilter");
			if($password == "")
			{
				$addon = "";
			}
			else 
			{
				$password = encrypt($password);
				$addon = ", `password` = '$password'";
			}
			$email = clean($_POST["email"]);
			$level = clean($_POST["level"]);
			$skype = clean($_POST["skype"]);
			$warnings = clean($_POST["warning"]);
			$ban = clean($_POST["ban"]);
			if(is_numeric($warnings))
			{
				$warning = mysql_fetch_array(mysql_query("SELECT `totalwarnings` FROM `settings`"));
				if($warning["totalwarnings"] < $warnings)
				{
					$warnings = $warning["totalwarnings"];
				}
			}
			else 
			{
				$warnings = "0";
			}
			mysql_query("UPDATE `users` SET `msn` = '$email', `skype` = '$skype', `warning` = '$warnings', `ban` = '$ban'$addon WHERE `username` = '$username'");
			notice("User Updated!");
			echo("The user ($username) has been successfully updated with the following details<br />
			<strong>Email / MSN:</strong> $email<br />
			<strong>Skype Address:</strong> $skype<br />
			<strong>User Level:</strong> $level<br />
			<strong>Warnings:</strong> $warnings<br />
			<strong>Banned:</strong> $ban<hr>
			You are not being taken back to the edit a user form, if you'd prefer not to wait, please click here. No wait, forget that, actually why not try clicking one of these:<br />
			Here<br />
			<u>Here?</u><br />
			<i>Here!!!</i><br />
			<a href=\"?view=usermanage&act=choosedit\"><span style=\"color: #EEEEEE\">Nah, click here :)</span></a><br />
			Or maybe you could click here?<br /><br />
			Perhaps you've already been past it ;)<br />");
			endnotice();
		}
	break;
	
	case "deleteform":
		if($_SESSION["kristall_level"] == "Administrator")
		{
			$extra = " WHERE `level` = 'Senior DJ' OR `level` = 'DJ'";
		}
		elseif($_SESSION["kristall_level"] == "Super Administrator")
		{
			$extra = " WHERE `level` = 'Senior DJ' OR `level` = 'DJ' OR `level` = 'Administrator'";
		}
		$sql = mysql_query("SELECT `username` FROM `users`$extra ORDER BY `username` ASC");
		$douser = "<select name=\"username\">\n";
		while($fetch = mysql_fetch_array($sql))
		{
			$douser .= "<option value=\"{$fetch["username"]}\">{$fetch["username"]}</option>\n";
		}
		$douser .= "</select>";
		echo("<table cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" align=\"center\">
			<tr>
				<td valign=\"top\" align=\"center\">
				<form action=\"?view=usermanage&act=confirmdelete\" method=\"post\">
					<fieldset>
						<legend><b>Username</b></legend>
							Please choose the username you wish to delete<br />
							$douser
					</fieldset>
					<br />
					<input type=\"submit\" value=\"Delete this user\">
				</form>
				</td>
			</tr>
		</table>");
	break;
	
	case "confirmdelete":
		$username = clean($_POST["username"]);
		if($username == "")
		{
			notice("Blank Username.");
			echo("You have tried to access this page without specifying a username, this means there is no user to edit.
			<hr>You are now being taken back to the delete users form.<br /><meta http-equiv=\"refresh\" content=\"8;url=?view=usermanage&act=deleteform\">");
			endnotice();
		}
		notice("Are You Sure?");
		echo("Are you sure you wish to delete this user ($username)?<br /><br />
		<a href=\"?view=usermanage&act=delete&user=$username\">Yes I'm sure, delete $username</a><br />
		<a href=\"?view=usermanage&act=choosedelete\">No I'm not sure, take me back to the form!</a>");
		endnotice();
	break;
	
	case "delete":
		$username = clean($_GET["user"]);
		$sql = mysql_fetch_array(mysql_query("SELECT `level` FROM `users` WHERE `username` = '$username'"));
		if($username == "" || $sql["level"] == "Super Administrator")
		{
			notice("Blank Username.");
			echo("You have tried to access this page without specifying a username, this means there is no user to edit.
			<hr>You are now being taken back to the delete users form.<br /><meta http-equiv=\"refresh\" content=\"8;url=?view=usermanage&act=deleteform\">");
			endnotice();
		}
		mysql_query("DELETE FROM `users` WHERE `username` = '$username'");
		notice("User Added");
		echo("The user ($username) has been successfully deleted. You are now being taken back to the \"Delete User\" form.<br /><br />If you'd prefer not to wait, please click <a href=\"?view=usermanage&act=deleteform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=usermanage&act=deleteform\">");
		endnotice();
	break;
}
?>