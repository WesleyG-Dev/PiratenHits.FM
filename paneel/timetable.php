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
<script type="text/javascript">
var xmlHttp

function GetXmlHttpObject(){
	var objXMLHttp=null
	if (window.XMLHttpRequest){
		objXMLHttp=new XMLHttpRequest()
	}else if (window.ActiveXObject){
		objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
	}
	return objXMLHttp
}

function GetShouts(method){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	}
	stfu = method
	var url="timetabledo.php?day=" + method
	xmlHttp.open("GET",url,true)
	xmlHttp.onreadystatechange = function () {
		if (xmlHttp.readyState == 4) {
			if (xmlHttp.status == 200) {
				document.getElementById("timetable").innerHTML = xmlHttp.responseText;
			}
		}
	};
	xmlHttp.send(null);
}
GetShouts();
setInterval("GetShouts(stfu)" ,1500);
function saveData(what, thething, theday){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	}

	xmlHttp.open('POST', 'timetabledo.php');
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.send('action=' + what + '&slot=' + thething + '&day=' + theday);
	if (what == "book") {
		document.getElementById(thething).innerHTML = "<b>Booking....</b>"
	}
	else if (what == "clear") {
		document.getElementById(thething).innerHTML = "<b>Clearing....</b>"
	}
	else {
		document.getElementById(thething).innerHTML = "<b>Unbooking....</b>"
	}
}
function ReleaseBold(theday) {
	document.getElementById(theday).innerHTML = "<b>" + theday + "</b>"
	document.getElementById("date").innerHTML = "<b>" + theday + "</b>"
}
function ResetBold(dayone, daytwo, daythree, dayfour, dayfive, daysix) {
	document.getElementById(dayone).innerHTML = "</b>" + dayone + "<b>"
	document.getElementById(daytwo).innerHTML = "</b>" + daytwo + "<b>"
	document.getElementById(daythree).innerHTML = "</b>" + daythree + "<b>"
	document.getElementById(dayfour).innerHTML = "</b>" + dayfour + "<b>"
	document.getElementById(dayfive).innerHTML = "</b>" + dayfive + "<b>"
	document.getElementById(daysix).innerHTML = "</b>" + daysix + "<b>"
}
function SetLoad(day) {
	document.getElementById("timetable").innerHTML = "<center><b><font size=\"3\">Loading " + day + "</font></b></center>"
}
</script>
<?php
if(preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"]))
{
	$hack = " href=\"#\"";
}
?>
<span class="title">Timetable</span><br />
Welcome to the timetable booking system for <span id="date"><b><?php echo("".gmdate("l").""); ?></b></span>, if you'd prefer to book for another day,
 please click one of the links below.<br /><br />
 <hr size=\"1\"><center>
 <a<?php echo("$hack"); ?> onclick="SetLoad('Monday'); GetShouts('Monday'); ReleaseBold('Monday'); ResetBold('Saturday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Sunday');"><span id="Monday">Monday</span></a> | 
 <a<?php echo("$hack"); ?> onclick="SetLoad('Tuesday'); GetShouts('Tuesday'); ReleaseBold('Tuesday'); ResetBold('Monday', 'Saturday', 'Wednesday', 'Thursday', 'Friday', 'Sunday');"><span id="Tuesday">Tuesday</span></a> | 
 <a<?php echo("$hack"); ?> onclick="SetLoad('Wednesday'); GetShouts('Wednesday'); ReleaseBold('Wednesday'); ResetBold('Monday', 'Tuesday', 'Saturday', 'Thursday', 'Friday', 'Sunday');"><span id="Wednesday">Wednesday</span></a> | 
 <a<?php echo("$hack"); ?> onclick="SetLoad('Thursday'); GetShouts('Thursday'); ReleaseBold('Thursday'); ResetBold('Monday', 'Tuesday', 'Wednesday', 'Saturday', 'Friday', 'Sunday');"><span id="Thursday">Thursday</span></a> | 
 <a<?php echo("$hack"); ?> onclick="SetLoad('Friday'); GetShouts('Friday'); ReleaseBold('Friday'); ResetBold('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Saturday', 'Sunday');"><span id="Friday">Friday</span></a> | 
 <a<?php echo("$hack"); ?> onclick="SetLoad('Saturday'); GetShouts('Saturday'); ReleaseBold('Saturday'); ResetBold('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Sunday');"><span id="Saturday">Saturday</span></a> | 
 <a<?php echo("$hack"); ?> onclick="SetLoad('Sunday'); GetShouts('Sunday'); ReleaseBold('Sunday'); ResetBold('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');"><span id="Sunday">Sunday</span></a>
 </center><hr size="1">
<div id="timetable"><center><b>LOADING</b></center></div>