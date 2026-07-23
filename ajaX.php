<?php
include('fonctions.php');
header('Access-Control-Allow-Origin: *');
switch ($_REQUEST["demande"]) {
	case "famille":
		$reponse = $pdo->query("SELECT id,nom,prix FROM " . $tab . "produits WHERE supprimer=0 AND famille =" . $_REQUEST['famille']);

		$i = 0;
		while ($donnees = $reponse->fetch()) {
?>
			<div class="produit" onclick="produit(<?php echo $donnees['id']; ?>)" nom="<?php echo $donnees['nom']; ?>" prix="<?php echo $donnees['prix']; ?>" id="select_produit_<?php echo $donnees['id']; ?>">
				<div class="row">
					<div class="col-4">
						<img id="pro_<?php echo $donnees['id']; ?>" class="pro_<?php echo $i; ?>" src="img/chargement_circle.gif" width="72" height="72" />
					</div>
					<div class="col ajx_nom_produit" style="" title="<?php echo $donnees['nom']; ?>">
						<?php echo $donnees['nom']; ?>
					</div>
				</div>
				<div class="row ajx_total">
					<p class="total"><?php echo number_format($donnees['prix'], 3, ',', ' '); ?></p>
				</div>
			</div>
		<?php $i++;
		}
		if ($i == 0) {
			echo '<small><i style="color:#ccc">Cette famille de produit est vide </i></small>';
		}
		break;
	case "produit_json":
		$reponse = $pdo->query("SELECT image FROM " . $tab . "produits WHERE id =" . $_REQUEST['produit']);
		while ($donnees = $reponse->fetch()) {
			echo base64_encode($donnees['image']) . "," . $_REQUEST['produit'];
		}
		break;

	case "famille_json":
		$reponse = $pdo->query("SELECT image FROM " . $tab . "familles WHERE id =" . $_REQUEST['famille']);
		while ($donnees = $reponse->fetch()) {
			echo base64_encode($donnees['image']) . "," . $_REQUEST['famille'];
		}
		break;

	case "produit";
		$col = 0;
		$reponse = $pdo->query("SELECT * FROM " . $tab . "produits WHERE id =" . $_POST['id']);
		while ($donnees = $reponse->fetch()) {
		?>
			<tr class="couleur-<?php echo $col; ?>" onclick="edit(<?php echo $donnees['id']; ?>)" id="produit_a_<?php echo $donnees['id']; ?>">
				<td class="col-5" id="nom_produit_<?php echo $donnees['id']; ?>"><?php echo $donnees['nom']; ?></td>
				<td class="col text-right" id="qte_<?php echo $donnees['id']; ?>">1</td>
				<td class="col text-right" id="pu_<?php echo $donnees['id']; ?>"><?php echo round($donnees['prix'], 3); ?></td>
				<td class="col text-right" id="pt_<?php echo $donnees['id']; ?>" name="pt"><?php echo round($donnees['prix'], 3); ?></td>
			</tr>
		<?php   }
		break;
	case "valider";
		$Latitude = "0.0000000";
		$Longitude = "0.0000000";
		$Altitude = "0.0000000";
		if (isset($_REQUEST['Latitude'])) {
			$Latitude = $_REQUEST['Latitude'];
		}
		if (isset($_REQUEST['Longitude'])) {
			$Longitude = $_REQUEST['Longitude'];
		}
		if (isset($_REQUEST['Altitude'])) {
			$Altitude = $_REQUEST['Altitude'];
		}

		$type = $_REQUEST["type"];
		$total = $_POST['total'];
		$nbr = $_POST['nbr'];

		$reponse = $pdo->query("SELECT max(id_insertion) as nx FROM " . $tab . "ticket WHERE type=" . $type);
		$donnees = $reponse->fetch();
		$id_ticket = ($donnees["nx"] + 1);

		$sql = "INSERT INTO " . $tab . "ticket (id, id_insertion, total, total_tva, total_ht, nbr_produits, Latitude, Longitude, Altitude, id_client, date_insert, timbre, enligne, description,id_utilisateur,type,vu) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$pdo->prepare($sql)->execute([NULL, $id_ticket, $total, $_POST['total_tva'], $_POST['total_ht'], $nbr, $Latitude, $Longitude, $Altitude, $_POST['id_client'], "" . date("Y-m-d H:i:s") . "", "0.600", "1", " ", $_SESSION['id'], $type, 0]);

		$reponse = $pdo->query("SELECT max(id) as id FROM " . $tab . "ticket");
		$donnees = $reponse->fetch();
		$id_ticket = ($donnees["id"]);

		$data = explode(';', $_POST["ch"]);
		foreach ($data as $value) {
			if ($value <> "") {
				$produit = explode(',', $value);
				$id = $produit[0];
				$qte = $produit[1];
				$date_insertion = date("Y-m-d H:i:s");
				$sql = "INSERT INTO " . $tab . "details_ticket (id,id_produit,qte,id_tickets,date_insert) VALUES (?,?,?,?,?)";
				$pdo->prepare($sql)->execute([NULL, $id, $qte, $id_ticket, $date_insertion]);

				$prods = $pdo->query("SELECT qte FROM " . $tab . "produits WHERE id =" . $id);
				$prod = $prods->fetch();
				switch ($type) {
					case "1":
						$new_qte = $prod['qte'] - $qte;
						break;
						/*case "2":
			$new_qte=$qte;
			break;*/
					case "3":
						$new_qte = $prod['qte'] + $qte;
						break;
					case "4":
						$new_qte = $prod['qte'] - $qte;
						break;
				}
				if ($type <> 2) {
					$sql = "UPDATE " . $tab . "produits SET qte=? WHERE id = ? ";
					$pdo->prepare($sql)->execute([$new_qte, $id]);
				}
			}
		}
		include('facture.php');
		break;
		/*
case "valider";
	switch ($_REQUEST["type"])
	{
		case "1":
		//facture
		break;
		case "2":
		//devis
		break;
		case "3":
		//bon de commande
		break;
	}
	$reponse = $pdo->query("SELECT max(id_tickets) as nx FROM ".$tab."details_ticket ");
	$donnees = $reponse->fetch();
	
		$id_ticket=($donnees["nx"]+1);
		$total=$_POST['total'];
		$nbr=$_POST['nbr'];

$data = explode( ';', $_POST["ch"] );
foreach($data as $value)
{
	if($value<>"")
	{
		$produit=explode( ',', $value );
		$id=$produit[0];
		$qte=$produit[1];
		$date_insertion=date("Y-m-d H:i:s");
		$sql = "INSERT INTO ".$tab."details_ticket (id,id_produit,qte,id_tickets,date_insert) VALUES (?,?,?,?,?)";
		$pdo->prepare($sql)->execute([NULL,$id,$qte,$id_ticket,$date_insertion]);
		
		$prods = $pdo->query("SELECT qte FROM ".$tab."produits WHERE id =".$id);
		$prod = $prods->fetch();
		
		$sql = "UPDATE ".$tab."produits SET qte=? WHERE id = ? ";
		$pdo->prepare($sql)->execute([($prod['qte']-$qte),$id]);
		

	}
}
$Latitude="0.0000000";
$Longitude="0.0000000";
$Altitude="0.0000000";
if(isset($_REQUEST['Latitude'])){$Latitude=$_REQUEST['Latitude'];}
if(isset($_REQUEST['Longitude'])){$Longitude=$_REQUEST['Longitude'];}
if(isset($_REQUEST['Altitude'])){$Altitude=$_REQUEST['Altitude'];}

$sql="INSERT INTO ".$tab."ticket (id, id_insertion, total, total_tva, total_ht, nbr_produits, Latitude, Longitude, Altitude, id_client, date_insert, timbre, enligne, description,id_utilisateur) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$pdo->prepare($sql)->execute([NULL,$id_ticket,$total,$_POST['total_tva'],$_POST['total_ht'],$nbr,$Latitude,$Longitude,$Altitude,$_POST['id_client'],date("Y-m-d H:i:s"),"0.600","1","",$_SESSION['id']]);
include('facture.php');
break;
*/
	case "ajoute_client":
		$sql = "INSERT INTO " . $tab . "client (id, nom, prenom, adresse, tel, gsm, email, Latitude, Longitude, MF, type, note) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
		$pdo->prepare($sql)->execute([NULL, $_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['tel'], $_POST['gsm'], $_POST['email'], $_POST['Latitude'], $_POST['Longitude'], $_POST['MF'], $_POST['type'], $_POST['note']]);
		$reponse = $pdo->query("SELECT max(id) as nx FROM " . $tab . "client ");
		$donnees = $reponse->fetch();

		echo $donnees["nx"] . "," . $_POST['nom'] . " " . $_POST['prenom'];

		break;
	case "facture":
		$sql = "UPDATE ";
		$sql = "UPDATE " . $tab . "ticket SET vu=? WHERE id=?";
		$pdo->prepare($sql)->execute([$_REQUEST['vu'], $_REQUEST['facture_id']]);
		include('facture.php');
		break;
	case "code_barre":
		$reponse = $pdo->query("SELECT id,nom,prix FROM " . $tab . "produits WHERE codebarre='" . $_POST['code'] . "'");
		$donnees = $reponse->fetch();
		?>
		<div i="x" j="<?php echo $donnees['id']; ?>" class="produit" onclick="produit(<?php echo $donnees['id']; ?>)" nom="<?php echo $donnees['nom']; ?>" prix="<?php echo $donnees['prix']; ?>" id="select_produit_<?php echo $donnees['id']; ?>">
			<div class="row">
				<div class="col ajx_nom_produit" style="" title="<?php echo $donnees['nom']; ?>">
					<?php echo $donnees['nom']; ?>
				</div>
			</div>
			<div class="row ajx_total">
				<p class="total"><?php echo number_format($donnees['prix'], 3, ',', ' '); ?></p>
			</div>
		</div>
<?php
		break;
}
?>