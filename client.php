
	<div class="col">
		<h4 onclick="cacher_Information_client()">Information client</h4>
	<div id="Information_client">
  <div class="row">
  <div class="col-8">
  <div class="form-group">
				<select name="liste_client" class="form-control" id="liste_client" placeholder="Selectionner votre client">
				<option value="" disabled selected>Selectionner votre client</option>
			<?php
			$reponse = $pdo->query("SELECT * FROM ".$tab."client ");
			while ($donnees = $reponse->fetch())
				{ 
					echo "<option value='".$donnees['id']."'>".$donnees['nom']." ".$donnees['prenom']."</option>";
				}
			?>
			</select>
  </div>
  </div>
  <div class="col-4">
 <button type="button" class="btn btn-primary col" onclick="affiche_ajout_client()">ajouter</button>
 </div>
 </div>
<!--//popup  -->
    	<div id="mod_client" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" id="close2">&times;</span>
    <p>
	<div class="row">
	
    <div class="form-group">
    <label for="Nom_client">Nom du client</label>
    <input type="text" name="nom_client" class="form-control" id="Nom_client" placeholder="Nom du client">
  </div>

    <div class="form-group">
    <label for="Prenom_client">Prenom du client</label>
    <input type="text" name="Prenom_client" class="form-control" id="Prenom_client" placeholder="Prenom du client">
  </div>
    <div class="form-group">
    <label for="MF">Matricule fiscal</label>
    <input type="text" name="MF" class="form-control" id="MF" placeholder="Matricule fiscal">
  </div>

    <div class="form-group">
    <label for="Adresse">Adresse</label>
    <input type="text" name="Adresse" class="form-control" id="Adresse" placeholder="Adresse">
  </div>

    <div class="form-group">
    <label for="tel">Téléphone</label>
    <input type="text" name="tel" class="form-control" id="tel" placeholder="Téléphone">
  </div>

    <div class="form-group">
    <label for="gsm">GSM</label>
    <input type="text" name="gsm" class="form-control" id="gsm" placeholder="GSM">
  </div>

    <div class="form-group">
    <label for="email">E-mail</label>
    <input type="text" name="email" class="form-control" id="email" placeholder="E-mail">
  </div>

    <div class="form-group">
    <label for="Longitude_client">Longitude</label>
    <input disabled type="text" name="Longitude_client" class="form-control" id="Longitude_client" placeholder="Longitude">
  </div>
    <div class="form-group">
    <label for="Latitude">Latitude</label>
    <input disabled type="text" name="Latitude_client" class="form-control" id="Latitude_client" placeholder="Latitude">
  </div>

    <div class="form-group">
    <label for="type">Type</label>
	<select name="type" class="form-control" id="type" placeholder="Type practicien">
			<option>Dentiste</option>
			<option>Orthodentiste</option>
			</select>

  </div>
  
    <div class="form-group">
    <label for="note">Note</label>
			<textarea name="note" class="form-control" id="note" placeholder="Note"></textarea>

  </div>

	</div>
 <button type="button" class="btn btn-primary col" onclick="ajoute_client()">Valider</button>
	</div>
	
	



	</div>
	</p>
  </div>

</div>
	<!--poup-->
	<script>
var modal_client = document.getElementById("Mod_client");
$("#close2").click(function(){mod_client.style.display = "none";});

window.onclick = function(event) {
  if (event.target == modal_client) {
    mod_client.style.display = "none";
  }
}
function edit(id)
{
  modal.style.display = "block";
  
  jQuery('#nom_produit_popup').html(jQuery('#nom_produit_'+id).html());
  jQuery('#id_produit_popup').html(id);
  jQuery('.form-control').val("");
  jQuery('#p_prix_u').html(jQuery('#pu_'+id).html());
  jQuery('#p_prix_t').html(jQuery('#pu_'+id).html());
  jQuery('.form-control').val(jQuery('#qte_'+id).html());
}
function close_pop()
{
	id=$("#id_produit_popup").html();
  modal.style.display = "none";
  jQuery('#produit_a_'+id).fadeOut( 1000 );
  jQuery('#produit_a_'+id).remove();
  total_tableau();
}
function close_pop_edit()
{
	id=$("#id_produit_popup").html();
    modal.style.display = "none";
	jQuery('#qte_'+id).html( jQuery('#input_qte').val() );
	q_total= parseFloat(jQuery('#pu_'+id).html()*jQuery('#qte_'+id).html()) ;
	q_total.toFixed(3);
	jQuery('#pt_'+id).html( trois_(q_total) );

	total_tableau();
}
function cal_qte()
{
	id=$("#id_produit_popup").html();
	jQuery('#p_prix_t').html(trois_(parseFloat(jQuery('#input_qte').val())*parseFloat(jQuery('#pu_'+id).html())));
}
	</script>
	<!--popup-->