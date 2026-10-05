<?php //require('fonctions.php');
?>
<script>
	$('body').css('background-color', '#fff');
</script>
<style>
	.container {
		background-color: #fff;
		color: #000;

	}

	.containerA4 {
		width: 210mm;
		height: 295mm;
		/*297mm;*/
		margin-left: auto;
		margin-right: auto;
		padding: 10px;
		color: #000;
	}

	.center {
		text-align: center;
	}

	h2 {
		background-color: #fff;
		color: #000;
	}

	.carre {
		width: 250px;
	}

	.carre1 {
		width: 120px;
		height: 70px;
		border-radius: 20px;
		border: 1px solid #000;
	}

	.carre_0000 {
		border: 1px solid #000;
	}

	.carre_idfacture {
		border: 1px solid #000;
		border-radius: 0px 0px 20px 0px;
		margin-left: 15px;
	}

	.carre_idclient {
		border: 1px solid #000;
		border-radius: 20px 20px 0px 0px;
		margin-left: 15px;
		margin-right: 15px;
	}

	.carre_total {
		border: 1px solid #000;
		border-radius: 0px 0px 20px 20px;
		margin-left: 0px;
		margin-right: 0px;
	}

	.top_padding {
		padding-top: 10px;
	}

	.b_l {
		border-left: 1px solid #000;
	}

	.no_border {
		border: none !important;
	}

	.total {
		border-radius: 0px 20px 20px 0px;
		font-size: 25px;
		font-weight: bold;
	}

	#calcule {
		/* margin-top: 10px; */
		margin-left: 15px;
		margin-right: 15px;
	}

	#calcule .col,
	#calcule col-5 {
		border: 1px solid #000;
	}

	.gros {
		background-color: #ffdbce;
		font-weight: bold;
	}

	#titres {
		margin-left: 0px;
		margin-right: 0px;
		margin-top: 10px;
	}

	#produits {
		margin-left: 0px;
		margin-right: 0px;
		font-size: 14px;
		font-family: system-ui;
	}

	#produits div {
		border-left: 1px solid #a8a8a8;
		border-right: 1px solid #a8a8a8;
	}

	.ttcAlign {
		text-align: right;
	}

	#produits .col-5 div {
		border: none;
		font-weight: 600;
	}

	#titres div {
		border: 1px solid #000;
	}

	#date {
		float: right;
		display: none;
	}

	#information_societe_col_1 {
		margin-left: 15px;
	}

	.sousProduitsExtensible {
		height: 320px;
	}
</style>
<div class="print_options" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
	<button type="button" class="btn btn-danger" onclick="window.location.reload() ">
		Quitter
	</button>
	<button type="button" class="btn btn-primary" onclick="$('.print_options').hide();window.print();$('.print_options').show();">
		Imprimer
	</button>
	<button type="button" class="btn btn-success" onclick="PDF('to_pdf')">PDF</button>
	<div style="display: inline-flex; align-items: center; gap: 6px; margin-left: 15px; background: #f8f9fa; padding: 6px 12px; border-radius: 6px; border: 1px solid #ced4da; cursor: pointer;">
		<input type="checkbox" id="chk_signature" checked onchange="toggleSignature(this.checked)" style="cursor: pointer; width: 18px; height: 18px; margin: 0;">
		<label for="chk_signature" style="cursor: pointer; user-select: none; margin: 0; font-weight: 600; color: #333;">Avec signature</label>
	</div>
</div>

<script>
function toggleSignature(show) {
	if (show) {
		$('#bloc_signature').show();
	} else {
		$('#bloc_signature').hide();
	}
	localStorage.setItem('facture_avec_signature', show ? 'true' : 'false');
}

$(document).ready(function() {
	var pref = localStorage.getItem('facture_avec_signature');
	if (pref === 'false') {
		$('#chk_signature').prop('checked', false);
		$('#bloc_signature').hide();
	} else {
		$('#chk_signature').prop('checked', true);
		$('#bloc_signature').show();
	}
});
</script>


