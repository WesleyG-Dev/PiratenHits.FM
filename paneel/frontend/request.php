<html>
<head>
<title>Request lines.</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<br><br>
<body>
<?php
include("functions.php");
include("../connector.php");
include("requeststats.php");
if(isset($_POST["name"]) && isset($_POST["message"]))
{
	$username = clean($_POST["name"]);
	$message = clean($_POST["message"]);
	$dj = clean($_POST["DJ"]);
	$type = clean($_POST["type"]);
	if($username == "" || $message == "")
	{
		header('Location: https://wesleygrevink.nl/verzoekbox.php?error');
		exit;
	}
	mysql_query("INSERT INTO `requests` (`sender`, `dj`, `message`, `date`, `type`, `ip`) VALUES ('$username', '$dj', '$message', '".gmdate("d/m/Y - h:i:s")."', '$type', '".$_SERVER["REMOTE_ADDR"]."')");
	header('Location: https://wesleygrevink.nl/verzoekbox.php?success');
	exit;
}
else
{
	?>
	<div class="container">
		<div style='padding: 20px;' class="panel panel-default">
			<form action="" method="post">
			<b>Jouw naam:</b><br /><input class='form-control' type="text" name="name">
			<select hidden name="DJ">
			<?php
			$sql = mysql_query("SELECT `username` FROM `users`");
			while($fetch = mysql_fetch_array($sql))
			{
			if(preg_match("/{$fetch["username"]}/i", $djsplit))
			{
				echo("<option value=\"{$fetch["username"]}\" selected>DJ {$fetch["username"]}</option>\n");
			}
			else
			{
				echo("<option value=\"{$fetch["username"]}\">DJ {$fetch["username"]}</option>\n");
			}
			}
			?>
			</select>
			<select hidden name="type">
			<option selected>Song Request</option>
			<option>Listener Shoutout</option>
			<option>Competition Entry</option>y
			<option>Joke Submission</option>
			<option>Other Submission</option>
			</select>
			<br /><br />
			<b>Jouw bericht:</b><br />
			<textarea class='form-control' name="message" rows="8" cols="50"></textarea><br /><br />
			<input class='btn btn-primary' type="submit" name="submit" value="Versturen">
			</form>
		</div>
	</div>

	<?php
}
?>
</body></html>
