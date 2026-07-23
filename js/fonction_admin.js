function famille(id) {
	$.ajax({
		method: "POST",
		url: "ajaX.php",
		data: { famille: id }
	})
		.done(function (msg) {
			alert("Data Saved: " + msg);
		});
}
function corrige_prix() {
	prix = parseFloat($("[name='prix']").val());
	$("[name='prix']").val(prix.toFixed(3));
}
function voir_vente(id) {
	$.ajax({
		method: "POST",
		url: "ajaX.php",
		data: {
			demande: "facture",
			facture_id: id,
			vu: 1
		}
	})
		.done(function (msg) {
			$('#pop_up .pop_upContainer').html(msg)
			$('.pop_up').show();
			$('#ligne_' + id).css('font-weight', '');
		});
}
function edit_produit(id) {
	$('#charge_' + id).show();
	//$('#pen_' + id).hide();
	$.ajax({
		method: "POST",
		url: "edit_produit.php",
		data: {
			id: id
		}
	})
		.done(function (msg) {
			$('#pop_up .pop_upContainer').html(msg)
			$('.pop_up').show();
			$('#charge_' + id).hide();
			$('#pen_' + id).show();

		});
}
/*suppression vente*/
function submit_vente(id) {
	$('#pop_up .pop_upContainer').html("<div class='alert alert-danger' role='alert'>Etes vous sur de vouloir supprimer ?");
	$('#pop_up .pop_upContainer').html($('#pop_up .pop_upContainer').html() + "<center><button class='btn btn-outline-success' onclick=$('#form_vente_" + id + "').submit() >OUI</button><button  class='btn btn-outline-danger' onclick=$('.pop_up').hide() >NON</button></div></center>")
	$('.pop_up ').show();
}
/*suppression vente*/
function submit_familles(id) {
	$('#pop_up .pop_upContainer').html("<div class='alert alert-danger' role='alert'>Etes vous sur de vouloir supprimer ?");
	$('#pop_up .pop_upContainer').html($('#pop_up .pop_upContainer').html() + "<center><button class='btn btn-outline-success' onclick=$('#form_familles_" + id + "').submit() >OUI</button><button  class='btn btn-outline-danger' onclick=$('.pop_up').hide() >NON</button></div></center>")
	$('.pop_up').show();
}
/*modification*/
function voir_famille(id) {
	var html_fam = '<form enctype="multipart/form-data"  method="post" name="ajoutContact" >';
	html_fam += '<input type="hidden" name="menu" value="familles">';
	html_fam += '<input type="hidden" name="modifier_famille" value="' + id + '">';
	html_fam += '<div class="form-group">';
	html_fam += '<label for="exampleInputEmail1">Nom de famille</label>'
	html_fam += '<input type="text" name="nom_famille" class="form-control" id="exampleInputEmail1" placeholder="Saisir un nom de famille" value="' + $("#nom_" + id).html() + '" >';
	html_fam += '<small id="emailHelp" class="form-text text-muted">Nom de famille.</small></div>';
	html_fam += '<div class="custom-file">';
	html_fam += '<input type="file" name="image" class="custom-file-input" id="customFile" accept="image/png, image/jpeg, image/gif">';
	html_fam += '<label class="custom-file-label" for="customFile" >Choisir une image</label>';
	html_fam += '</div>';
	html_fam += '<button type="submit" class="btn btn-primary">Modifier</button><br>';
	html_fam += '<img class="famille_' + id + '" id="fam_img_' + id + '" src="img/chargement_circle.gif">';
	html_fam += '</form>';

	$('#pop_up .pop_upContainer').html(html_fam);
	$('.pop_up').show();
	$('#fam_img_' + id).attr('src', $('#fam_' + id).attr('src'));
}

/*suppression client*/
function submit_utilisateur(id) {
	$('#pop_up .pop_upContainer').html("<div class='alert alert-danger' role='alert'>Etes vous sur de vouloir supprimer ?");
	$('#pop_up .pop_upContainer').html($('#pop_up .pop_upContainer').html() + "<center><button class='btn btn-outline-success' onclick=$('#form_utilisateur_" + id + "').submit() >OUI</button><button  class='btn btn-outline-danger' onclick=$('.pop_up').hide() >NON</button></div></center>")
	$('.pop_up').show();
}
/*suppression client*/
function submit_clients(id) {
	$('#pop_up').html("<div class='alert alert-danger' role='alert'>Etes vous sur de vouloir supprimer ?");
	$('#pop_up').html($('#pop_up').html() + "<center><button class='btn btn-outline-success' onclick=$('#form_clients_" + id + "').submit() >OUI</button><button  class='btn btn-outline-danger' onclick=$('.pop_up').hide() >NON</button></div></center>")
	$('.pop_up').show();
}
function edit_client() {
	$('#form_client').submit();
}
function voir_client(id) {
	$.ajax({
		method: "POST",
		url: "edit_client.php",
		data: {
			id: id
		}
	})
		.done(function (msg) {
			$('#pop_up .pop_upContainer').html(msg)
			$('.pop_up').show();
			if (id == -1) {
				$('#form_client')[0].reset();
				$("form :input").each(function () { $(this).val(''); });
				$('#submit_btn').html('Ajout utilisateur');
				$('#type_form').attr('name', 'ajout_client');
				$('#type').val($("#type option:eq(1)").val());
			}
		});
}
function voir_utilisateur(id) {
	$.ajax({
		method: "POST",
		url: "utilisateur.php",
		data: {
			id: id
		}
	})
		.done(function (msg) {
			$('#pop_up .pop_upContainer').html(msg)
			$('.pop_up').show();
			if (id == -1) {
				$('#form_utilisateur')[0].reset();
				$('#submit_btn').html('Ajouter');
				$('#type_form').attr('name', 'ajout_utilisateur');
				$('#form_utilisateur')[0].reset();
			}
		});
}
function edit_utilisateur() {
	$('#form_utilisateur').submit()
}
function menu(menu) {
	$.ajax({
		method: "POST",
		url: menu + ".php",
		data: {}
	})
		.done(function (msg) {
			$('#html').html(msg)
		});
}