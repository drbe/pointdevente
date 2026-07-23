<div class="row">
	<div class="col-10">
		<h3>Produits</h3>
	</div>
	<div class="col" style="cursor:pointer;text-size:20px;">
		<i onclick="$('.Ajout_div').slideToggle('slow');" class="far fa-2x fa-plus-square" title="Ajouter produit"></i>
	</div>
</div>

<div class="Ajout_div" style="display:none">
	<form enctype="multipart/form-data" method="post" name="ajoutContact">
		<input type="hidden" name="menu" value="produits">

		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text" id="basic-addon1">Libellé</span>
			</div>
			<input type="text" name="nom_produit" class="form-control" placeholder="Libellé" aria-label="Libellé" aria-describedby="basic-addon1">
		</div>

		<div class="input-group mb-3">
			<input type="text" name="prix" onblur="corrige_prix()" class="form-control" placeholder="Prix" aria-label="Recipient's username" aria-describedby="basic-addon2">
			<div class="input-group-append">
				<span class="input-group-text" id="basic-addon2">En dianr </span><span class="input-group-text">(1.234)</span></span>
			</div>
		</div>
		<div class="input-group mb-3">
			<input type="text" name="tva" onblur="" class="form-control" placeholder="Tva" aria-label="">
			<div class="input-group-append">
				<span class="input-group-text">%</span></span>
			</div>
		</div>

		<div class="input-group mb-3">
			<input type="text" name="qte" class="form-control" placeholder="quantité">
		</div>

		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label class="input-group-text" for="inputGroupSelect01">Familles</label>
			</div>
			<select class="custom-select" id="inputGroupSelect01" name="famille">
				<option selected>Choix...</option>
				<?php
				$reponse = $pdo->query("SELECT * FROM " . $tab . "familles");
				while ($donnees = $reponse->fetch()) {
					echo '<option value="' . $donnees['id'] . '">' . $donnees['nom'] . '</option>';
				}
				?>
			</select>
		</div>
		<div class="row">
			<div class="col-9">
				<label for="basic-url">Code a barre</label>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon3">Exemple : 6 988188 701292</span>
					</div>
					<input type="text" name="codebarre" class="form-control" id="basic-url" aria-describedby="basic-addon3">
				</div>
			</div>
			<div class="col">
				<img src="img/code.png" width="92">
			</div>
		</div>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text">Couleur</span>
			</div>
			<input type="color" name="couleur" class="form-control" aria-label="Amount (to the nearest dollar)">
			<div class="input-group-append">
				<span class="input-group-text">#FFFFFFFF</span>
			</div>
		</div>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text">Description</span>
			</div>
			<textarea class="form-control" name="description" aria-label="With textarea"></textarea>
		</div>

		<div class="input-group">
			<div class="custom-file">
				<input type="file" name="image" class="custom-file-input" id="customFile" accept="image/png, image/jpeg, image/gif">
				<label class="custom-file-label" for="customFile">Choisir une image</label>
			</div>
		</div>
		<input type="hidden" name="ajout_produit" value="1">
		<button type="submit" class="btn btn-primary">Ajouter</button>
	</form>
</div>

<div class="alert alert-primary" role="alert">
	<form methode="get" action="admin.php">
		<div class="row">
			<input type="hidden" value="produits" name="menu">
			<select name="famille" class="col-3">
				<?php
				$reponse = $pdo->query("SELECT * FROM " . $tab . "familles");
				while ($donnees = $reponse->fetch()) {
					if (isset($_GET['famille']) && $_GET['famille'] == $donnees['id']) {
						echo "<option selected value='" . $donnees['id'] . "'>" . $donnees['nom'] . "</option>";
					} else {
						echo "<option value='" . $donnees['id'] . "'>" . $donnees['nom'] . "</option>";
					}
				}
				?>
			</select>
			<input type="submit" value="Recherche" class="btn btn-outline-success my-2 my-sm-0  col-3">
		</div>
	</form>
	<?php
	if (isset($_REQUEST['supprimer'])) {
		//$sql = "DELETE FROM " . $tab . "produits WHERE id = ?";
		$sql = "UPDATE " . $tab . "produits SET supprimer=1 WHERE id = ?";
		$pdo->prepare($sql)->execute([$_REQUEST['id']]);
		echo "Produit " . $_REQUEST['id'] . " a ete supprimer";
	} ?>

