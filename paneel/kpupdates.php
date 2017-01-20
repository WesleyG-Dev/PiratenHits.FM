<?php
echo("<span class=\"title\">Kristall-Panel Updates.</span><br />");
$updates = @file_get_contents("http://kristall-services.com/kpupdates.php");
if(!$updates)
{
echo("Could not retrieve updates from the Kristall-Services server.<br />Please try later.");
}
else 
{
	echo("$updates");
}
?>