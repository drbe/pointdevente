<h3>familles</h3>
<form enctype="multipart/form-data"  method="post" name="ajoutContact" >
<input type="hidden" name="menu" value="familles">
  <div class="form-group">
    <label for="exampleInputEmail1">Nom de famille</label>
    <input type="text" name="nom_famille" class="form-control" id="exampleInputEmail1" placeholder="Saisir un nom de famille">
    <small id="emailHelp" class="form-text text-muted">Nom de famille.</small>
  </div>
  <div class="custom-file">
  <input type="file" name="image" class="custom-file-input" id="customFile" accept="image/png, image/jpeg, image/gif">
  <label class="custom-file-label" for="customFile" >Choisir une image</label>
</div>
<input type="hidden" name="ajout_famille" value="1">
  <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<table class="table  table-dark">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nom</th>
      <th scope="col">Image</th>
      <th scope="col-2">X</th>
    </tr>
  </thead>
  <tbody>
<?php
if(isset($_REQUEST['ajout_famille'])){
	$img="";
if($_FILES['image']['tmp_name']<>""){	
		$dossier = explode("php",$_FILES['image']['tmp_name']);
		$dossier[1]="php".$dossier[1];
		$ext = explode(".",$_FILES['image']['name']);
		$redimOK = fct_redim_image(120,80,$dossier[0],$dossier[1],$dossier[0],$dossier[1],$ext[1]);
		//if ($redimOK == 1) { echo 'Redimensionnement OK !';  }
		$img =file_get_contents ($_FILES['image']['tmp_name']);}
	
$sql = "INSERT INTO ".$tab."familles (id,nom,image) VALUES (?,?,?)";
$pdo->prepare($sql)->execute([NULL,$_REQUEST['nom_famille'],$img]);
}

if(isset($_REQUEST['modifier_famille'])){
		$img="";
if($_FILES['image']['tmp_name']<>""){	
		$dossier = explode("php",$_FILES['image']['tmp_name']);
		$dossier[1]="php".$dossier[1];
		$ext = explode(".",$_FILES['image']['name']);
		$redimOK = fct_redim_image(120,80,$dossier[0],$dossier[1],$dossier[0],$dossier[1],$ext[1]);
		//if ($redimOK == 1) { echo 'Redimensionnement OK !';  }
$img =file_get_contents ($_FILES['image']['tmp_name']);}
	
if($img==""){
$sql = "UPDATE ".$tab."familles SET nom=? WHERE id=?";
$pdo->prepare($sql)->execute([$_REQUEST['nom_famille'],$_REQUEST['modifier_famille']]);
	}else{
$sql = "UPDATE ".$tab."familles SET nom=?,image=? WHERE id=?";
$pdo->prepare($sql)->execute([$_REQUEST['nom_famille'],$img,$_REQUEST['modifier_famille']]);
	}
}


if(isset($_REQUEST['supprimer'])){
$sql = "DELETE FROM ".$tab."familles WHERE id = ?";
$pdo->prepare($sql)->execute([$_REQUEST['id']]);
echo "<div class='alert alert-success' >Familles ".$_REQUEST['id']." a ete supprimer .</div>";
}
// On récupère tout le contenu de la table familles
$reponse = $pdo->query("SELECT * FROM ".$tab."familles");

// On affiche chaque entrée une à une
while ($donnees = $reponse->fetch())
{
?>
    <tr>
      <th scope="row"><?php echo $donnees['id']; ?></th>
      <td id="nom_<?php echo $donnees['id']; ?>"><?php echo $donnees['nom']; ?></td>
      <td>
	  <img id="fam_<?php echo $donnees['id']; ?>" src="img/chargement_circle.gif" width="72" height="72" class="famille_<?php echo $donnees['id']; ?>" />
	  </td>
      <td>
	  <form  id="form_familles_<?php echo $donnees['id']; ?>">
	  <i class="fas fa-pencil-alt green"  onclick="voir_famille(<?php echo $donnees['id']; ?>)" title="Voir"></i>
	  <input type="hidden" name="menu" value="familles">
	  <input type="hidden" name="supprimer" value="familles">
	  <input type="hidden" value="<?php echo $donnees['id']; ?>" name="id">
	  <i class="fas fa-trash-alt red"  onclick="submit_familles(<?php echo $donnees['id']; ?>);"  title="Supprimer"></i>
	  </form>
	  </td>
    </tr>
<?php
}
echo "<script>famille_json();</script>";
$reponse->closeCursor(); // Termine le traitement de la requête

?>


  </tbody>
</table>