</div>

<table class="table  table-dark">
	<thead>
		<tr>
			<th scope="col">#</th>
			<th scope="col"><a href="<?php echo $url; ?>&order=nom">Nom</a></th>
			<th scope="col"><a href="<?php echo $url; ?>&order=prix">prix</a></th>
			<th scope="col"><a href="<?php echo $url; ?>&order=prix">tva</a></th>
			<th scope="col"><a href="<?php echo $url; ?>&order=qte">qte</a></th>
			<!--<th scope="col">Image</th>-->
			<th scope="col"><a href="<?php echo $url; ?>&order=famille">familles</a></th>
			<th scope="col">Nbr ventes</th>
			<th scope="col">Nbr achats</th>
			<th scope="col-2"><small>Options</small></th>
		</tr>
	</thead>
	<tbody>
		<?php
		/******************Ajout********************************/
		if (isset($_REQUEST['ajout_produit'])) {
			if ($_FILES['image']['tmp_name'] <> "") {
				$dossier = explode("php", $_FILES['image']['tmp_name']);
				$dossier[1] = "php" . $dossier[1];
				$ext = explode(".", $_FILES['image']['name']);
				$redimOK = fct_redim_image(120, 80, $dossier[0], $dossier[1], $dossier[0], $dossier[1], $ext[1]);
				//if ($redimOK == 1) { echo 'Redimensionnement OK !';  }
				$img = file_get_contents($_FILES['image']['tmp_name']);
			} else {
				$img = "";
			}
			$sql = "INSERT INTO " . $tab . "produits (id,nom,prix,famille,codebarre,couleur,description,image,qte,tva) VALUES (?,?,?,?,?,?,?,?,?,?)";
			$pdo->prepare($sql)->execute([
				NULL,
				$_REQUEST['nom_produit'],
				$_REQUEST['prix'],
				$_REQUEST['famille'],
				$_REQUEST['codebarre'],
				$_REQUEST['couleur'],
				$_REQUEST['description'],
				$img,
				$_REQUEST['qte'],
				$_REQUEST['tva']
			]);
		}
		/******************Modifier********************************/
		if (isset($_REQUEST['edit_produit'])) {
			if ($_FILES['image']['tmp_name'] <> "") {
				$dossier = explode("php", $_FILES['image']['tmp_name']);
				$dossier[1] = "php" . $dossier[1];
				$ext = explode(".", $_FILES['image']['name']);
				$redimOK = fct_redim_image(120, 80, $dossier[0], $dossier[1], $dossier[0], $dossier[1], $ext[1]);
				//if ($redimOK == 1) { echo 'Redimensionnement OK !';  }
				$img = file_get_contents($_FILES['image']['tmp_name']);
			} else {
				$img = "";
			}
			if ($img == "") {
				$sql = "UPDATE " . $tab . "produits SET nom=?,prix=?,famille=?,codebarre=?,couleur=?,description=?,qte=?,tva=? WHERE id=?";
				$pdo->prepare($sql)->execute([
					$_REQUEST['nom_produit'],
					$_REQUEST['prix'],
					$_REQUEST['famille'],
					$_REQUEST['codebarre'],
					$_REQUEST['couleur'],
					$_REQUEST['description'],
					$_REQUEST['qte'],
					$_REQUEST['tva'],
					$_REQUEST['edit_produit']
				]);
			} else {
				$sql = "UPDATE " . $tab . "produits SET nom=?,prix=?,famille=?,codebarre=?,couleur=?,description=?,image=?,tva=? WHERE id=?";
				$pdo->prepare($sql)->execute([
					$_REQUEST['nom_produit'],
					$_REQUEST['prix'],
					$_REQUEST['famille'],
					$_REQUEST['codebarre'],
					$_REQUEST['couleur'],
					$_REQUEST['description'],
					$img,
					$_REQUEST['tva'],
					$_REQUEST['edit_produit']
				]);
			}
		}


		$start = 0;
		$bloc = 10;
		$WHERE = "WHERE supprimer=0 ";
		if (isset($_GET['page'])) {
			$start = $bloc * ($_GET['page'] - 1);
		}
		if (isset($_GET['order'])) {
			$order = $_GET['order'];
		} else {
			$order = "id";
		}
		if (isset($_GET['famille'])) {
			$WHERE = "AND famille='" . $_GET['famille'] . "' ";
		}
		$sql = "SELECT * FROM " . $tab . "produits " . $WHERE . "  ORDER BY " . $order . " DESC LIMIT " . $start . "," . $bloc;
		$reponse = $pdo->query($sql);
		//echo $sql;
		while ($donnees = $reponse->fetch()) {
		?>
			<tr>
				<th scope="row"><?php echo $donnees['id']; ?></th>
				<td><?php echo $donnees['nom']; ?></td>
				<td align="right"><?php echo number_format($donnees['prix'], 3, ',', ' '); ?></td>
				<td align="right"><?php echo number_format($donnees['tva'], 1, ',', ' '); ?></td>
				<td align="center"><?php echo number_format($donnees['qte'], 2, ',', ' '); ?></td>
				<!--<td>
	  <img src="data:image/jpeg;base64,<?php //echo base64_encode( $donnees['image']);
										?>" width="72" height="72" />
	  </td>-->
				<td>
					<?php
					$rep = $pdo->query("SELECT * FROM " . $tab . "familles WHERE id=" . $donnees['famille'] . "");
					while ($df = $rep->fetch()) {
						echo $df['nom'];
					}
					?>
				</td>
				<td>
					<?php
					$respons = $pdo->query("SELECT SUM(" . $tab . "details_ticket.qte) as nbr FROM " . $tab . "details_ticket," . $tab . "ticket WHERE " . $tab . "ticket.id=" . $tab . "details_ticket.id_tickets && (" . $tab . "ticket.type=1 or " . $tab . "ticket.type=4) and " . $tab . "details_ticket.id_produit={$donnees['id']}");
					$data = $respons->fetch();

					if ($data["nbr"] <> "") {
						echo $data["nbr"];
					} else {
						echo 0;
					}
					?>
				</td>
				<td>
					<?php
					$respons = $pdo->query("SELECT SUM(" . $tab . "details_ticket.qte) as nbr FROM " . $tab . "details_ticket," . $tab . "ticket WHERE " . $tab . "ticket.id=" . $tab . "details_ticket.id_tickets && (" . $tab . "ticket.type=3) and " . $tab . "details_ticket.id_produit={$donnees['id']}");
					$data = $respons->fetch();

					if ($data["nbr"] <> "") {
						echo $data["nbr"];
					} else {
						echo 0;
					}
					?>
				</td>
				<td>
					<form id="form_vente_<?php echo $donnees['id']; ?>">
						<i class="fas fa-pencil-alt green" onclick="edit_produit(<?php echo $donnees['id']; ?>)" title="Voir" id="pen_<?php echo $donnees['id']; ?>"></i>
						<i class="fas fa-spinner fa-spin" id="charge_<?php echo $donnees['id']; ?>" style="display:none"></i>
						<input type="hidden" name="menu" value="produits">
						<input type="hidden" name="supprimer" value="familles">
						<input type="hidden" value="<?php echo $donnees['id']; ?>" name="id">
						<i class="fas fa-trash-alt red" onclick="submit_vente(<?php echo $donnees['id']; ?>);" title="Supprimer"></i>

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

			$reponse = $pdo->query("SELECT count(*) as total FROM " . $tab . "produits " . $WHERE);

			$data = $reponse->fetch();
			$max_page = ceil($data[0] / $bloc);
			//echo $start."/".$max_page;
			for ($i = 1; $i <= $max_page; $i++) {
				echo "<a class='btn btn-primary' href='admin.php?menu=produits&page=" . $i;
				echo (isset($_GET['famille'])) ? "&famille=" . $_GET['famille'] : "";
				echo "&order=";
				echo $order;
				echo "&dir=asc'>";
				echo (isset($_GET["page"]) && $_GET["page"] == $i) ? "<u>" . $i . "</u>" : $i;
				echo "</a> ";
			}
			?>
		</center>
	</div>
</div>