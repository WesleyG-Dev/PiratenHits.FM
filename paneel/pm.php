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
/*********************************************************\
|* Private messaging, I did it with a switch case to     *|
|* Make it alot easier to read, it's all done in English *|
|* E.g. to send a message, it would use case "send":     *|
|* So if you do need to edit something, should be easy!  *|
|* Copyright Kristall-Services till whenever copyright   *|
|* Licensing is affected by stupid American laws and you *|
|* Have to pay for it! (loadsa years if it never changes)*|
|* Oh, and to find the form you want  e.g the form to    *|
|* Send a new message or whatever just do blahform where *|
|* Blah is the bit you want to find. E.g. sendform :)    *|
|*                                                       *|
|* NOTE:                                                 *|
|* With login("senior"); you can change so only admins   *|
|* or whatever can access, if you decide to make it only *|
|* Admins, then just change head to admin (Simple! ;] )  *|
|* make sure it's lowercase as anything else will error! *|
\*********************************************************/
$act = clean($_GET["act"], "nofilter");
if($act == "sendform")
{
	$doing = "Writing a Private Message";
}
elseif($act == "send")
{
	$doing = "Sending a Private Message";
}
elseif($act == "deleteform")
{
	$doing = "Confirming Your Choice to Delete a Private Message";
}
elseif($act == "delete")
{
	$doing = "Deleting a Private Message";
}
elseif($act == "reportform")
{
	$doing = "Confirming Your Choice to Report a Private Message";
}
elseif($act == "report")
{
	$doing = "Reporting a Private Message";
}
elseif($act == "massform")
{
	$doing = "Writing a Mass Message";
}
elseif($act == "mass")
{
	$doing = "Sending a Mass Message";
}
elseif($act == "viewform")
{
	$doing = "Choosing a Private Message to view";
}
elseif($act == "view")
{
	$doing = "Viewing a Private Message";
}
elseif($act == "viewall")
{
	$doing = "Viewing All Private Messages";
}
$nowdoing = ("<hr size=\"1\"><center><b>You are currently:</b> $doing</center><hr size=\"1\">");
if(!isset($_GET["replyto"]))
{
$userlist = "<select name=\"user\">\n";
$sql = mysql_query("SELECT `username` FROM `users` ORDER BY `username` DESC");
while($fetch = mysql_fetch_array($sql))
{
	$userlist .= "<option value=\"{$fetch["username"]}\">{$fetch["username"]}</option>\n";
}
$userlist .= "</select>";
}
else 
{
	$userlist = clean($_GET["replyto"]);
	$userlist = ("<input type=\"hidden\" value=\"$userlist\" name=\"user\">$userlist");
}
if(isset($_GET["replysubject"]))
{
	if(isset($_GET["replyto"]))
	{
	$subjectnow = clean($_GET["replysubject"]);
	$replysubject = " value=\"RE: $subjectnow\"";
	}
	else 
	{
	$subjectnow = clean($_GET["replysubject"]);
	$replysubject = " value=\"FWD: $subjectnow\"";
	}
}
if(isset($_GET["replymessage"]))
{
	$messagenow = clean($_GET["replymessage"]);
	$messagenow = preg_replace("/<br \/>/i", "\n", $messagenow);
	$replymessage = "\n\n-------------------\nOriginal Message:\n\n$messagenow";
}



