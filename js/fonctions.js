
function cacher_famille() {
	jQuery('#famille').slideToggle();
}

function cacher_produit() {
	jQuery('#produit').slideToggle();
}

function cacher_produit_achete() {
	jQuery('#produit_achete').slideToggle();
}
function cacher_Information_client() {
	jQuery('#Information_client').slideToggle();
}

function trois_(x) {
	return Number.parseFloat(x).toFixed(3);
}
function maPosition(position) {

	var infopos = "Position déterminée :\n";
	infopos += "Latitude : <span id='Latitude_gps'>" + position.coords.latitude + "</span>\n";
	infopos += "Longitude: <span id='Longitude_gps'>" + position.coords.longitude + "</span>\n";
	infopos += "Altitude : <span id='Altitude_gps'>" + position.coords.altitude + "</span>\n";
	url = "https://www.google.com/maps/search/" + position.coords.latitude + "," + position.coords.longitude + "";
	$('#position_gps').html(infopos + "<br><a href='" + url + "'>google maps</a>");
}
function PDF(div) {
	// Get the element.
	var element = document.getElementById(div);

	// Generate the PDF.
	html2pdf().from(element).set({
		margin: 1,
		filename: 'facture.pdf',
		image: { type: 'jpeg', quality: 0.95 },
		html2canvas: { scale: 2, useCORS: true, logging: false },
		jsPDF: { compressPDF: true }
	}).save();
}

$(document).ready(function () {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(maPosition);
	} else {
		var infopos = "Position déterminée :\n";
		infopos += "Latitude : <span id='Latitude_gps'>0.0000000</span>\n";
		infopos += "Longitude: <span id='Longitude_gps'>0.0000000</span>\n";
		infopos += "Altitude : <span id='Altitude_gps'>0.0000000</span>\n";
		$('#position_gps').html(infopos);
	}

	restaurer_etat_pos();

	$(document).on('change', '#liste_client, #type_doc', function () {
		sauvegarder_etat_pos();
	});
});

function sauvegarder_etat_pos() {
	var produits = [];
	$('[id^=produit_a_]').each(function () {
		var id = $(this).attr('id').replace('produit_a_', '');
		var nom = $('#nom_produit_' + id).text();
		var qte = $('#qte_' + id).text();
		var pu = $('#pu_' + id).text();
		var pt = $('#pt_' + id).text();
		produits.push({
			id: id,
			nom: nom,
			qte: qte,
			pu: pu,
			pt: pt
		});
	});

	var id_client = $('#liste_client').val();
	var type_doc = $('#type_doc').val();

	if (produits.length === 0 && (!id_client || id_client === "" || id_client === null)) {
		localStorage.removeItem('pos_state');
		return;
	}

	var state = {
		id_client: id_client,
		type_doc: type_doc,
		produits: produits
	};

	localStorage.setItem('pos_state', JSON.stringify(state));
}

function effacer_etat_pos() {
	localStorage.removeItem('pos_state');
}

function restaurer_etat_pos() {
	var saved = localStorage.getItem('pos_state');
	if (!saved) return;

	try {
		var state = JSON.parse(saved);

		if (state.id_client) {
			$('#liste_client').val(state.id_client);
		}

		if (state.type_doc) {
			$('#type_doc').val(state.type_doc);
		}

		if (state.produits && state.produits.length > 0) {
			$('[id^=produit_a_]').remove();

			$.each(state.produits, function (index, item) {
				var msg = '';
				msg += '<tr class="couleur-0" onclick="edit(' + item.id + ')" id="produit_a_' + item.id + '" >';
				msg += '<td class="col-5" id="nom_produit_' + item.id + '">' + item.nom + '</td>';
				msg += '<td class="col text-right" id="qte_' + item.id + '" >' + item.qte + '</td>';
				msg += '<td class="col text-right" id="pu_' + item.id + '">' + item.pu + '</td>';
				msg += '<td class="col text-right" id="pt_' + item.id + '" name="pt" >' + item.pt + '</td>';
				msg += '</tr>';

				$('.total').before(msg);
			});

			total_tableau();
		}
	} catch (e) {
		console.error("Erreur lors de la restauration du POS:", e);
	}
}

