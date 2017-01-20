<!DOCTYPE html>
<html>
  <?php require_once "./src/components/header.php" ?>





<body>

      <div class="container">
        <div class="row">

                  <div class="col-md-12" style="border-radius: 0px;">
                    <div class="panel panel-default" style="border-radius: 0px;">
                      <div class="panel-body" style="border-radius: 0px;">
                        <div class="well" style="text-align: center;">
                           <?php
                           $ip = "server-66.stream-server.nl";
                           $port = "8844";
                           $fp = @fsockopen($ip,$port,$errno,$errstr,1);
                           if (!$fp) {
                               $title = "Connection timed out or the server is offline  ";
                           } else {
                               fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: Mozilla\r\n\r\n");
                               while (!feof($fp)) {
                                   $info = fgets($fp);
                               }
                               $info = str_replace('</body></html>', "", $info);
                               $split = explode(',', $info);
                               if (empty($split[6])) {
                                   $title = "The current song is not available  ";
                               } else {
                                   $count = count($split);
                                   $i = "6";
                                   while($i<=$count) {
                                       if ($i > 6) {
                                           $title .= ", " . $split[$i];
                                       } else {
                                           $title .= $split[$i];
                                       }
                                       $i++;
                                   }
                               }
                           }
                           $title = substr($title, 0, -2);
                           print $title;
                           ?>

                       </div>



                    <audio  controls=""  style="width:100%; height:10;  background-color:#000; color:#000;" src="http://server-66.stream-server.nl:8844/;"></audio>

                    </div>
                  </div>
        </div>
      </div>
    </div>





  </html>
