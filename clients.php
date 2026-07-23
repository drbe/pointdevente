 <div class="row">
	<div class="col-10"><h3>Clients</h3></div>
	<div class="col" style="cursor:pointer;text-size:20px;">
	<i onclick="voir_client(-1);" class="far fa-2x fa-plus-square" title="Ajouter produit"></i>
	</div>
</div>
<div class="container-fluid">
<div class="alert alert-primary" role="alert">
<?php
	if(isset($_REQUEST['supprimer']))
	{
		$sql = "DELETE FROM ".$tab."client WHERE id = ?";
		$pdo->prepare($sql)->execute([$_REQUEST['id']]);
		echo "supression client ".$_REQUEST['id'];
	}
	if(isset($_REQUEST['edit_client_mod']))
	{
		$sql = "UPDATE ".$tab."client SET nom = ?, prenom = ?, adresse = ?, tel = ?, gsm = ?, email = ?, Latitude = ?, Longitude = ?, type = ?, note = ?, MF = ? WHERE id = ?";
		$req=$pdo->prepare($sql) or die(print_r($pdo->errorInfo()));
		$req->execute([$_REQUEST['nom'],$_REQUEST['prenom'],$_REQUEST['adresse'],$_REQUEST['tel'],$_REQUEST['gsm'],$_REQUEST['email'],$_REQUEST['Latitude'],$_REQUEST['Longitude'],$_REQUEST['type'],$_REQUEST['note'],$_REQUEST['MF'],$_REQUEST['edit_client_mod']]) ;
				echo "Client ".$_REQUEST['nom']." ".$_REQUEST['prenom']." modifier ";


	}
	if(isset($_REQUEST['ajout_client']))
	{
		$sql = "INSERT INTO ".$tab."client (id, nom, prenom, adresse, tel, gsm, email, Latitude, Longitude, type, note, MF) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$pdo->prepare($sql)->execute([$_REQUEST['nom'],$_REQUEST['prenom'],$_REQUEST['adresse'],$_REQUEST['tel'],$_REQUEST['gsm'],$_REQUEST['email'],$_REQUEST['Latitude'],$_REQUEST['Longitude'],$_REQUEST['type'],$_REQUEST['note'],$_REQUEST['MF']]);
		echo "Client ".$_REQUEST['nom']." ".$_REQUEST['prenom']." ajouter";

	}
?>
</div>
	<table class="table  table-dark">
	  <thead>
		<tr>
		  <th scope="col">#</th>
		  <th scope="col"><a href="<?php echo $url;?>&order=nom">Nom</a></th>
		  <th scope="col"><a href="<?php echo $url;?>&order=prenom">Prenom</a></th>
		  <th scope="col">gps</th>
		  <th scope="col"><a href="<?php echo $url;?>&order=tel">Tel</a></th>
		  <th scope="col"><a href="<?php echo $url;?>&order=adresse">Adresse</a></th>
		  <th scope="col">Nbr produits acheter</th>
		  <th scope="col"><a href="<?php echo $url;?>&order=type">Type</a></th>
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
	$sql="SELECT * FROM ".$tab."client ".$WHERE." ORDER BY ".$order." DESC LIMIT ".$start.",".$bloc;
	$reponse = $pdo->query($sql);
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch())
	{
	?>
		<tr>
		  <th scope="row"><?php echo $donnees['id']; ?></th>

		  <td align="right"><?php echo $donnees['nom']; ?></td>
		  <td><?php echo $donnees['prenom']; ?></td>
		  <td><?php echo "<a href='https://www.google.com/maps/search/".$donnees['Latitude'].",".$donnees['Longitude']."'>"; ?>Voir l'emplacement</a>
</p>
<div class="collapse" id="map_<?php echo $donnees['id']; ?>">
  <div class="card card-body">
  </div>
</div>

		  </td>
		  
		  <td><?php echo $donnees['tel']; ?></td>
		  <td><?php echo $donnees['adresse']; ?></td>
		  <td><?php 
		  $respons = $pdo->query("SELECT sum(nbr_produits) as nbr FROM ptv_ticket WHERE (type=1 or type=4) and id_client={$donnees['id']}");
		  		$data = $respons->fetch();

		if($data["nbr"]<>""){echo $data["nbr"];}else{echo 0;}

		  ?></td>
		  <td><?php echo $donnees['type']; ?></td>
		  <td>
		  <form id="form_clients_<?php echo $donnees['id']; ?>">
		  <i class="fas fa-pencil-alt green"  onclick="voir_client(<?php echo $donnees['id']; ?>)" title="Voir"></i>
		  <input type="hidden" name="menu" value="clients">
		  <input type="hidden" name="supprimer" value="client">
		  <input type="hidden" value="<?php echo $donnees['id']; ?>" name="id">
	  <i class="fas fa-trash-alt red"  onclick="submit_clients(<?php echo $donnees['id']; ?>);" title="Supprimer"></i>
		  </form>
		  </td>
		</tr>

	<?php
	}

	//$reponse->closeCursor(); // Termine le traitement de la requête

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
