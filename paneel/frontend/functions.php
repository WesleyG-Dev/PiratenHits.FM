<?php
/*======================================================================*\
|*                     FINAL FRONTEND FUNCTIONS.                        *|
\*======================================================================*/
	function clean($string, $type = NULL)
	{
	 	$string = str_replace("'", "", $string);
		$string = str_replace("\"", "", $string);
        $string = nl2br($string);
        $string = htmlentities($string);
        $bbcode = array('#\[b\](.*?)\[\/b\]#se',
				'#\[u\](.*?)\[\/u\]#se',
				'#\[i\](.*?)\[\/i\]#se',
				'#\[B\](.*?)\[\/B\]#se',
				'#\[U\](.*?)\[\/U\]#se',
				'#\[I\](.*?)\[\/I\]#se',);
$replace = array("'<b>\\1</b>'",
				 "'<u>\\1</u>'",
				 "'<i>\\1</i>'",
				 "'<b>\\1</b>'",
				 "'<u>\\1</u>'",
				 "'<i>\\1</i>'",);
$string = preg_replace($bbcode, $replace, $string);
if($type != "nofilter")
{
$string = filter($string);
}
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
?>