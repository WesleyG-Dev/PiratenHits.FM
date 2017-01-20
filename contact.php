<!DOCTYPE html>
<html>

  <?php require_once "./src/components/header.php" ?>



  <div class="container">
  		<div class="panel panel-default">
                    <div class="panel-body">


                       <legend>Contact opnemen met Piratenhits.fm</legend>

                      <?php

                       if(isset($_POST['mail'])) {
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                              mail('wesleygrevink@icloud.com', htmlspecialchars($_POST['subject']),

(
"
<!DOCTYPE html>
<html>
<div style='background-color: #F1F1F1; padding: 5em;'>
<br><br>
<div style='background-color: white; border: 1px solid lightgrey; padding: 20px;'>
Naam: " .$_POST['name'] ." <br><br>
Afzender: " .$_POST['mail'] ."<br><br>
Onderwerp: " .$_POST['subject'] ."<br><br>
<hr>
" .nl2br($_POST['message']) ."
</div>

<br><br>
<div style='background-color: white; border: 1px solid lightgrey; padding: 20px;'>
<small>
De inhoud van dit bericht is alleen bestemd voor de geadresseerde en kan vertrouwelijke of persoonlijke informatie bevatten.
Als u dit bericht onbedoeld heeft ontvangen verzoeken wij u het te vernietigen en de afzender te informeren.
Het is niet toegestaan om een bericht dat niet voor u bestemd is te vermenigvuldigen dan wel te verspreiden.
Aan dit bericht inclusief de bijlagen kunnen geen rechten ontleend worden, tenzij schriftelijk anders wordt overeengekomen.
Wesleygrevink.nl aanvaardt geen enkele aansprakelijkheid voor schade en/of kosten die voortvloeien uit onvolledige en/of foutieve informatie in e-mailberichten.
</small>
</div>
</div>

</html>
"
)

, $headers);
                              ?>
                            <div class="alert alert-success">Bericht is verstuurd</div>
                                <?php
                           }

                     ?>

                      <form method="POST">

                           <input type="text" name="name" class="form-control" placeholder="Naam">
                           <input type="text" name="mail" class="form-control" placeholder="E-Mail adres">
                           <input type="text" name="subject" class="form-control" placeholder="Onderwerp"> <br>
                           <textarea style="resize: none"name="message" class="form-control" rows="5" placeholder="Bericht"></textarea> <br><br>
                           <input type="submit" class="btn btn-primary btn-block" value="Verstuur bericht">
                       </form>
                    </div>
                </div>
	</div>

  <div class="container">
    <div class="row">
      <div class="col-md-12" >
        <div class="panel panel-default" >
          <div class="panel-body">

          <audio controls="" autoplay="" style="width:100%; height:10;  background-color:#000; color:#000;" src="https://server-66.stream-server.nl:8844/;"></audio>

            </div>
          </div>

        </div>
      </div>

    </div>




  </html>