var xhr;
function famille(id) {
	if (xhr != undefined) { xhr.abort() }
	$('#produit').html('<img src="img/chargements.gif">');
	$.ajax({
		method: "GET",
		url: "ajaX.php",
		cache: true,
		data: { famille: id, demande: "famille" }
	})
		.done(function (msg) {

			cacher_famille();
			$('#produit').html(msg);
			$('#produit').show();
			produit_json();
		});
}
function produit_json() {
	var id;
	$.each($('[class^=pro_]'), function (index, value) {
		id = $(this).attr('id');
		id = id.split("_");
		id = id[1];

		$.ajax({
			method: "GET",
			url: "ajaX.php",
			cache: true,
			data: { produit: id, demande: "produit_json" }
		})
			.done(function (data) {
				id_img = data.split(",");
				img_data = id_img[0];
				id_img = id_img[1];
				$("#pro_" + id_img).attr('src', 'data:image/jpeg;base64,' + img_data);
			})
	});
}
function famille_json() {
	var id;
	$.each($('[class^=famille_]'), function (index, value) {
		id = $(this).attr('id');
		id = id.split("_");
		id = id[1];

		$.ajax({
			method: "GET",
			url: "ajaX.php",
			cache: true,
			data: { famille: id, demande: "famille_json" }
		})
			.done(function (data) {
				id_img = data.split(",");
				img_data = id_img[0];
				id_img = id_img[1];
				$("#fam_" + id_img).attr('src', 'data:image/jpeg;base64,' + img_data);
			})
	});
}

function total_tableau() {
	var p_total = 0.000;
	var nbr = 0;
	p_total = 0;
	$.each($('[id^=pt_]'), function (index, value) {
		nbr++;
		p_total += parseFloat($(value).text());
	});
	$('#ptt').html(p_total.toFixed(3));
	$('#total_general').html(p_total.toFixed(3) + " TND");
	$('#nbt').html(nbr);

	u_total = 0;
	$.each($('[id^=pu_]'), function (index, value) {
		u_total += parseFloat($(value).text());
	});
	$('#put').html(u_total.toFixed(3));


	q_total = 0;
	$.each($('[id^=qte_]'), function (index, value) {
		q_total += parseFloat($(value).text());
	});
	$('#qtet').html(q_total.toFixed(3));

	sauvegarder_etat_pos();
}

function produit(id) {
	var p_total = 0.000;
	var nbr = 0;
	var msg = "";
	var msg_nom = $('#select_produit_' + id).attr('nom');
	var msg_prix = $('#select_produit_' + id).attr('prix');

	msg += '<tr class="couleur-0" onclick="edit(' + id + ')" id="produit_a_' + id + '" >';
	msg += '<td class="col-5" id="nom_produit_' + id + '">' + msg_nom + '</td>';
	msg += '<td class="col text-right" id="qte_' + id + '" >1</td>';
	msg += '<td class="col text-right" id="pu_' + id + '">' + msg_prix + '</td>';
	msg += '<td class="col text-right" id="pt_' + id + '" name="pt" >' + msg_prix + '</td>';
	msg += '</tr>';

	if ($('#produit_a_' + id).length) {
		$('#qte_' + id).html(parseFloat($('#qte_' + id).html()) + 1);

		p_total = parseFloat($('#qte_' + id).html()) * parseFloat($('#pu_' + id).html());
		$('#pt_' + id).html(p_total.toFixed(3));
	} else {
		$('.table_tete').after("" + msg + "");
		$.each($('[id^=pt_]'), function (index, value) {
			pt_instan = parseFloat($(this).text());
			$(this).text(pt_instan.toFixed(3));
		});
		$.each($('[id^=pu_]'), function (index, value) {
			pu_instan = parseFloat($(this).text());
			$(this).text(pu_instan.toFixed(3));
		});
	}
	total_tableau();
}

