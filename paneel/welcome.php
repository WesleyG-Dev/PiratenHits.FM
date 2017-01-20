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
$fetch = mysql_fetch_array(mysql_query("SELECT `welcome` FROM `settings`"));
echo("<span class=\"title\">Welkom</span><br />Dit is het Dj-Paneel van PiratenHits.Fm. Hier heb je toegang tot de ingezonden verzoekjes en kun je deze indien nodig ook verwijderen. <br> Heeft uw vragen over het paneel? Neem dan contact op met de Admin.
");
?>
