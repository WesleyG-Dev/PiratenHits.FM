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
<br>

  </div>
  <br>
<?php
echo("
		<span class=\"title\">Verzoekbox</span><br />Hier kun je alle verzoekjes die ingestuurd zijn zien. Ook kun je deze verwijderen
		<form action=\"?view=requests&act=delete\" method=\"post\">
		<br />

		<hr size=\"1\">

		<hr size=\"1\">");

switch($_GET["act"])
{
	case "delete":
		foreach($HTTP_POST_VARS as $key => $value)
		{
			if(!is_numeric($value))
			{}
			else
			{
				$sql = mysql_query("SELECT `dj` FROM `requests` WHERE `id` = '$value'");
				if(mysql_num_rows($sql) == "1")
				{
				$fetch = mysql_fetch_array($sql);
				if($fetch["dj"] == $_SESSION["kristall_username"] || $_SESSION["kristall_level"] == "Senior DJ" || $_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
				{
					mysql_query("DELETE FROM `requests` WHERE `id` = '$value'");
				}
				else
				{
					notice("Requests not deleted.");
					echo("One or more requests have not been deleted due do you not being the rightful owner of them<br />
					You are now being taken back to the main request line, if you'd prefer not to wait, please click <a href=\"?view=requests\">here</a><meta http-equiv=\"refresh\" content=\"2;url=?view=requests\">");
					endnotice();
				}
				}
			}
		}
		notice("Requests deleted.");
		echo("The requests you selected have been deleted<br />
		You are now being taken back to the main request line, if you'd prefer not to wait, please click <a href=\"?view=requests\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=requests\">");
		endnotice();
	break;

	case "reportform":
		$id = clean($_GET["id"]);
		$fetch = mysql_fetch_array(mysql_query("SELECT `dj` FROM `requests` WHERE `id` = '$id'"));
			notice("Weet je het zeker?");
			echo("Weet je zeker dat je dit verzoekje wilt verwijderen><br /><br />
			<a href=\"?view=requests&act=report&id=$id\">Ja, Verwijder dit verzoekje</a><br />
			<a href=\"?view=requests&act=viewform\">Nee. Breng me terug naar de verzoekbox.</a>");
			endnotice();


	break;

	case "report":
		$id = clean($_GET["id"]);
		$fetch = mysql_fetch_array(mysql_query("DELETE FROM `requests` WHERE `id` = '$id'"));

			mysql_query("DELETE FROM `requests` WHERE `id` = '$id'");
			notice("Deleted");
			echo("The Request you selected, with id tag $id, has been successfully deleted.<br /><br />
			If you'd prefer not to wait, please click <a href=\"?view=requests\">here</a><meta http-equiv=\"refresh\" content=\"8;url=?view=requests\">");
			endnotice();

	break;

	default:
		if(isset($_GET["type"]))
		{
			$type = clean($_GET["type"]);
		}
		else
		{
			$type = "all";
		}
		if($_SESSION["kristall_level"] == "DJ" && $type != "all")
		{
			$sql = mysql_query("SELECT * FROM `requests` WHERE `dj` = {$_SESSION["kristall_username"]} AND `type` = '$type' AND `reported` = '0' ORDER BY `id` ASC");
		}
		elseif($_SESSION["kristall_level"] == "DJ" && $type == "all")
		{
			$sql = mysql_query("SELECT * FROM `requests` WHERE `dj` = {$_SESSION["kristall_username"]} AND `reported` = '0' ORDER BY `id` ASC");
		}
		elseif($_SESSION["kristall_level"] != "DJ" && $type != "all")
		{
			$sql = mysql_query("SELECT * FROM `requests` WHERE `type` = '$type' ORDER BY `id` ASC");
		}
		else
		{
			$sql = mysql_query("SELECT * FROM `requests` ORDER BY `id` ASC");
		}
		while($fetch = mysql_fetch_array($sql))
		{
			if($_SESSION["kristall_level"] != "DJ")
			{
				$sentto = ("<b>To:</b> {$fetch["dj"]}");
				$ips = ("<b>IP:</b> {$fetch["ip"]}");
			}
			if($fetch["reported"] == "1")
			{
				if($_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
				{
					$colour = " background: #fff2f2;";
				}
				else
				{
					$colour = "";
				}
			}
			else
			{
				$colour = "";
			}
			echo('<table border="0" width="100%" cellpadding="3" style="border-collapse: collapse;'.$colour.'" cellspacing="2">
	<tr>
		<td width="2%" rowspan="2"><input type="checkbox" class="rofl" name="'.$fetch["id"].'" value="'.$fetch["id"].'"></td>
		<td colspan="2">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="35%" valign="top"><b>From:</b> '.$fetch["sender"].'</td>
				<td width="35%" valign="top"><b>Type:</b> '.$fetch["type"].'</td>
				<td width="30%">
				'. $ips .'
				</td>
			</tr>
			<tr>
				<td width="35%" valign="top"><b>Date:</b> '.$fetch["date"].'</td>
				<td width="35%" valign="top"><b>Delete:</b> <a href="?view=requests&act=reportform&id='.$fetch["id"].'">Klik om te verwijderen.</a></td>
				<td width="30%">
				'.$sentto.'
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td valign="top"><b>Message:</b></td>
		<td width="90%" valign="top">'.$fetch["message"].'</td>
	</tr>
</table><hr size="1">');
		}
		echo("</form>");
}
?>
