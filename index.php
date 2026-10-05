<?php

	include('fonctions.php');

	if(!isset($_SESSION['etat'])||$_SESSION['etat']<=1){header('Location: login.php');}

?>

<!DOCTYPE html>

<html lang="fr">

  <head>

    <title>Point de vente</title>

	<link rel="icon" type="image/png" href="img/logo.png" />

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

	<script>
	  // Script CRITIQUE - doit s'exécuter avant le CSS pour éviter le flash
	  (function() {
		const theme = localStorage.getItem('theme') || 'light';
		document.documentElement.setAttribute('data-theme', theme);
		document.documentElement.classList.add(theme === 'dark' ? 'dark-theme' : 'light-theme');
	  })();
	</script>

	<style>
	  /* Spinner de chargement */
	  .loading-spinner {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: #ffffff;
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 9999;
		transition: opacity 0.3s ease, visibility 0.3s ease;
	  }
	  
	  .loading-spinner.dark {
		background-color: #1a1a1a;
	  }
	  
	  .loading-spinner.hidden {
		opacity: 0;
		visibility: hidden;
	  }
	  
	  .spinner {
		width: 50px;
		height: 50px;
		border: 4px solid #f3f3f3;
		border-top: 4px solid #007bff;
		border-radius: 50%;
		animation: spin 1s linear infinite;
	  }
	  
	  .loading-spinner.dark .spinner {
		border-color: #495057;
		border-top-color: #66b3ff;
	  }
	  
	  @keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	  }
	  
	  .loading-text {
		margin-top: 20px;
		font-size: 16px;
		color: #6c757d;
	  }
	  
	  .loading-spinner.dark .loading-text {
		color: #adb5bd;
	  }
	</style>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="css/css.css?v=1.1" rel="stylesheet">

	<script src="js/jquery.min.js"></script>

	<script src="js/fonctions.js"></script>

	





    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

	<!--poup-->

    <link href="popup/style.css" rel="stylesheet">

	<!--popup-->

  </head>

  <body>

  <!-- Spinner de chargement -->
  <div id="loading-spinner" class="loading-spinner">
    <div class="text-center">
      <div class="spinner"></div>
      <div class="loading-text">Chargement en cours...</div>
    </div>
  </div>

<div class="no_print">

	<div class="piste">

<div class="row">

  <div class="col">

	<div class="col">

		<div class="input-group mb-3">

		  <div class="input-group-prepend">

			<span class="input-group-text" id="basic-addon1">CODE BARRE</span>

		  </div>

		  <input id="code_barre" type="text" class="form-control" placeholder="CODE BARRE" aria-label="Username" aria-describedby="basic-addon1">

		</div>

	</div>

<script>

var input = document.getElementById("code_barre");

input.addEventListener("keyup", function(event) {

  if (event.keyCode === 13) {

    event.preventDefault();

    code_barre();

  }

});

</script>



  <h4 onclick="cacher_famille()">Familles</h4>

    <div class="row" id="famille">

		<?php 

		$reponse = $pdo->query("SELECT id,nom FROM ".$tab."familles");

		$i=0;

			while ($donnees = $reponse->fetch())

		{ 

		?>

			<div class="famille" onclick="famille(<?php echo $donnees['id']; ?>)">

				<div class="row">

				<div class="col-4">

				<!--<img src="img/produit.jpg" class="img_famille">-->

				<img id="fam_<?php echo $donnees['id']; ?>" class="famille_<?php echo $i; ?>" src="img/chargement_circle.gif" width="72" height="72" />

				</div>

				<div class="col ajx_nom_produit" >

				<?php echo $donnees['nom']; ?>

				</div>

				</div>

			</div>

		<?php $i++; }?>

	</div>

	<h4 onclick="cacher_produit()">Produits</h4>

    <div class="row" id="produit">

		<small><i style="color:#ccc">Selectionnez une famille pour voir les produits</i></small>

	</div>	

  </div>

  

  <div class="col">

  <h5 style="text-align: right;"><div class="total col prix_tot" id="total_general">0.000 TND</div></h5>

	<h4 onclick="cacher_produit_achete()">Liste produits</h4>

	<div class="achat" id="produit_achete">

				<table>

				<tr class="table_tete">

				  <th class="col-5">produits</th>

				  <th class="col">Qté</th>

				  <th class="col">Prix</th>

				  <th class="col">Total</th>

				</tr>

				<tr class="total">

				  <th class="col-5" id="nbt">0</th>

				  <th class="col text-right" id="qtet">0</th>

				  <th class="col text-right" id="put">0</th>

				  <th class="col text-right" id="ptt">0</th>

				</tr>

				</table>

		</div><?php include('client.php');?>

		<div class="row">

		<div id="message_validation" class="alert alert-danger" role="alert" style="display:none"></div>

		</div>

		<div class="row">

		<select class="form-control" name="type_doc" id="type_doc">

		<option value="1">Facture</option>

		<option value="2">Devis</option>

		<option value="3">Bon de commande</option>

		<option value="4">Bon de livraison</option>

		</select>

			<?php //if($_SESSION['etat']==2){?>

			<button type="button" class="btn btn-primary col" onclick="valider_command()">Valider</button>

			<?php //}?>

			

			<button type="button" class="btn btn-warning col" onclick="annuler_command()">Annuler</button>

		</div>

  </div>



</div>



<?php include('popup.php');?>

</div>

</div>

	<div class="debug">

	</div>

<div class="no_print">	

	<div id="footer">

	<div id="position_gps"></div><center>

	<div id="copy right" class="copy_right"><a href="admin.php">admin</a> Expert informatique 2020 <a href="admin.php?deconnexion=true">Déconnexion</a></div><center>

	</div>

</div>

    <script src="bootstrap/js/bootstrap.min.js"></script>

	<script src="bibliotheque/html2pdf.js"></script>

	<script>

		$(window).load(function(){

			famille_json();

		});

	</script>
	
	<script>
	  // Masquer le spinner de chargement une fois que tout est chargé
	  window.addEventListener('load', function() {
		const spinner = document.getElementById('loading-spinner');
		const theme = localStorage.getItem('theme') || 'light';
		
		// Appliquer le thème au spinner avant de le masquer
		if (theme === 'dark') {
		  spinner.classList.add('dark');
		}
		
		// Petit délai pour s'assurer que tout est bien rendu
		setTimeout(function() {
		  spinner.classList.add('hidden');
		  // Supprimer complètement après la transition
		  setTimeout(function() {
			spinner.style.display = 'none';
		  }, 300);
		}, 100);
	  });
	</script>

  </body>

</html>