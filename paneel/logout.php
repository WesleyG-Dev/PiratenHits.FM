<?php
session_start();
session_unset();
session_destroy();
exit("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL=index.php\">");
?>