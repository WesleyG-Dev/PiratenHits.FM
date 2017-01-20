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

function GetXmlHttpObject()
{ 
	var objXMLHttp = null 
	if (window.XMLHttpRequest)
	{ 
		objXMLHttp = new XMLHttpRequest() 
		}
			else if (window.ActiveXObject)
		{ 
		objXMLHttp = new ActiveXObject("Microsoft.XMLHTTP")
	} 
	return objXMLHttp 
} 

function GetShouts()
{ 
	xmlHttp = GetXmlHttpObject() 
	if (xmlHttp == null)
	{
		alert ("Browser does not support HTTP Request")
		return
	}
	var url="shoutbox/shouts.php?act=view"
	xmlHttp.open("GET", url, true)
	xmlHttp.onreadystatechange = function ()
	{
		if (xmlHttp.readyState == 4)
			{ 
				if (xmlHttp.status == 200)
					{
						document.getElementById("shoutarea").innerHTML = xmlHttp.responseText; 
					}
			}
	};
	xmlHttp.send(null); 
}
GetShouts(); 
setInterval("GetShouts()", 1000);
function saveData()
{
	xmlHttp = GetXmlHttpObject()
	if (xmlHttp == null)
	{
		alert ("Browser does not support HTTP Request")
		return
	}
	if(document.shout.message.value == "")
	{
		alert("Please go back and enter a message.")
		return
	}
	
	xmlHttp.open('POST', 'shoutbox/shouts.php?act=send');
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.send('&shout=' + document.shout.message.value);
	document.shout.message.value = '';
	document.shout.message.focus();	
}
function addemote(whattoadd)
{
	document.shout.message.value = document.shout.message.value + ' ' + whattoadd + ' ';
	document.shout.message.focus();
}
</script>
<div id="shoutarea"></div>

<br />
<form method="post" name="shout" onsubmit="saveData();return false;">
<input type="text" name="message" size="48" maxlength="80">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Shout"></form><br /><span class="title">Emoticons</span><br />
To add an emoticon to your message, please just click an image below.<br /><br />
<img src="images/noes.gif" onclick="addemote('D:');"> <img src="images/-.-.gif" onclick="addemote('-.-');"> <img src="images/=(.gif" onclick="addemote('=(');"> <img src="images/=D.gif" onclick="addemote('=D');"> <img src="images/=).gif" onclick="addemote('=)');"> <img src="images/;D.gif" onclick="addemote(';D');"> <img src="images/;o.gif" onclick="addemote(';o');"> <img src="images/;P.gif" onclick="addemote(';P');"><hr>
<?php
if($_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
{
	echo("<span class=\"title\">Administrator Quick Links</span><br />
			Just click any of the text or images below and that code will be placed into the shoutbox for quick and easy warning, annoying or any other purpose you may use these for!<br /><br />
			<a onclick=\"addemote('Please shut up.')\">STFU</a> (Polite)<br />
			<a onclick=\"addemote('WILL YOU SHUT THE FUCK UP.')\">STFU</a> (Impolite)<br /><br />
			<a onclick=\"addemote('Please refrain from spamming the shoutbox.')\">Stop spamming.</a> (Polite)<br />
			<a onclick=\"addemote('You either stop the spam shit now, or I put you in the fucking bancage.')\">Stop spamming.</a> (Impolite)<br /><br />
			");
}
?>