<div class="container">
	<div class="containerA4" id="to_pdf">
		<div class="header">
			<div class="row" id="information_societe">
				<div class="col logo">
					<img src="img/logo_mot.jpg" width="100px">
				</div>
				<div class="carre" id="information_societe_col_1">
					<div>MODERN Orthodontics Tunisia</div>
					<div>MF : 1591615/W/P/M/000</div>
					<div>Adresse WEB : mot.tn</div>
					<div>Email : contact@mot.tn</div>
				</div>
				<div class="col center ">
					<img src="img/logo.jpg" width="350px">
				</div>
				<!-- <div class="col center">
					<div class="carre1" id="date">
						<p><?php echo date("d/m/Y"); ?></p>
						<p><?php echo date("H:i"); ?></p>
					</div>
				</div> -->
			</div><!--information_societe-->
			<?php
			if (isset($_REQUEST['facture_id'])) {
				$sql = "SELECT * FROM " . $tab . "ticket WHERE id=" . $_REQUEST['facture_id'];
			} else {
				$sql = "SELECT * FROM " . $tab . "ticket order by id desc limit 0,1";
			}
			$reponse = $pdo->query($sql);
			$ticket = $reponse->fetch();

			$clients = $pdo->query("SELECT * FROM " . $tab . "client WHERE id = " . $ticket['id_client']);
			$client = $clients->fetch();

			?>
			<div class="row top_padding">
				<div class="col-4 carre_idfacture">
					<div class="row justify-content-center">
						<h2 onclick="window.print()" style="margin-top: 2px;">

							<?php
							switch ($ticket['type']) {
								case "1":
									echo "Facture";
									break;
								case "2":
									echo "Devis";
									break;
								case "3":
									echo "Bon de commande";
									break;
								case "4":
									echo "Bon de livraison";
									break;
							}
							?>
						</h2>
					</div>
					<div class="row gros text-center" style="margin: 0;">
						<div class="col-3" style="padding: 2px 1px;">
							N°
						</div>
						<div class="col-5" style="padding: 2px 1px;">
							Date
						</div>
						<div class="col-4" style="padding: 2px 1px;">
							N° Client
						</div>
					</div>
					<div class="row text-center" style="margin: 0;">
						<div class="col-3" style="padding: 2px 1px;">
							<?php echo $ticket['id_insertion']; ?>
						</div>
						<div class="col-5" style="padding: 2px 1px;">
							<?php echo date("d/m/Y", strtotime($ticket['date_insert'])); ?>
						</div>
						<div class="col-4" style="padding: 2px 1px;">
							<?php echo $ticket['id_client']; ?>
						</div>
					</div>
				</div>
				<div class="col carre_idclient">

					<div class="row  justify-content-center gros" style="border-radius: 20px 20px 0px 0px;">
						<b><?php echo $client['nom'] . " " . $client['prenom']; ?></b>
					</div>
					<div class="row">
						<div class="col-2 gros">
							MF
						</div>
						<div class="col">
							<?php echo $client['MF']; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-2 gros">
							TEL
						</div>
						<div class="col">
							<?php echo $client['tel'] . "/" . $client['gsm'] . "/" . $client['email']; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-2 gros">
							Adresse
						</div>
						<div class="col">
							<?php echo $client['adresse']; ?>
						</div>
					</div>
				</div>
			</div><!--information_societe-->
		</div><!--header-->

		<div class="produits">
			<div style="height: 600px;"><!--tableau produits-->
				<div class="row gros" id="titres">
					<div class="col-5">Désignation
					</div>
					<div class="col">Quantité
					</div>
					<div class="col-1">TVA%
					</div>
					<div class="col">Prix U
					</div>
					<div class="col">T HT
					</div>
					<div class="col">TTC
					</div>
				</div><!--titres-->
				<?php
				$reponse = $pdo->query("SELECT * FROM " . $tab . "details_ticket WHERE id_tickets=" . $ticket['id']);
				$nbr_prod = 0;
				$total_tht = 0;
				$total_ttc = 0;
				$timbre = 1;
				while ($details = $reponse->fetch()) {
					$nbr_prod++;
					$produits = $pdo->query("SELECT * FROM " . $tab . "produits WHERE id = " . $details['id_produit']);
					$produit = $produits->fetch();
					//echo $details['id_produit']." ".$produit['nom']."<br>";
					$tva = $produit['tva'];
					$tva_val = (1 + ($tva / 100));
					$qte = number_format($details['qte'], 2);
					$pu = number_format(($produit['prix'] / $tva_val), 3, '.', '');
					$tht = number_format(($pu * $qte), 3, '.', '');
					$ttc = number_format(($produit['prix'] * $qte), 3, '.', '');

					$total_tht += $tht;
					$total_ttc += $ttc;
					$net = $total_ttc + $timbre;

				?>
					<div class="row" id="produits">
						<div class="col-5">
							<div><?php echo $produit['nom']; ?></div>
							<!--<div> <img src="data:image/jpeg;base64,<?php /*echo base64_encode($produit['image']);*/ ?>" width="72" height="72" style="margin-bottom: 2px;" />
						</div>-->
						</div>
						<div class="col"><?php echo $qte; ?>
						</div>
						<div class="col-1"><?php echo $tva; ?>
						</div>
						<div class="col"><?php echo $pu; ?>
						</div>
						<div class="col"><?php echo $tht; ?>
						</div>
						<div class="col ttcAlign"><?php echo $ttc; ?>
						</div>
					</div><!--produits-->
				<?php } ?>
				<div class="row sousProduitsExtensible" id="produits" style="height:<?= max(80, 310 - (25 * $nbr_prod)) ?>px">
					<div class="col-5">
					</div>
					<div class="col">
					</div>
					<div class="col-1">
					</div>
					<div class="col">
					</div>
					<div class="col">
					</div>
					<div class="col">
					</div>
				</div><!--produits-->
				<div class="row carre_total" id="total">
					<div class="col">
						<?php echo $nbr_prod; ?> produit(s)
					</div>
					<div class="col">
					</div>
					<div class="col">
					</div>
					<div class="col b_l  gros">Total
					</div>
					<div class="col b_l"><?php echo number_format($total_tht, 3, '.', ''); ?>
					</div>
					<div class="col b_l"><?php echo number_format($total_ttc, 3, '.', ''); ?>
					</div>
				</div><!--total-->

			</div>
			<div id="calcule">
				<div class="row" id="calcule1">
					<div class="col  gros">Total TVA :
					</div>
					<div class="col"><?php echo number_format($total_ttc - $total_tht, 3, '.', ''); ?>
					</div>
					<div class="col  gros">Net à payer
					</div>
					<div class="col total"><?php echo number_format($net, 3, '.', ''); ?>
					</div>
				</div><!--calcule1-->
				<div class="row" id="calcule2">
					<div class="col gros">Total HT :
					</div>
					<div class="col"><?php echo number_format($total_tht, 3, '.', ''); ?>
					</div>
					<div class="col no_border">
					</div>
					<div class="col no_border">Timbre fiscale <?php echo number_format($timbre, 3, '.', ''); ?>
					</div>
				</div><!--calcule2-->
				<div class="row top_padding" id="calcule3">
					<div class="col  gros">Mode réglement :
					</div>
					<div class="col">Espèce
					</div>
					<div class="col no_border">
					</div>
					<div class="col no_border">
					</div>
				</div><!--calcule3-->
				<div class="row" style="margin-top: 5px; align-items: flex-start;">
					<div class="col-7">
						Arrêtée la présente FACTURE à la somme de :<br>
						<b><?php echo Lettre(number_format($net, 3, '.', '')); ?></b>
					</div>
					<div id="bloc_signature" class="col-5 text-right" style="text-align: right; margin-top: -30px;">
						<div style="font-weight: bold; margin-bottom: 2px;">Signature & Cachet</div>
						<img src="img/signature.png" style="max-width: 145px; height: auto; display: inline-block;">
					</div>
				</div>
			</div><!--calcule-->
		</div>
	</div>