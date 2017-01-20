<?php
session_start();
require("functions.php");
require("connector.php");
define("kristall", 1);
login();
?>
<html>
<head>
<title><?php sitename(); ?> :: Kristall-Panel :: Welcome</title>
<style type="text/css">@import("default.css");</style>
<link href="default.css" rel="stylesheet" type="text/css" title="default">
<link href="redrose.css" rel="alternate stylesheet" type="text/css" title="red">
<link href="cleanwhite.css" rel="alternate stylesheet" type="text/css" title="white">
<script type="text/javascript" src="js.js"></script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="timetable()">

<div align="center">
	<table cellpadding="0" cellspacing="0" class="header">
		<tr>
			<td valign="top" align="center">
			<table cellpadding="0" cellspacing="0" width="430" height="150">
				<tr>
					<td class="logo">&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-2" >
			<div class="panel panel-default" >
				<div class="panel-body">


				<audio controls="" autoplay="" style="width:100%; height:10;  background-color:#000; color:#000;" src="http://server-66.stream-server.nl:8844/;"></audio>

					</div>
				</div>

			</div>
		</div>

	</div>
	<br>
<div align="center">
	<table cellpadding="0" cellspacing="0" width="95%" align="center">
		<tr>
			<td width="213" style="padding-right: 9px;" valign="top">
			<span class="title">Navigation</span><br /><br />
			<span class="subtitle">Standaard Links</span><br />
				<a href="?view=welcome">Homepagina</a><br />

				<a href="?view=changedetails">Verander Je Details</a><br />

				<a href="logout.php">Uitloggen</a><br />
			<br />

			<span class="subtitle">DJ Links</span><br />
			<a href="?view=requests">Verzoekjes <?php countrequests(); ?></a><br />

			<a href="?view=viewinfo">Radio Informatie</a><br />
			<br />
			<span class="subtitle">Prive berichten</span><br />
			<a href="?view=pm&act=viewform">Zie hier je Prive berichten (<?php countpm(); ?>)</a><br />
			<a href="?view=pm&act=sendform">Verstuur hier een Prive bericht</a><br />
			<?php
			if($_SESSION["kristall_level"] == "Senior DJ" || $_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
			{
				echo("<a href=\"?view=pm&act=massform\">Verstuur een groepsbericht</a><br />");
			}
			?>
			<?php
			if($_SESSION["kristall_level"] == "Administrator" || $_SESSION["kristall_level"] == "Super Administrator")
			{
				?>
				<hr>
				<span class="title">Administration</span><br />
			<br />
			<span class="subtitle">General Administration</span><br />
				<a href="?view=kpupdates">Kristall-Panel Updates</a><br />
				<a href="?view=settings">System Settings</a><br />
				<a href="?view=radioinfo">Update Radio Info</a><br />
				<a href="?view=viewlogs">View Login Logs</a><br />
				<br />
			<span class="subtitle">User Administration</span><br />
			<a href="?view=usermanage&act=addform">Add a User</a><br />
			<a href="?view=usermanage&act=choosedit">Edit a User</a><br />
			<a href="?view=usermanage&act=deleteform">Remove a User</a><br />
			<?php
			}
			?>
			<hr>
			<span class="title">Gebruiker Details</span><br />
			<strong>Gebruikersnaam:</strong> <?php echo("{$_SESSION["kristall_username"]}"); ?><br />
			<strong>Niveau:</strong> <?php echo("{$_SESSION["kristall_level"]}"); ?><br />
			<br>
			<span class="title">Styles</span><br />
			<a onclick="setActiveStyleSheet('red');">Red Rose</a><br />
			<a onclick="setActiveStyleSheet('default');">Kristall Blue (Default)</a><br />
			<a onclick="setActiveStyleSheet('white');">Desaturated</a>
			<br />
			</td>


					<td class="main">
					<?php
						$url = clean($_GET["view"], "nofilter");
						if(isset($_GET["view"]) && $_GET["view"] != "")
						{
							if(file_exists("$url.php"))
							{
								include("$url.php");
							}
							else
							{
								include("404.php");
							}
						}
						else
						{
							include("welcome.php");
						}
					?>
					</td>
		</tr>
	</table>
</div>

</body>

</html>
