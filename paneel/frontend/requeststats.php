<link href="stats.css" rel="stylesheet" type="text/css">
<?php
include("connector.php");
$sql = mysql_fetch_array(mysql_query("SELECT * FROM `radioinfo`"));
$pass = ("{$sql["pass"]}");
$rofl = fsockopen("{$sql["ip"]}", "{$sql["port"]}");

if(!$rofl)
{
	
}
else
{
	fputs($rofl,"GET /admin.cgi?pass=$pass&mode=viewxml HTTP/1.0\r\nUser-Agent: SHOUTcast Song Status (Mozilla Compatible)\r\n\r\n");
 while(!feof($rofl)) {
  $page .= fgets($rofl, 1000);
}
}
if(preg_match("/Admin page is busy/i", $page))
{

}
elseif(!preg_match("/\<STREAMSTATUS\>1\<\/STREAMSTATUS\>/i", $page))
{

}
else
{
	$startsplit = split("<SHOUTCASTSERVER>", $page);
	$endsplit = split("<SONGURL>", $startsplit[1]);
	$stats = $endsplit[0];
	$startdjsplit = split("<SERVERTITLE>", $stats);
	$enddjsplit = split("</SERVERTITLE>", $startdjsplit[1]);
	$djsplit = $enddjsplit[0];
}
?>
