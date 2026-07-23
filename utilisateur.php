 <form id="form_utilisateur" method="post" action="admin.php?menu=utilisateurs"> <div class="modal-content">
	<div class="row">
		<?php
	include('fonctions.php');
			$utilisateurs = $pdo->query("SELECT * FROM ".$tab."utilisateurs WHERE id=".$_REQUEST['id']);
			$utilisateur = $utilisateurs->fetch();
	?>
    <div class="form-group">
    <label for="Nom_utilisateur">Nom</label>
    <input type="text" name="nom" class="form-control" id="Nom_utilisateur" placeholder="Nom d'utilisateur" value="<?php echo $utilisateur['nom'];?>">
  </div>

    <div class="form-group">
    <label for="Prenom_utilisateur">Prenom d'utilisateur</label>
    <input type="text" name="prenom" class="form-control" id="Prenom_utilisateur" placeholder="Prenom d'utilisateur" value="<?php echo $utilisateur['prenom'];?>">
  </div>
    <div class="form-group">
    <label for="nom_u">Nom utilisateur</label>
    <input type="text" name="Nom_utilisateur" class="form-control" id="nom_u" placeholder="nom utilisateur" value="<?php echo $utilisateur['Nom_utilisateur'];?>">
  </div>

    <div class="form-group">
    <label for="mdp">Mot de passe</label>
    <input type="password" name="mot_pass" class="form-control" id="mdp" placeholder="Mot de passe"  value="<?php echo $utilisateur['mot_pass'];?>">
  </div>

    <div class="form-group">
    <label for="tel">tel</label>
    <input type="text" name="tel" class="form-control" id="tel" placeholder="Téléphone"  value="<?php echo $utilisateur['tel'];?>">
  </div>

    <div class="form-group">
    <label for="gsm">gsm</label>
    <input type="text" name="gsm" class="form-control" id="gsm" placeholder="Téléphone 2"  value="<?php echo $utilisateur['gsm'];?>">
  </div>

    <div class="form-group">
    <label for="email">email</label>
    <input type="text" name="email" class="form-control" id="email" placeholder="E-mail"  value="<?php echo $utilisateur['email'];?>">
  </div>

    <div class="form-group">
    <label for="etat">etat</label>
	<select name="etat" class="form-control" id="etat" >
	<option value="1" <?php echo ($utilisateur['etat']==1)?"selected":""; ?> >Compte bloqué</option>
	<option value="2" <?php echo ($utilisateur['etat']==2)?"selected":""; ?> >Vendeur</option>
	<option value="3" <?php echo ($utilisateur['etat']==3)?"selected":""; ?> >Administrateur</option>
	</select>
  </div>
  
    <div class="form-group">
    <label for="note">Note</label>
			<textarea name="descriptions" class="form-control" id="note" placeholder="Note"><?php echo $utilisateur['descriptions'];?></textarea>

  </div>

	</div>
	<input id="type_form" type="hidden" name="edit_utilisateur_mod" value="<?php echo $utilisateur['id'];?>">
 <button id="submit_btn" type="button" class="btn btn-primary col" onclick="edit_utilisateur()">Valider la modification</button>
	</div>
</form>