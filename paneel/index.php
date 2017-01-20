<?php
if(@file_exists("installer.php"))
{
	header("Location: installer.php?act");
}
session_start();
if(isset($_POST["username"]) && isset($_POST["password"]))
{
	include("functions.php");
	include("connector.php");
	$username = clean($_POST["username"], "nofilter");
	$username = strtolower($username);
	$username = ucfirst($username);
	$password = clean($_POST["password"], "nofilter");
	$password = encrypt($password);
	######################################
	# KRISTALL-PANEL "WHO TEH F00K ARE J00?" VERSION 1.0 D: (Final... I think :S)
	# This callback simply enables me to check where the panel is accessed from
	# Once one person logs in and it logs the panel url, it'll disable itself :)
	# The callback logs no personal information, it just sends the server path to
	# Me, so I can track the amount of sites with this panel installed!
	$logsql = mysql_fetch_array(mysql_query("SELECT `haslogged` FROM `settings`"));
	if($logsql["haslogged"] != "logged")
	{
		$site = $_SERVER["HTTP_HOST"];
		$whatelse = $_SERVER["REQUEST_URI"];
		$call = file_get_contents("http://kristall-services.com/callback.php?site=http://".$site."".$whatelse."");
		mysql_query("UPDATE `settings` SET `haslogged` = 'logged'");
	}
	######################################
	if($username == "" || $password == "")
	{
		header("Location: index.php?error=blank");
	}
	$sql = mysql_query("SELECT `username`, `password`, `ban`, `level`, `warning` FROM `users` WHERE `username` = '$username' AND `password` = '$password'");
	if(mysql_num_rows($sql) == "1")
	{
		$fetch = mysql_fetch_array($sql);
		$warning = mysql_fetch_array(mysql_query("SELECT `totalwarnings` FROM `settings`"));
		if($fetch["ban"] == "Yes")
		{
			header("Location: index.php?error=banned");
			exit;
		}
		if($fetch["warning"] >= $warning["totalwarnings"])
		{
			mysql_query("UPDATE `users` SET `ban` = '1' WHERE `username` = '{$fetch["username"]}'");
			header("Location: index.php?error=banned");
			exit;
		}
		$ip = $_SERVER["REMOTE_ADDR"];
		$_SESSION["kristall_username"] = $fetch["username"];
		$_SESSION["kristall_level"] = $fetch["level"];
		$_SESSION["kristall_ip"] = $ip;
		$_SESSION["kristall_hostname"] = gethostbyaddr($ip);
		if(mysql_fetch_array(mysql_query("SELECT `pin` FROM `users` WHERE `username` = '{$fetch["username"]}'")) == "")
		{
			header("Location: dopin.php");
		}
		else
		{
			mysql_query("INSERT INTO `logs` (`name`, `ip`, `date`, `success`) VALUES ('$username', '{$_SERVER["REMOTE_ADDR"]}', '" . date("d/m/Y - h:i:s") . "', 'passed')");
			header("Location: main.php");
		}
	}
	else
	{
		mysql_query("INSERT INTO `logs` (`name`, `ip`, `date`, `success`) VALUES ('$username', '{$_SERVER["REMOTE_ADDR"]}', '" . date("d/m/Y - h:i:s") . "', 'failed')");
		header("Location: index.php?error=incorrect");
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Login</title>
<script type="text/javascript">

var bustcachevar = 1
var loadedobjects = ""
var rootdomain = "http://" +window.location.hostname
var bustcacheparameter = ""

function CheckDetails(url, containerid){
	var page_request = false
	if (window.XMLHttpRequest)
	page_request = new XMLHttpRequest()
	else if (window.ActiveXObject){
		try {
			page_request = new ActiveXObject("Msxml2.XMLHTTP")
		}
		catch (e){
			try{
				page_request = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch (e){}
		}
	}
	else
	return false
	page_request.onreadystatechange = function(){
		loadpage(page_request, containerid)
	}
	if (bustcachevar) //if bust caching of external page
	bustcacheparameter = (url.indexOf("?")!=-1)? "&" + new Date().getTime() : "?" + new Date().getTime()
	page_request.open('GET', url+bustcacheparameter, true)
	page_request.send(null)
}

function loadpage(page_request, containerid)
{
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("https") == -1))
	document.getElementById(containerid).innerHTML = page_request.responseText
}
</script>
<style type="text/css">
.nav
{
	width: 430px;
	padding: 13px;
	vertical-align: top;
	text-align: left;
}
.title
{
	font-size: 24px;
	font-family: Trebuchet MS;
}
.verdana
{
	font-size: 10px;
	font-family: Verdana;
}
.subtitle
{
	font-size: 14px;
	font-weight: bold;
}
.left
{
	text-align: left;
}
td, a, a:link, a:active, input
{
	font-size: 12px;
	font-family: Trebuchet MS;
	color: #000000;
}
a
{
	text-decoration: underlined;
}
a:hover
{
	font-weight: bold;
	cursor: hand;
	cursor: pointer;
}
input, textarea, fieldset
{
	border: 1px solid #e3e3e3;
	background: transparent;
	text-align: left;
}
hr
{
	border: 0px;
	color: #e3e3e3;
	background: #e3e3e3;
	height: 1px;
}
</style>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div align="center">
	<table cellpadding="0" cellspacing="0" width="100%" height="150" background="images/header.gif">
		<tr>
			<td valign="top" align="center">
			<table cellpadding="0" cellspacing="0" width="430" height="150">
				<tr>
					<td background="images/logo.gif">&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<div align="center">
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td class="nav">
			<span class="title">Login</span><br />Welkom bij het Dj-Paneel van Piratenhits.Fm. Vul hier onder je gegevens in en klik op
			 &quot;Login&quot;<br />
			 LET OP! Geef uw inloggegevens niet aan onbevoegden!! <br>
			<br />
			<form action="" method="post" name="login">
			Gebruikersnaam:<br />
			<input type="text" name="username" size="28" onchange="CheckDetails('checkdetails.php?username=' + this.value, 'username');"><span id="username"></span>
			<br /><br />
			Wachtwoord:<br />
			<input type="password" name="password" size="28">
			<br /><br />
			<input type="submit" value="Login" name="submit">
			</form><br /><br />
			</td>
		</tr>
	</table>
	<br /><br /><br /><br /><span class="verdana">Powered by <a href="http://kristall-services.com" target="_blank"><span class="verdana">Kristall-Panel</span></a></span>
</div>

</body>

</html>