echo("<span class=\"title\">Private Messaging</span>
<br />Welcome to the private messaging system, here you can send, delete and read your private messages, with the option to report a message to an administrator or a super administrator.<br />");
switch($act)
{
	case "sendform":
		echo("$nowdoing");
		echo("<br /><table cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" align=\"center\">
		<tr>
			<td valign=\"top\" align=\"center\">
			<form action=\"?view=pm&act=send\" method=\"post\">
			<fieldset>
				<legend><b>Send to</b></legend>
					The person you wish to send this message to<br /><br />
					$userlist
			</fieldset>
			<br />
			<fieldset>
				<legend><b>Subject</b></legend>
				The subject of this message<br /><br />
				<input type=\"text\" name=\"subject\" size=\"28\"$replysubject>
			</fieldset>
			<br />
			<fieldset>
				<legend><b>Message</b></legend>
				The message you  wish to send<br /><br />
				<textarea name=\"message\" rows=\"6\" cols=\"50\">$replymessage</textarea>
			</fieldset>
</td>
		</tr>
	</table><br />
	<center><input type=\"submit\" value=\"Send this Private Message\"></center></form><br />");
	break;
	
	case "send":
		echo("$nowdoing");
		$username = clean($_SESSION["kristall_username"]);
		$user = clean($_POST["user"]);
		$subject = clean($_POST["subject"]);
		$message = clean($_POST["message"]);
		$date = gmdate("d/m/Y - h:i:s");
		$date .= " GMT";
		if($user == "" || $subject == "" || $message == "")
		{
			notice("Message not sent");
			echo("Your message has not been sent because it is blank. You are now being taken back to the \"Send a Private Message\" form.<br /><br />If you'd prefer not to wait, please click <a href=\"?view=pm&act=sendform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=sendform\">");
			endnotice();
		}
		else 
		{
			mysql_query("INSERT INTO `pm` (`to`, `from`, `date`, `subject`, `message`) VALUES ('$user', '$username', '$date', '$subject', '$message')");
			notice("Message sent");
			echo("Your message has been sent to $user successfully.
			 You are now being taken back to the \"Send a Private Message\" form.
			 <br /><br />
			 If you'd prefer not to wait, please click <a href=\"?view=pm&act=sendform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=sendform\">");
			endnotice();
		}
	break;
	
	case "deleteform":
		echo("$nowdoing");
		$id = clean($_GET["id"]);
		$fetch = mysql_fetch_array(mysql_query("SELECT `to` FROM `pm` WHERE `id` = '$id'"));
		if($fetch["to"] == $_SESSION["kristall_username"] || $_SESSION["kristall_username"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
		{
			notice("Are You Sure?");
			echo("Are you sure you wish to delete this Private Message?<br /><br />
			<a href=\"?view=pm&act=delete&id=$id\">I'm Sure, Delete the Private Message</a><br />
			<a href=\"?view=pm&act=viewform\">I'm Not Sure, Take me Back</a>");
			endnotice();
		}
		else 
		{
			notice("That isn't your PM!");
			echo("That isn't your private message to view. You are now being taken back to the \"View Private Messages\" form.<br /><br />
			If you'd prefer not to wait, please click <a href=\"?view=pm&act=viewform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=viewform\">");
			endnotice();
		}
	break;
	
	case "delete":
		echo("$nowdoing");
		$id = clean($_GET["id"]);
		$fetch = mysql_fetch_array(mysql_query("SELECT `to` FROM `pm` WHERE `id` = '$id'"));
		if($fetch["to"] == $_SESSION["kristall_username"] || $_SESSION["kristall_username"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
		{
			mysql_query("DELETE FROM `pm` WHERE `id` = '$id'");
			notice("Private Message Deleted");
			echo("The Private Message you selected, with id tag $id, has been successfully deleted. You are now being taken back to the \"View Private Messages\" form.<br /><br />
			If you'd prefer not to wait, please click <a href=\"?view=pm&act=viewform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=viewform\">");
			endnotice();
		}
		else 
		{
			notice("That isn't your PM!");
			echo("That isn't your private message to view. You are now being taken back to the \"View Private Messages\" form.<br /><br />
			If you'd prefer not to wait, please click <a href=\"?view=pm&act=viewform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=viewform\">");
			endnotice();
		}
	break;
	
	case "reportform":
		echo("$nowdoing");
		$id = clean($_GET["id"]);
		$fetch = mysql_fetch_array(mysql_query("SELECT `to` FROM `pm` WHERE `id` = '$id'"));
		if($fetch["to"] == $_SESSION["kristall_username"] || $_SESSION["kristall_username"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
		{
			notice("Are You Sure?");
			echo("Are you sure you wish to report this Private Message?<br /><br />
			<a href=\"?view=pm&act=report&id=$id\">I'm Sure, Report the Private Message</a><br />
			<a href=\"?view=pm&act=viewform\">I'm Not Sure, Take me Back</a>");
			endnotice();
		}
		else 
		{
			notice("That isn't your PM!");
			echo("That isn't your private message to view. You are now being taken back to the \"View Private Messages\" form.<br /><br />
			If you'd prefer not to wait, please click <a href=\"?view=pm&act=viewform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=viewform\">");
			endnotice();
		}
	break;
	
	case "report":
		echo("$nowdoing");
		$id = clean($_GET["id"]);
		$fetch = mysql_fetch_array(mysql_query("SELECT `to` FROM `pm` WHERE `id` = '$id'"));
		if($fetch["to"] == $_SESSION["kristall_username"] || $_SESSION["kristall_username"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
		{
			mysql_query("UPDATE `pm` SET `reported` = '1' WHERE `id` = '$id'");
			notice("Private Message Reported");
			echo("The Private Message you selected, with id tag $id, has been successfully reported. An Administrator will view this PM and take action accordingly. You are now being taken back to the \"View Private Messages\" form.<br /><br />
			If you'd prefer not to wait, please click <a href=\"?view=pm&act=viewform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=viewform\">");
			endnotice();
		}
		else 
		{
			notice("That isn't your PM!");
			echo("That isn't your private message to view. You are now being taken back to the \"View Private Messages\" form.<br /><br />
			If you'd prefer not to wait, please click <a href=\"?view=pm&act=viewform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=viewform\">");
			endnotice();
		}
	break;
	
	case "viewform":
		echo("$nowdoing");
		$sql = mysql_query("SELECT * FROM `pm` WHERE `read` = '0' AND `reported` = '0' AND `to` = '{$_SESSION["kristall_username"]}' ORDER BY `id` DESC");
		echo("<span class=\"subtitle\">Unread Messages</span><br /><br />");
		if(mysql_num_rows($sql) == "0")
		{
			echo("You have no unread messages.<hr>");
		}
		else 
		{
		while($fetch = mysql_fetch_array($sql))
		{
			echo("<strong>From:</strong> {$fetch["from"]}<br />
					<strong>Subject:</strong> {$fetch["subject"]}<br />
					<strong>Actions:</strong> <a href=\"?view=pm&act=view&id={$fetch["id"]}\">View</a> - <a href=\"?view=pm&act=deleteform&id={$fetch["id"]}\">Delete</a> - <a href=\"?view=pm&act=reportform&id={$fetch["id"]}\">Report</a><hr>");
		}
		}
		$sql = mysql_query("SELECT * FROM `pm` WHERE `read` = '1' AND `reported` = '0' AND `to` = '{$_SESSION["kristall_username"]}' ORDER BY `id` DESC");
		echo("<span class=\"subtitle\">Read Messages</span><br /><br />");
		if(mysql_num_rows($sql) == "0")
		{
			echo("You have no read messages.<hr>");
		}
		else 
		{
		while($fetch = mysql_fetch_array($sql))
		{
			echo("<strong>From:</strong> {$fetch["from"]}<br />
					<strong>Subject:</strong> {$fetch["subject"]}<br />
					<strong>Actions:</strong> <a href=\"?view=pm&act=view&id={$fetch["id"]}\">View</a> - <a href=\"?view=pm&act=deleteform&id={$fetch["id"]}\">Delete</a> - <a href=\"?view=pm&act=reportform&id={$fetch["id"]}\">Report</a><hr>");
		}
		}
		if($_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
		{
			$sql = mysql_query("SELECT * FROM `pm` WHERE `reported` = '1' ORDER BY `id` DESC");
		echo("<span class=\"subtitle\">Reported Messages</span><br /><br />");
		if(mysql_num_rows($sql) == "0")
		{
			echo("There are no reported messages.<hr>");
		}
		else 
		{
		while($fetch = mysql_fetch_array($sql))
		{
			echo("<strong>From:</strong> {$fetch["from"]}<br />
					<strong>Subject:</strong> {$fetch["subject"]}<br />
					<strong>Actions:</strong> <a href=\"?view=pm&act=view&id={$fetch["id"]}\">View</a> - <a href=\"?view=pm&act=deleteform&id={$fetch["id"]}\">Delete</a><hr>");
		}
		}
		}
	break;
	
	case "view":
		echo("$nowdoing");
		$id = clean($_GET["id"]);
		if($_SESSION["kristall_level"] != "Administrator" && $_SESSION["kristall_level"] != "Super Administrator")
		{
			$admin = " `reported` = '0' AND";
		}
		$fetch = mysql_fetch_array(mysql_query("SELECT * FROM `pm` WHERE$admin `id` = '$id'"));
		if($fetch["to"] != $_SESSION["kristall_username"] && $admin = "")
		{
			notice("That isn't your PM!");
			echo("That isn't your private message to view. You are now being taken back to the \"View Private Messages\" form.<br /><br />
			If you'd prefer not to wait, please click here");
			endnotice();
		}
		else
		{
			mysql_query("UPDATE `pm` SET `read` = '1' WHERE `id` = '$id'");
			echo("<strong>From:</strong> {$fetch["from"]}<br />
					<strong>Subject:</strong> {$fetch["subject"]}<br />
					<strong>Date:</strong> {$fetch["date"]}<hr>
					<strong>Message:</strong> {$fetch["message"]}<hr>
					<strong>Actions:</strong> <a href=\"?view=pm&act=sendform&replyto={$fetch["from"]}&replysubject={$fetch["subject"]}&replymessage={$fetch["message"]}\">Reply</a> - <a href=\"?view=pm&act=sendform&replysubject={$fetch["subject"]}&replymessage={$fetch["message"]}\">Foward</a> - <a href=\"?view=pm&act=deleteform&id={$fetch["id"]}\">Delete</a> - <a href=\"?view=pm&act=reportform&id={$fetch["id"]}\">Report</a>");
		}
	break;
	
	case "massform":
		echo("$nowdoing");
		login("senior");
		echo("<br /><table cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" align=\"center\">
		<tr>
			<td valign=\"top\" align=\"center\">
			<form action=\"?view=pm&act=mass\" method=\"post\">
			<fieldset>
				<legend><b>Send to</b></legend>
					The group you wish to send this message to<br /><br />
					<select name=\"group\">
						<option value=\"Super Administrator\">Super Admins</option>
						<option value=\"Administrator\">Admins and Super Admins</option>
						<option value=\"Everyone\">Everyone</option>
					</select>
			</fieldset>
			<br />
			<fieldset>
				<legend><b>Subject</b></legend>
				The subject of this message<br /><br />
				<input type=\"text\" name=\"subject\" size=\"28\">
			</fieldset>
			<br />
			<fieldset>
				<legend><b>Message</b></legend>
				The message you  wish to send<br /><br />
				<textarea name=\"message\" rows=\"6\" cols=\"50\"></textarea>
			</fieldset>
</td>
		</tr>
	</table><br />
	<center><input type=\"submit\" value=\"Send this Mass Message\"></center></form><br />");
	break;
	
	case "mass":
		echo("$nowdoing");
		login("senior");
		$username = clean($_SESSION["kristall_username"]);
		$level = clean($_POST["group"]);
		$subject = clean($_POST["subject"]);
		$message = clean($_POST["message"]);
		$date = gmdate("d/m/Y - h:i:s");
		$date .= " GMT";
		if($username == "" || $level == "" || $subject == "" || $message == "")
		{
			notice("Message not sent");
			echo("Your message has not been sent because it is blank. You are now being taken back to the \"Send a Private Message\" form.<br /><br />If you'd prefer not to wait, please click <a href=\"?view=pm&act=sendform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=sendform\">");
			endnotice();
		}
		if($level == "Super Administrator")
		{
			$addon = " WHERE `level` = 'Super Administrator'";
		}
		elseif($level == "Administrator")
		{
			$addon = " WHERE `level` = 'Administrator' OR `level` = 'Super Administrator'";
		}
		else 
		{
			$addon = "";
		}
		$getusers = mysql_query("SELECT `username` FROM `users`$addon");
		$count = mysql_num_rows($getusers);
		while($fetchusers = mysql_fetch_array($getusers))
		{
			mysql_query("INSERT INTO `pm` (`to`, `from`, `date`, `subject`, `message`) VALUES ('{$fetchusers["username"]}', '$username', '$date', '$subject', '$message')");
		}
		notice("Message sent");
			echo("Your message has been sent to $count users successfully.
			 You are now being taken back to the \"Send a Private Message\" form.
			 <br /><br />
			 If you'd prefer not to wait, please click <a href=\"?view=pm&act=sendform\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=pm&act=sendform\">");
			endnotice();
	break;
}
?>