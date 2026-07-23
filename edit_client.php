<form id="form_client" method="post">

  <div class="modal-content">

    <div class="row">

      <?php

      include('fonctions.php');

      $clients = $pdo->query("SELECT * FROM " . $tab . "client WHERE id=" . $_REQUEST['id']);

      $client = $clients->fetch();



      ?>

      <div class="form-group">

        <label for="Nom_client">[<?php echo $_REQUEST['id']; ?>] Nom du client</label>

        <input type="text" name="nom" class="form-control" id="Nom_client" placeholder="Nom du client" value="<?php echo $client['nom']; ?>">

      </div>



      <div class="form-group">

        <label for="Prenom_client">Prenom du client</label>

        <input type="text" name="prenom" class="form-control" id="Prenom_client" placeholder="Prenom du client" value="<?php echo $client['prenom']; ?>">

      </div>

      <div class="form-group">

        <label for="MF">Matricule fiscal</label>

        <input type="text" name="MF" class="form-control" id="MF" placeholder="Matricule fiscal" value="<?php echo $client['MF']; ?>">

      </div>



      <div class="form-group">

        <label for="Adresse">Adresse</label>

        <input type="text" name="adresse" class="form-control" id="Adresse" placeholder="Adresse" value="<?php echo $client['adresse']; ?>">

      </div>



      <div class="form-group">

        <label for="tel">Téléphone</label>

        <input type="text" name="tel" class="form-control" id="tel" placeholder="Téléphone" value="<?php echo $client['tel']; ?>">

      </div>



      <div class="form-group">

        <label for="gsm">GSM</label>

        <input type="text" name="gsm" class="form-control" id="gsm" placeholder="GSM" value="<?php echo $client['gsm']; ?>">

      </div>



      <div class="form-group">

        <label for="email">E-mail</label>

        <input type="text" name="email" class="form-control" id="email" placeholder="E-mail" value="<?php echo $client['email']; ?>">

      </div>



      <div class="form-group">

        <label for="Longitude_client">Longitude</label>

        <input type="text" name="Longitude" class="form-control" id="Longitude_client" placeholder="Longitude" value="<?php echo $client['Longitude']; ?>">

      </div>

      <div class="form-group">

        <label for="Latitude">Latitude</label>

        <input type="text" name="Latitude" class="form-control" id="Latitude_client" placeholder="Latitude" value="<?php echo $client['Latitude']; ?>">

      </div>



      <div class="form-group">

        <label for="type">Type</label>

        <select name="type" class="form-control" id="type" placeholder="Type practicien">

          <option <?= (isset($client['type']) && $client['type'] == "Dentiste") ? "selected" : "" ?>>Dentiste</option>

          <option <?= (isset($client['type']) && $client['type'] <> "Dentiste") ? "selected" : "" ?>>Orthodentiste</option>

        </select>

      </div>

      <div class="form-group">

        <label for="note">Note</label>

        <textarea name="note" class="form-control" id="note" placeholder="Note"><?php echo $client['nom']; ?></textarea>



      </div>



    </div>

    <input id="type_form" type="hidden" name="edit_client_mod" value="<?php echo $client['id']; ?>">

    <button id="submit_btn" type="button" class="btn btn-primary col" onclick="edit_client()">Modifier</button>



  </div>

  </div>

  </div>

  </div>

</form>