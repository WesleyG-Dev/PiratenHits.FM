<!DOCTYPE html>
<html>

  <?php require_once "./src/components/header.php" ?>





      <div class="container">
        <div class="row">
          <div class="col-md-12" >
            <div class="panel panel-default" >
              <div class="panel-body">
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
              <audio controls="" autoplay="" style="width:100%; height:10;  background-color:#000; color:#000;" src="http://server-66.stream-server.nl:8844/;"></audio>

                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
      <div class="container">
          <div class="panel panel-default">
                        <div class="panel-body">
                          <?php if(isset($_GET['error'])){ ?><div class='alert alert-danger'>Er is iets mis gegaan</div><?php } ?>
                          <?php if(isset($_GET['success'])){  ?><div class='alert alert-success'>Je verzoekje is verzonden</div><?php } ?>
                          <form action='./paneel/frontend/request.php' method="post">
                          <b>Jouw naam:</b><br /><input class='form-control' type="text" name="name">
                          <select hidden name="DJ">
                          <option value="123456" selected></option>
                          </select>
                          <select hidden name="type">
                          <option selected>Song Request</option>
                          <option>Listener Shoutout</option>
                          <option>Competition Entry</option>y
                          <option>Joke Submission</option>
                          <option>Other Submission</option>
                          </select>
                          <br>
                          <b>Jouw verzoekplaat:</b>
                          <!--<textarea class='form-control' name="message" rows="8" cols="50"></textarea><br /><br />-->
                          <input type='text' name='message' class='form-control'> <br>
                          <input class='btn btn-primary' type="submit" name="submit" value="Versturen">
                          </form>

      </div>
      </div></div>



  </html>
