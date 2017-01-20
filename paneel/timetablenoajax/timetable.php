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
if(isset($_GET["day"]))
{
	$date = clean($_GET["day"]);	
}
else
{
	$date = gmdate("l");
}
$sql = mysql_fetch_array(mysql_query("SELECT * FROM `timetable` WHERE `day` = '$date'"));
if($_GET["action"] == "book")
{
 	$slot = clean($_GET["slot"]);
	if($sql[$slot] == "" || $_SESSION["kristall_level"] == "Head DJ" || $_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
	{
		mysql_query("UPDATE `timetable` SET `$slot` = '{$_SESSION["kristall_username"]}' WHERE `day` = '$date'");
		notice("Timetable Slot Booked");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=timetable&day=$date\">Thanks for booking this slot, {$_SESSION["kristall_username"]}. You are now being taken back to the main booking page for $date<br /><br />If you'd prefer not to wait, please click <a href=\"?view=timetable&day=$date\">Here</a>");
		endnotice();
	}
	else
	{
		notice("You can't book that slot!");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=timetable&day=$date\">Sorry, it looks like that slot has already been taken. You are now being taken back to the main booking page for $date<br /><br />If you'd prefer not to wait, please click <a href=\"?view=timetable&day=$date\">Here</a>");
		endnotice();
	}
}
elseif($_GET["action"] == "unbook")
{
	$slot = clean($_GET["slot"]);
	if($sql[$slot] == $_SESSION["kristall_username"] || $_SESSION["kristall_level"] == "Head DJ" || $_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
	{
		mysql_query("UPDATE `timetable` SET `$slot` = '' WHERE `day` = '$date'");
		notice("Timetable Slot Unbooked");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=timetable&day=$date\">Thanks for unbooking this slot, {$_SESSION["kristall_username"]}. You are now being taken back to the main booking page for $date<br /><br />If you'd prefer not to wait, please click <a href=\"?view=timetable&day=$date\">Here</a>");
		endnotice();
	}
	else
	{
		notice("You can't unbook that slot!");
		echo("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"8;URL=?view=timetable&day=$date\">Sorry, it looks like that slot isn't yours, or is hasn't been taken yet! You are now being taken back to the main booking page for $date<br /><br />If you'd prefer not to wait, please click <a href=\"?view=timetable&day=$date\">Here</a>");
		endnotice();
	}
}
else
{
$variable = array("", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty", "twentyone", "twentytwo", "twentythree", "twentyfour"); // Holds a list of all the words thou shalt need, and OH YES, a new, highly efficient timetable.
$printtimes = array("", "00:01 - 01:00", "01:01 - 02:00", "02:01 - 03:00", "03:01 - 04:00", "04:01 - 05:00", "05:01 - 06:00", "06:01 - 07:00", "07:01 - 08:00", "08:01 - 09:00", "09:01 - 10:00", "10:01 - 11:00", "11:01 - 12:00", "12:01 - 13:00", "13:01 - 14:00", "14:01 - 15:00", "15:01 - 16:00",  "16:01 - 17:00", "17:01 - 18:00", "18:01 - 19:00", "19:01 - 20:00", "20:01 - 21:00", "21:01 - 22:00", "22:01 - 23:00", "23:01 - 00:00");
for($num = 1; $num < 25; $num++)
{
	$slot = $variable[$num];
	if($sql[$num] == "")
	{
		$$slot = ("<a href=\"?view=timetable&action=book&slot=$num&day=$date\">Book Slot</a>");
	}
	elseif($sql[$num] == $_SESSION["kristall_username"] || $_SESSION["kristall_level"] == "Administrator" && $sql[$num] == "" || $_SESSION["kristall_level"] == "Super Administrator" && $sql[$num] == "" || $_SESSION["kristall_level"] == "Senior DJ" && $sql[$num] == "")
	{
		$$slot = ("<a href=\"?view=timetable&action=unbook&slot=$num&day=$date\">{$sql[$num]} - (Unbook)</a>");
	}
	else
	{
		$$slot = $sql[$num];
	}
}
echo("<span class=\"title\">Timetable</span><br />Welcome to the timetable booking system for $date, if you'd prefer to book for another day, please click one of the links below.<hr size=\"1\"><center><a href=\"?view=timetable&day=Monday\">Mon</a> | <a href=\"?view=timetable&day=Tuesday\">Tue</a> | <a href=\"?view=timetable&day=Wednesday\">Wed</a> | <a href=\"?view=timetable&day=Thursday\">Thu</a> | <a href=\"?view=timetable&day=Friday\">Fri</a> | <a href=\"?view=timetable&day=Saturday\">Sat</a> | <a href=\"?view=timetable&day=Sunday\">Sun</a></center><hr size=\"1\"><table cellpadding=\"1\" cellspacing=\"0\" width=\"60%\" align=\"center\">
	<tr>
		<td align=\"center\" width=\"50%\" height=\"11\">
		<b>Time</b></td>
		<td align=\"center\" width=\"50%\" height=\"11\">
		<b>DJ Booked</b></td>
	</tr>");
for($num = 1; $num < 25; $num++)
{
 	$lol = $variable[$num];
 	echo("<tr>
		<td align=\"center\" width=\"50%\" height=\"11\">
		$printtimes[$num]</td>
		<td align=\"center\" width=\"50%\" height=\"11\">");
	echo $$lol;
	echo("</td></tr>");
}
echo("</table>");
}
?>