function annuler_command() {
	if (confirm("Vous désirez vraiment annuler?")) {
		$.each($('[id^=produit_a_]'),
			function (index, value) {
				$(this).remove();
			});
		$('#liste_client').val('');
		$('#type_doc').val('1');
		effacer_etat_pos();
		total_tableau();
	}
}
function valider_command() {
	id_client = $('#liste_client').val();
	nbr = $('#nbt').html();
	type = $('#type_doc').val();

	if (nbr == 0 || id_client == null || id_client == "") {
		msg = "";
		if (nbr == 0) { msg += "- Vous devez selectionnez au mois un produit.<br>"; }
		if (id_client == null || id_client == "") { msg += "- Vous devez selectionnez un client."; }
		$('#message_validation').show().html(msg);
		return;
	}
	var ch = "";
	id = "";
	total = 0;

	Latitude = "0.0";
	Longitude = "0.0";
	Altitude = "0.0";

	Latitude = $('#Latitude_gps').html();
	Longitude = $('#Longitude_gps').html();
	Altitude = $('#Altitude_gps').html();

	$.each($('[id^=qte_]'), function (index, value) {
		id = $(value).attr("id");
		id = id.split("_");
		if (id[1] != "" || $(value).text() != "") { ch += id[1] + "," + $(value).text() + ";"; }
	});
	total = parseFloat($('#ptt').text());
	qte = $('#nbt').text();

	total_tva = (total - (total / parseFloat(1.19)));
	total_ht = (total / parseFloat(1.19));
	$.ajax({
		method: "POST",
		url: "ajaX.php",
		data: {
			ch: ch,
			type: type,
			nbr: nbr,
			total: total,
			total_tva: total_tva,
			total_ht: total_ht,
			Latitude: Latitude,
			Longitude: Longitude,
			Altitude: Altitude,
			id_client: id_client,
			demande: "valider"
		}
	})
		.done(function (msg) {
			$('.no_print').hide();
			$(".debug").html(msg);
			$.each($('[id^=produit_a_]'),
				function (index, value) {
					$(this).remove();
				});
			$('#liste_client').val('');
			$('#type_doc').val('1');
			effacer_etat_pos();
			total_tableau();
			//setTimeout(window.print(), 10000);
			//$('.no_print').show();	
		});

}
function affiche_ajout_client() {
	mod_client.style.display = 'block';
	if (navigator.geolocation)
		navigator.geolocation.getCurrentPosition(maPosition);
	$('#Latitude_client').val($('#Latitude_gps').html());
	$('#Longitude_client').val($('#Longitude_gps').html())

}
function ajoute_client() {
	Latitude = "0.0";
	Longitude = "0.0";
	Latitude = $('#Latitude_client').val();
	Longitude = $('#Longitude_client').val();
	$.ajax({
		method: "POST",
		url: "ajaX.php",
		data: {
			nom: $('#Nom_client').val(),
			prenom: $('#Prenom_client').val(),
			adresse: $('#Adresse').val(),
			MF: $('#MF').val(),
			tel: $('#tel').val(),
			gsm: $('#gsm').val(),
			email: $('#email').val(),
			Latitude: Latitude,
			Longitude: Longitude,
			type: $('#type').val(),
			note: $('#note').val(),
			demande: "ajoute_client"
		}
	})
		.done(function (msg) {
			//$(".debug").html( msg );
			tab = msg.split(",");
			$('#liste_client').append("<option value='" + tab[0] + "'>" + tab[1] + "</option>");
			$('#liste_client').val(tab[0]);
			sauvegarder_etat_pos();
			mod_client.style.display = "none";
		});
}
function code_barre() {
	$.ajax({
		method: "POST",
		url: "ajaX.php",
		data: {
			demande: "code_barre",
			code: $('#code_barre').val()
		}
	})
		.done(function (msg) {
			$('#produit').html(msg);
			produit($('[i=x]').attr('j'));
			$('#produit').html("");
			$('#code_barre').val("");
		})

}