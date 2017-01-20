<?php
include("connector.php");
function login($level = NULL)
	{
		if(isset($_SESSION["kristall_username"]))
		{
			$sql = mysql_fetch_array(mysql_query("SELECT `ban`, `warning` FROM `users` WHERE `username` = '{$_SESSION["kristall_username"]}'"));
			$warning = mysql_fetch_array(mysql_query("SELECT `totalwarnings` FROM `settings`"));
			if($sql["warning"] >= $warning["totalwarnings"] || !is_numeric($sql["warning"]))
			{
				session_start();
				errorheader("You're banned!");
				echo("Unfortunatley our logs show that your warning level either isn't a number (Meaning someone tried to let you in sneakily!) or your warning level is higher than that allowed by this panel.<br /><br />If you believe this ban to be in error, please contact an administrator at your earliest convenience.");
				errorfooter();
				session_destroy();
				session_unset();
				exit;
			}
			if($sql["ban"] == "Yes")
			{
				session_start();
				errorheader("You're banned!");
				echo("Unfortunatley our logs show that you have been banned by an administrator.<br /><br />If you believe this ban to be in error, please contact an administrator at your earliest convenience.");
				errorfooter();
				session_destroy();
				session_unset();
				exit;
			}
		}
		if($level == NULL)
		{
			if(!isset($_SESSION["kristall_username"]) || empty($_SESSION["kristall_username"]) || empty($_SESSION["kristall_level"]) || !isset($_SESSION["kristall_level"]) || !isset($_SESSION["kristall_ip"]) || empty($_SESSION["kristall_ip"]) || !isset($_SESSION["kristall_hostname"]) || empty($_SESSION["kristall_hostname"]))
			{
			 	session_start();
				errorheader("You're not logged in!");
				echo("You are currently not logged in, this could be for a variety of reasons:<br />- You have not logged in.<br />- Your session has timed out.<br />- An error has occured.<br /><br />If all of the following items below are set, then please contact an administrator immediatley.<br>Username: {$_SESSION["kristall_username"]}<br />Level: {$_SESSION["kristall_level"]}<br />IP: {$_SESSION["kristall_ip"]}<br />Hostname: {$_SESSION["kristall_hostname"]}");
				errorfooter();
				session_destroy();
				session_unset();
				exit;
			}
		}
		elseif($level == "admin")
		{
			login();
			if($_SESSION["kristall_level"] != "Administrator" && $_SESSION["kristall_level"] != "Super Administrator")
			{
				notice("You cannot view this page.");
				echo("You are not allowed to view this page because you don't have sufficient rights.<br /><br />This page will not refresh, please navigate away.");
				endnotice();
				exit;
			}
		}
		elseif($level == "senior")
		{
			login();
			if($_SESSION["kristall_level"] != "Senior DJ" && $_SESSION["kristall_level"] != "Administrator" && $_SESSION["kristall_level"] != "Super Administrator")
			{
				notice("You cannot view this page.");
				echo("You are not allowed to view this page because you don't have sufficient rights.<br /><br />This page will not refresh, please navigate away.");
				endnotice();
				exit;
			}
		}
		else 
		{
			login();
			{
				notice("You cannot view this page.");
				echo("You cannot view this page as an incorrect function has been specified!, please navigate away from this page.");
				endnotice();
				exit;
			}
		}
	}
	function iehack()
	{
		if(preg_match("/msie/i", $_SERVER["HTTP_USER_AGENT"]))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	function countpm()
	{
		$sql = mysql_num_rows(mysql_query("SELECT `id` FROM `pm` WHERE `to` = '{$_SESSION["kristall_username"]}' AND `read` = '0' AND `reported` = '0'"));
		echo("$sql");
	}
function users($type = NULL)
	{
		$type = clean($type);
		if($type == "totalreg")
		{
			$sql = mysql_num_rows(mysql_query("SELECT `id` FROM `users`"));
			echo("$sql");
		}
		elseif($type == "lastreg")
		{
			$sql = mysql_fetch_array(mysql_query("SELECT `username` FROM `users` ORDER BY `username` DESC"));
			echo("{$sql["username"]}");
		}
		else 
		{
			echo("invalid type defined.");
		}
	}
	function djsays()
	{
		$sql = mysql_fetch_array(mysql_query("SELECT * FROM `djsays`"));
		echo("<hr /><b>DJ {$sql["username"]}</b> posted the message:<br />
					{$sql["shoutout"]}");
	}
function warning()
	{
		$fetch = mysql_fetch_array(mysql_query("SELECT `warning` FROM `users` WHERE `username` = '{$_SESSION["kristall_username"]}'"));
		echo("{$fetch["warning"]} / ");
		$fetch = mysql_fetch_array(mysql_query("SELECT `totalwarnings` FROM `settings`"));
		echo("{$fetch["totalwarnings"]}");
	}
function sitename()
	{
		$fetch = mysql_fetch_array(mysql_query("SELECT `sitename` FROM `settings`"));
		echo("{$fetch["sitename"]}");
	}
	function clean($string, $type = NULL, $smilies = NULL)
		{
			$string = str_replace("\"", "", $string);
	        $string = nl2br($string);
	        $string = htmlentities($string);
	        $mysql = mysql_fetch_array(mysql_query("SELECT `bbcode` FROM `settings`"));
	        $words = array("UNION",
							"SELECT FROM",
							"ORDER BY",
							"INSERT INTO",
							"TRUNCATE",
							"DROP TABLE",
							"CREATE TABLE",
							"DROP DATABASE"); // All the queries we want to stop
			$string = preg_replace("/$words/i", "", $string);
	        if($mysql["bbcode"] == "Yes")
	        {
		        $bbcode = array('#\[b\](.*?)\[\/b\]#mie',
		       					'#\[strike\](.*?)\[\/strike\]#mie',
								'#\[u\](.*?)\[\/u\]#mie',
								'#\[i\](.*?)\[\/i\]#mie',);
				$replace = array("'<b>\\1</b>'",
								 "'<strike>\\1</strike>'",
								 "'<u>\\1</u>'",
								 "'<i>\\1</i>'",);
				$string = preg_replace($bbcode, $replace, $string);
	        }
	        if($smilies == "dosmilies")
	        {
	        	$bbcode = array('#D:#mie',
					'#=\(#mie',
					'#=D#mie',
					'#=\)#mie',
					'#;D#mie',
					'#;o#mie',
					'#;P#mie',
					'#:lsm82Nik:#mie',
					'#:lsm82Nip:#mie',);
	        	$replace = array("'<img src=\"images/noes.gif\">'",
					 "'<img src=\"images/=(.gif\">'",
					 "'<img src=\"images/=D.gif\">'",
					 "'<img src=\"images/=).gif\">'",
					 "'<img src=\"images/;D.gif\">'",
					 "'<img src=\"images/;o.gif\">'",
					 "'<img src=\"images/;P.gif\">'",
					 "'<img src=\"images/stopspampolite.gif\">'",
					 "'<img src=\"images/stopspamimpolite.gif\">'",);
					 $string = preg_replace($bbcode, $replace, $string);
					 $string = str_replace("-.-", "<img src=\"images/-.-.gif\">", $string);
	        }
	if($type != "nofilter")
	{
	$string = filter($string);
	}
	$string = preg_replace("/&lt;br \/&gt;/i", "<br />", $string);
	        return $string;
		}
			function encrypt($string)
			{
				$string = clean($string, "nofilter");
				$string = md5($string);
				$string = sha1($string);
				return $string;
			}
				function filter($string)
				{
					$sql = mysql_fetch_array(mysql_query("SELECT `filter` FROM `settings`"));
					$split = split(";", $sql["filter"]);
					for($num = 0; $num < count($split); $num++)
					{
						$string = preg_replace("/{$split["$num"]}/i", "****", $string);
					}
					return $string;
				}
		function djname()
			{
				$fetch = mysql_fetch_array(mysql_query("SELECT `username` FROM `djsays`"));
				echo("{$fetch["username"]}");
			}
	function djmessage()
		{
			$fetch = mysql_fetch_array(mysql_query("SELECT `shoutout` FROM `djsays`"));
			echo("{$fetch["shoutout"]}");
		}
function reversebb($string)
	{
				$bbcode = array('[b]\\1[/b]',
		       					'[strike]\\1[/strike]',
								'[u]\\1[/u]',
								'[i]\\1[/i]',);
				$replace = array("'<b>(.*?)</b>'",
								 "'<strike>(.*?)</strike>'",
								 "'<u>(.*?)</u>'",
								 "'<i>(.*?)</i>'",);
				$string = preg_replace($replace, $bbcode, $string);
				$string = str_replace("\n", "", $string);
				$string = str_replace("<br />\n", "\n", $string);
				$string = str_replace("<br />", "", $string);
				return $string;
	}
function countrequests()
	{
		$sql = mysql_num_rows(mysql_query("SELECT `id` FROM `requests` WHERE `dj` = '{$_SESSION["kristall_username"]}'"));
		echo("($sql)");
	}
function serverload()
	{
		$load = exec("uptime");
		$load = split("load average:", $load);
		$load = split(", ", $load[1]);
		if($load[0] < "2.0")
		{
			$load = ("<font style=\"color: darkgreen; font-weight: bold;\">{$load[0]}%</font>");
		}
		elseif($load[0] < "5.00")
		{
			$load = ("<font style=\"color: orange; font-weight: bold;\">{$load[0]}%</font>");
		}
		else
		{
			$load = ("<font style=\"color: red; font-weight: bold;\">{$load[0]}%</font>");
		}
		echo $load;
	}
function sqlversion()
	{
		$ver_result = @mysql_query("SELECT Version() AS version");
		$ver_row = @mysql_fetch_array($ver_result);
		$mySQLversion = $ver_row["version"];
		$mySQLversion = split("-", $mySQLversion);
		if($mySQLversion[0] < "4.0.27")
		{
			echo("<font style=\"color: red; font-weight: bold;\">$mySQLversion[0]</font>");
		}
		else
		{
			echo ("<font style=\"color: darkgreen; font-weight: bold;\">$mySQLversion[0]</font>");
		}
		mysql_free_result($ver_result);
	}
function phpversiondef()
	{
		$phpversion = phpversion();
		if($phpversion < "4.3.11")
		{
			echo("<font style=\"color: red; font-weight: bold;\">$phpversion</font>");
		}
		else
		{
			echo("<font style=\"color: darkgreen; font-weight: bold;\">$phpversion</font>");
		}
	}
function errorheader($text)
	{
		echo('<html>
			<head>
			<meta http-equiv="Content-Language" content="en-gb">
			<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
			<title></title>
			<style type="text/css">
			.nav
			{
				width: 450px;
				padding: 13px;
				vertical-align: top;
			}
			.title
			{
				font-size: 24px;
				font-family: Trebuchet MS;
			}
			.subtitle
			{
				font-size: 14px;
				font-weight: bold;
			}
			td, a, a:link, a:active, input
			{
				font-size: 12px;
				font-family: Trebuchet MS;
				color: #000000;
				text-decoration: none;
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
				<table cellpadding="0" cellspacing="0" width="430">
					<tr>
						<td class="nav"><span class="title">'.$text.'</span><br />');
	}
function errorfooter()
	{
		echo('</td>
				
				
				</tr>
			</table>
		</div>
		
		</body>
		
		</html>');
	}
function notice($text)
	{
		echo('<span class="title">'.$text.'</span><br />');
	}
function endnotice()
	{
		echo('<br />');
		exit;
	}
function loginerror()
{
 	errorheader("You're not allowed to access that!");
	echo("You are not allowed to access this page directly, you must access it by logging into the panel, you may log in by clicking <a href=\"index.php\">here</a>");
	errorfooter();
	session_unset();
	session_destroy();
}
?>