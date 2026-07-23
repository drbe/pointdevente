<!-- Trigger/Open The Modal
<button id="myBtn">Open Modal</button> -->
<!-- The Modal -->
<div id="myModal" class="modal">
	<!-- Modal content -->
	<div class="modal-content">
		<span class="close" id="close">&times;</span>
		<p>
		<div class="row">
			<div class="col-5">produit selectionné<b>
					<span id="nom_produit_popup"></span></b>
			</div>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1" title="Identifiant du produit"><span id="id_produit_popup"></span></span>
				</div>
				<input type="number" id="input_qte" class="form-control" placeholder="Quantité" aria-label="Username" aria-describedby="basic-addon1" onchange="cal_qte()" onkeyup="cal_qte()">
			</div>
			<div class="col" id="p_prix_u">0.000</div>
			<div class="col" id="p_prix_t">0.000</div>
			<div class="col">
				<button type="button" id="edit_pop" class="btn btn-info" onclick="close_pop_edit()">Modifier
				</button>
				<button type="button" id="fermer_pop" class="btn btn-danger" onclick="close_pop()">Supprimer
				</button>
			</div>
		</div>
		</p>
	</div>
</div>
<!--poup-->
<script src="popup/script.js"></script>
<!--popup-->