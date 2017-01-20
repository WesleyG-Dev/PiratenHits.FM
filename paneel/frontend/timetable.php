<?php
include("../functions.php");
include("../connector.php");
if(isset($_GET["day"]))
{
	$date = clean($_GET["day"]);	
}
else
{
	$date = gmdate("l");
}
$sql = mysql_fetch_array(mysql_query("SELECT * FROM `timetable` WHERE `day` = '$date'"));
$variable = array("", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty", "twentyone", "twentytwo", "twentythree", "twentyfour"); // Holds a list of all the words thou shalt need, and OH YES, a new, highly efficient timetable.
$printtimes = array("", "00:01 - 01:00", "01:01 - 02:00", "02:01 - 03:00", "03:01 - 04:00", "04:01 - 05:00", "05:01 - 06:00", "06:01 - 07:00", "07:01 - 08:00", "08:01 - 09:00", "09:01 - 10:00", "10:01 - 11:00", "11:01 - 12:00", "12:01 - 13:00", "13:01 - 14:00", "14:01 - 15:00", "15:01 - 16:00",  "16:01 - 17:00", "17:01 - 18:00", "18:01 - 19:00", "19:01 - 20:00", "20:01 - 21:00", "21:01 - 22:00", "22:01 - 23:00", "23:01 - 00:00");
for($num = 1; $num < 25; $num++)
{
	$slot = $variable[$num];
	$$slot = $sql[$num];
}
echo("<span class=\"title\">Timetable</span><br />Welcome to the timetable for $date, if you'd prefer to view another day, please click one of the links below.<hr size=\"1\"><center><a href=\"?day=Monday\">Mon</a> | <a href=\"?day=Tuesday\">Tue</a> | <a href=\"?day=Wednesday\">Wed</a> | <a href=\"?day=Thursday\">Thu</a> | <a href=\"?day=Friday\">Fri</a> | <a href=\"?day=Saturday\">Sat</a> | <a href=\"?day=Sunday\">Sun</a></center><hr size=\"1\"><table cellpadding=\"1\" cellspacing=\"0\" width=\"60%\" align=\"center\">
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
?>