 <div class="row">
	<div class="col-10"><h3>Utilisateurs</h3></div>
	<div class="col" style="cursor:pointer;text-size:20px;">
	<i onclick="voir_utilisateur(-1);" class="far fa-2x fa-plus-square" title="Ajouter produit"></i>
	</div>
</div>

<div class="container-fluid">
<div class="alert alert-primary" role="alert">
<?php
	if(isset($_REQUEST['supprimer']))
	{
		$sql = "DELETE FROM ".$tab."utilisateurs WHERE id = ?";
		$pdo->prepare($sql)->execute([$_REQUEST['id']]);
		echo "supression utilisateurs ".$_REQUEST['id'];
	}
	if(isset($_REQUEST['edit_utilisateur_mod']))
	{
		$sql = "UPDATE ".$tab."utilisateurs SET nom = ?, prenom = ?,Nom_utilisateur=?,mot_pass=?,email = ?,  tel = ?, gsm = ? , etat = ?, descriptions = ? WHERE id = ?;";
		$pdo->prepare($sql)->execute([$_REQUEST['nom'],$_REQUEST['prenom'],$_REQUEST['Nom_utilisateur'],$_REQUEST['mot_pass'],$_REQUEST['email'],$_REQUEST['tel'],$_REQUEST['gsm'],$_REQUEST['etat'],$_REQUEST['descriptions'],$_REQUEST['edit_utilisateur_mod']]);
		echo "Modification effectuer pour utilisateur ".$_REQUEST['edit_utilisateur_mod'];

	}
	if(isset($_REQUEST['ajout_utilisateur']))
	{
		$sql = "INSERT INTO ".$tab."utilisateurs (nom, prenom, Nom_utilisateur, mot_pass, email, tel, gsm, etat, descriptions) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$pdo->prepare($sql)->execute([$_REQUEST['nom'],$_REQUEST['prenom'],$_REQUEST['Nom_utilisateur'],$_REQUEST['mot_pass'],$_REQUEST['email'],$_REQUEST['tel'],$_REQUEST['gsm'],$_REQUEST['etat'],$_REQUEST['descriptions']]);
		echo "Ajout effectuer pour l'utilisateur ".$_REQUEST['nom'].",".$_REQUEST['prenom'];

	}
?>
</div>
	<table class="table  table-dark">
	  <thead>
		<tr>
		  <th scope="col">#</th>
		  <th scope="col"><a href="<?php echo $url;?>&order=nom">Nom</a></th>
		  <th scope="col"><a href="<?php echo $url;?>&order=prenom">Prenom</a></th>
		  <th scope="col"><a href="<?php echo $url;?>&order=tel">nom utilisateur</th>
		  <th scope="col"><a href="<?php echo $url;?>&order=tel">Tel</a></th>
		  <th scope="col"><a href="<?php echo $url;?>&order=tel">Gsm</a></th>
		  <th scope="col"><a href="<?php echo $url;?>&order=adresse">Email</a></th>
		  <th scope="col"><a href="<?php echo $url;?>&order=type">Etat</a></th>
		  <th scope="col-2"><small>Option</small></th>
		</tr>
	  </thead>
	  <tbody>
	  <?php
	// On récupère tout le contenu de la table familles
	$start=0;
	$bloc=10;
	$WHERE="";
	if(isset($_GET['page'])){$start=$bloc*($_GET['page']-1);}
	if(isset($_GET['order'])){$order=$_GET['order'];}else{$order="id";}
	//if(isset($_GET['recherche_date'])){$WHERE="WHERE date(date_insert)='".$_GET['recherche_date']."'";}else{$WHERE="";}
	$sql="SELECT * FROM ".$tab."utilisateurs ".$WHERE." ORDER BY ".$order." DESC LIMIT ".$start.",".$bloc;
	$reponse = $pdo->query($sql);
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch())
	{
	?>
		<tr>
		  <th scope="row"><?php echo $donnees['id']; ?></th>

		  <td align="right"><?php echo $donnees['nom']; ?></td>
		  <td><?php echo $donnees['prenom']; ?></td>
		  <td><?php echo $donnees['Nom_utilisateur']; ?></td>

		  
		  <td><?php echo $donnees['tel']; ?></td>
		  <td><?php echo $donnees['gsm']; ?></td>
		  <td><?php echo $donnees['email']; ?></td>
		  <td>
		  <?php 
		  switch ($donnees['etat'])
		  {
			  case "2":
			  echo "Vendeur";
			  break;
			  case "3":
			  echo "Administrateur";
			  break;
			  default:
			  echo "Compte bloqué";
		  }
		  //echo $donnees['etat']; 
		  ?>
		  </td>
		  <td>
		  <form id="form_utilisateur_<?php echo $donnees['id']; ?>">
		  <i class="fas fa-pencil-alt green"  onclick="voir_utilisateur(<?php echo $donnees['id']; ?>)" title="Voir"></i>
		  <input type="hidden" name="menu" value="utilisateurs">
		  <input type="hidden" name="supprimer" value="utilisateur">
		  <input type="hidden" value="<?php echo $donnees['id']; ?>" name="id">
	  <i class="fas fa-trash-alt red"  onclick="submit_utilisateur(<?php echo $donnees['id']; ?>);"  title="Supprimer"></i>
		  </form>
		  </td>
		</tr>

	<?php
	}

	$reponse->closeCursor(); // Termine le traitement de la requête

	?>
	  </tbody>
</table>
	<div class="alert alert-secondary row" role="alert">
	<div class="col">
		<center>
		<?php
		
		$reponse = $pdo->query("SELECT count(*) as total FROM ".$tab."client ".$WHERE);
		$data = $reponse->fetch();
		$max_page=ceil($data[0]/$bloc);
		//echo $start."/".$max_page;
		for ($i=1;$i<=$max_page;$i++)
		{
			echo "<a class='btn btn-primary' href='admin.php?menu=clients&page=".$i;
			echo (isset($_GET['recherche_date']))? "&recherche_date=".$_GET['recherche_date'] : "";
			echo "&order=";
			echo $order;
			echo "&dir=asc'>";
			echo (isset($_GET["page"]) && $_GET["page"]==$i) ?"<u>".$i."</u>" :$i;
			echo "</a> ";
		}
		?>
		</center>
	</div>
	</div>
</div>
