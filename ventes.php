<?php //require('fonctions.php');
?>
<style>
/* Styles personnalisés pour la page des ventes */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 20px 20px;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.table-responsive {
    border-radius: 10px;
    overflow: auto;
	padding-right: 6px;
}

.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
    transform: scale(1.01);
}

.badge {
    font-weight: 500;
    padding: 0.4em 0.8em;
}

.btn-group .btn {
    margin: 0 1px;
}

.pagination .page-link {
    border-radius: 8px !important;
    margin: 0 2px;
    border: 1px solid #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    box-shadow: 0 2px 4px rgba(13,110,253,0.3);
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
}

.alert {
    border-radius: 12px;
    border: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem 0;
        text-align: center;
    }

    .table-responsive {
        font-size: 0.9rem;
    }

    .btn-group {
        flex-direction: column;
        width: 100%;
    }

    .btn-group .btn {
        margin: 1px 0;
        border-radius: 6px !important;
    }

    .card-body .row > div {
        margin-bottom: 1rem;
    }

    .d-flex.gap-2 {
        justify-content: center !important;
        flex-wrap: wrap;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }

    .card {
        margin: 0 -5px;
    }

    .table th, .table td {
        padding: 0.5rem;
    }

    h2 {
        font-size: 1.5rem;
    }
}

/* Animation pour les nouvelles lignes */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.table tbody tr {
    animation: fadeIn 0.3s ease-out;
}

/* Style pour les totaux */
.table-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    color: white;
}

.table-primary td {
    border-color: rgba(255,255,255,0.2);
}

/* Indicateur de chargement */
.loading {
    position: relative;
}

.loading::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
// Fonctions JavaScript pour améliorer l'interactivité
function supprimer_vente(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette vente ? Cette action est irréversible.')) {
        // Ajouter une classe de chargement
        const row = document.getElementById('ligne_' + id);
        if (row) {
            row.classList.add('loading');
        }

        // Soumettre le formulaire
        document.getElementById('form_vente_' + id).submit();
    }
}

// Animation d'entrée pour les nouvelles lignes
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.table tbody tr');
    rows.forEach((row, index) => {
        row.style.animationDelay = (index * 0.1) + 's';
    });

    // Auto-refresh des données toutes les 30 secondes (optionnel)
    // setInterval(function() {
    //     location.reload();
    // }, 30000);
});

// Fonction pour filtrer en temps réel (optionnel)
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.querySelector('.table tbody');
    const rows = table.getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell) {
                const text = cell.textContent || cell.innerText;
                if (text.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }

        if (found) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

// Responsive table adjustments
function adjustTableForMobile() {
    const table = document.querySelector('.table-responsive');
    if (window.innerWidth < 768) {
        table.classList.add('table-sm');
    } else {
        table.classList.remove('table-sm');
    }
}

// Mettre à jour le compteur de documents
function updateDocumentCount() {
    const countElement = document.getElementById('total-count');
    if (countElement) {
        //countElement.innerHTML = 'Total: <?php echo isset($total_documents) ? $total_documents : 0; ?> documents';
    }
}

window.addEventListener('resize', adjustTableForMobile);
window.addEventListener('load', function() {
    adjustTableForMobile();
    // updateDocumentCount(); // Déplacé à la fin du fichier
});
</script>

<div class="container-fluid py-4">

    <!-- Search Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        <?php
                        if(isset($_REQUEST['type']) && $_REQUEST['type']){$type=$_REQUEST['type'];}else{$type=0;}
                        switch ($type) {
                            case "0": echo "Toutes les Ventes"; break;
                            case "1": echo "Factures"; break;
                            case "2": echo "Devis"; break;
                            case "3": echo "Bons de Commande"; break;
                            case "4": echo "Bons de Livraison"; break;
                        }
                        ?>
            </h5>
			
        </div>
        <div class="card-body">
            <form method="get" action="admin.php" class="row g-3">
                <input type="hidden" value="ventes" name="menu">
                <input type="hidden" value="<?php echo $type; ?>" name="type">

                <!-- Date Range -->
                <div class="col-md-4">
                    <input class="form-control" type="date" id="date_debut" name="recherche_date"
                           value="<?php echo (isset($_GET['recherche_date']))? $_GET['recherche_date'] : date("Y-m-d"); ?>">
                </div>

                <div class="col-md-4">
                    <input class="form-control" type="date" id="date_fin" name="recherche_date_fin"
                           value="<?php echo (isset($_GET['recherche_date_fin']))? $_GET['recherche_date_fin'] : date("Y-m-d"); ?>">
                </div>

                <!-- Action Buttons -->
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button class="btn btn-success flex-fill" type="submit">
                        <i class="fas fa-search me-1"></i>Rechercher
                    </button>

                    <?php
                    $export_url = "export_excel.php?export=excel&type=" . urlencode($type) .
                                  "&recherche_date=" . urlencode((isset($_GET['recherche_date'])? $_GET['recherche_date'] : date("Y-m-d"))) .
                                  "&recherche_date_fin=" . urlencode((isset($_GET['recherche_date_fin'])? $_GET['recherche_date_fin'] : date("Y-m-d"))) .
                                  "&order=" . urlencode(isset($_GET['order']) ? $_GET['order'] : 'id');

                    $export_zip_url = "export_zip_pdf.php?type=" . urlencode($type) .
                                      "&recherche_date=" . urlencode((isset($_GET['recherche_date'])? $_GET['recherche_date'] : date("Y-m-d"))) .
                                      "&recherche_date_fin=" . urlencode((isset($_GET['recherche_date_fin'])? $_GET['recherche_date_fin'] : date("Y-m-d")));
                    ?>
                    <a href="<?php echo $export_url; ?>" class="btn btn-outline-primary" title="Exporter la liste au format Excel">
                        <i class="fas fa-file-excel me-1"></i>Excel
                    </a>
                    <a href="<?php echo $export_zip_url; ?>" class="btn btn-danger text-nowrap" title="Télécharger toutes les factures de la période sous forme de fichiers PDF individuels dans une archive ZIP">
                        <i class="fas fa-file-archive me-1"></i>ZIP (PDFs)
                    </a>
                </div>
            </form>
        </div>
    </div>

  <?php		if(isset($_REQUEST['supprimer'])){

			$sql = "DELETE FROM ".$tab."ticket WHERE id = ?";

			$pdo->prepare($sql)->execute([$_REQUEST['id']]);

			$sql = "DELETE FROM ".$tab."details_ticket WHERE id_tickets = ?";

			$pdo->prepare($sql)->execute([$_REQUEST['id_insertion']]);

			echo "supression ticket ".$_REQUEST['id']." et tout leur contenu";

			}?>



</div>

    <!-- Data Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>Liste des documents
            </h5>
            <div class="text-muted small" id="total-count">
                <!-- Le total sera calculé après la définition de $WHERE -->
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col">
                                <a href="<?php echo $url;?>&order=total" class="text-decoration-none">
                                    <i class="fas fa-coins me-1"></i>Total
                                    <?php if(isset($_GET['order']) && $_GET['order']=='total') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                </a>
                            </th>
                            <th scope="col" class="text-center">
                                <a href="<?php echo $url;?>&order=nbr_produits" class=" text-decoration-none">
                                    <i class="fas fa-boxes me-1"></i>Articles
                                    <?php if(isset($_GET['order']) && $_GET['order']=='nbr_produits') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                </a>
                            </th>
                            <th scope="col" class="text-center">
                                <i class="fas fa-tag me-1"></i>Type
                            </th>
                            <th scope="col">
                                <a href="<?php echo $url;?>&order=id_client" class=" text-decoration-none">
                                    <i class="fas fa-user me-1"></i>Client
                                    <?php if(isset($_GET['order']) && $_GET['order']=='id_client') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                </a>
                            </th>
                            <th scope="col">
                                <a href="<?php echo $url;?>&order=id_utilisateur" class=" text-decoration-none">
                                    <i class="fas fa-user-tie me-1"></i>Utilisateur
                                    <?php if(isset($_GET['order']) && $_GET['order']=='id_utilisateur') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                </a>
                            </th>
                            <th scope="col">
                                <a href="<?php echo $url;?>&order=date" class=" text-decoration-none">
                                    <i class="fas fa-calendar me-1"></i>Date
                                    <?php if(isset($_GET['order']) && $_GET['order']=='date') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                </a>
                            </th>
                            <th scope="col" class="text-center">
                                <i class="fas fa-cogs me-1"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>

	<?php

	if(isset($_REQUEST['nom_famille'])){

		

			$dossier = explode("php",$_FILES['image']['tmp_name']);

			$dossier[1]="php".$dossier[1];

			$ext = explode(".",$_FILES['image']['name']);

			$redimOK = fct_redim_image(120,80,$dossier[0],$dossier[1],$dossier[0],$dossier[1],$ext[1]);

			//if ($redimOK == 1) { echo 'Redimensionnement OK !';  }

			$img =file_get_contents ($_FILES['image']['tmp_name']);

		

	$sql = "INSERT INTO ".$tab."familles (id,nom,image) VALUES (?,?,?)";

	$pdo->prepare($sql)->execute([NULL,$_REQUEST['nom_famille'],$img]);

	}

	if(isset($_REQUEST['supprimer'])){

	$sql = "DELETE FROM ".$tab."familles WHERE id = ?";

	$pdo->prepare($sql)->execute([$_REQUEST['id']]);

	}

	// On récupère tout le contenu de la table familles

	$start=0;

	$bloc=10;

	if($type<>0){$WHERE="WHERE type=".$type;}else{$WHERE="WHERE id = id ";}

	if(isset($_GET['page'])){$start=$bloc*($_GET['page']-1);}

	if(isset($_GET['order'])){$order=$_GET['order'];}else{$order="id";}

	// Filtrer par intervalle de dates
	$date_debut = (isset($_GET['recherche_date'])) ? $_GET['recherche_date'] : date("Y-m-d");

	$date_fin = (isset($_GET['recherche_date_fin'])) ? $_GET['recherche_date_fin'] : date("Y-m-d");

	$WHERE .= ' AND date(date_insert) BETWEEN ' . $pdo->quote($date_debut) . ' and ' . $pdo->quote($date_fin) . '';
	$reponse = $pdo->query("SELECT * FROM ".$tab."ticket ".$WHERE." ORDER BY ".$order." DESC LIMIT ".$start.",".$bloc);

	// Calculer le total des documents pour l'afficher dans le header
	$reponse_count = $pdo->query("SELECT count(*) as total FROM ".$tab."ticket ".$WHERE);
	$data_count = $reponse_count->fetch();
	$total_documents = $data_count[0];


	// On affiche chaque entrée une à une

	$nbr_total=0;

	$total=0;

	$i=0;

	while ($donnees = $reponse->fetch())

	{

	?>

		<tr id="ligne_<?php echo $donnees['id']; ?>" <?php if ($donnees['vu']==0){echo 'style="font-weight: bold;"';}?>>

		  <th scope="row"><?php echo $donnees['id_insertion']; ?></th>



		  <td align="right">

		  <?php 

			echo number_format($donnees['total'], 3, ',', ' '); 

			$total+=$donnees['total'];

			$i++;

		  ?>

		  </td>

		  <td>

		  <?php 

			echo $donnees['nbr_produits']; 

			$nbr_total+=$donnees['nbr_produits'];

		  ?>

		  </td>

		  <!--<td><?php echo "<a target='_blank' href='https://www.google.com/maps/search/".$donnees['Latitude'].",".$donnees['Longitude']."'>"; ?>Map >></a>

		  </td>-->

		  <td><?php 

					switch ($donnees['type'])

					{

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

		  ?></td>

		  <?php 

		$clients = $pdo->query("SELECT * FROM ".$tab."client WHERE id=".$donnees['id_client']."");

		$client = $clients->fetch();

		if($client['nom']==""&&$client['prenom']=="")

		{$cl="<small><i>Information non disponible<i></small>";}else{

			$cl="<span style='cursor:pointer' onclick='voir_client(".$client['id'].")'>".$client['nom']." ".$client['prenom'].'</span>';

			}

		

		$utilisateurs = $pdo->query("SELECT * FROM ".$tab."utilisateurs WHERE id=".$donnees['id_utilisateur']."");

		$utilisateur = $utilisateurs->fetch();

		if($utilisateur['nom']==""&&$utilisateur['prenom']=="")

		{$uti="<small><i>Information non disponible<i></small>";}else{

			$uti="<span style='cursor:pointer' onclick='voir_utilisateur(".$utilisateur['id'].")'>".$utilisateur['nom']." ".$utilisateur['prenom']."</span>";

			}

		

		  ?>

		  

		  <td><?php echo  $cl;?></td>

		  <td><?php echo  $uti;?></td>

		  <td><?php echo $donnees['date_insert']; ?></td>

		  <td>

		  <form id="form_vente_<?php echo $donnees['id']; ?>">

		  <i class="fas fa-pencil-alt green me-2" onclick="voir_vente(<?php echo $donnees['id']; ?>)" title="Voir"></i>

		  <input type="hidden" name="menu" value="ventes">

		  <input type="hidden" name="supprimer" value="ventes">

		  <input type="hidden" value="<?php echo $donnees['id']; ?>" name="id">

		  <input type="hidden" value="<?php echo $donnees['id_insertion']; ?>" name="id_insertion">

		  <i class="fas fa-trash-alt red" onclick="submit_vente(<?php echo $donnees['id']; ?>);" title="Supprimer"></i>

		  

		  </form>

		  </td>

		</tr>



	<?php

	}

?>

		<tr class="table-primary fw-bold">
		    <td class="text-center">
		        <i class="fas fa-calculator me-1"></i><?php echo $i; ?> documents
		    </td>
		    <td class="text-end text-success fs-5">
		        <i class="fas fa-coins me-1"></i><?php echo number_format($total, 3, ',', ' '); ?> DT
		    </td>
		    <td class="text-center">
		        <span class="badge bg-info fs-6">
		            <i class="fas fa-boxes me-1"></i><?php echo $nbr_total; ?>
		        </span>
		    </td>
		    <td colspan="5" class="text-muted text-center">
		        <em>Total de la page</em>
		    </td>
		</tr>



<?php

	$reponse->closeCursor(); // Termine le traitement de la requête



	?>

                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="text-muted small">
                        <?php
                        $reponse_count = $pdo->query("SELECT count(*) as total FROM ".$tab."ticket ".$WHERE);
                        $data_count = $reponse_count->fetch();
                        $total_records = $data_count[0];
                        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $start_record = ($current_page - 1) * $bloc + 1;
                        $end_record = min($current_page * $bloc, $total_records);
                        echo "Affichage de $start_record à $end_record sur $total_records documents";
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <nav aria-label="Navigation des pages">
                        <ul class="pagination pagination-sm justify-content-end mb-0">
                            <?php
                            $max_page = ceil($total_records / $bloc);

                            // Bouton précédent
                            if ($current_page > 1) {
                                $prev_url = $url . "&page=" . ($current_page - 1);
                                echo '<li class="page-item"><a class="page-link" href="' . $prev_url . '"><i class="fas fa-chevron-left"></i></a></li>';
                            }

                            // Pages
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($max_page, $current_page + 2);

                            if ($start_page > 1) {
                                $first_url = $url . "&page=1";
                                echo '<li class="page-item"><a class="page-link" href="' . $first_url . '">1</a></li>';
                                if ($start_page > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }

                            for ($i = $start_page; $i <= $end_page; $i++) {
                                $page_url = $url . "&page=" . $i;
                                $active_class = ($i == $current_page) ? ' active' : '';
                                echo '<li class="page-item' . $active_class . '"><a class="page-link" href="' . $page_url . '">' . $i . '</a></li>';
                            }

                            if ($end_page < $max_page) {
                                if ($end_page < $max_page - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                $last_url = $url . "&page=" . $max_page;
                                echo '<li class="page-item"><a class="page-link" href="' . $last_url . '">' . $max_page . '</a></li>';
                            }

                            // Bouton suivant
                            if ($current_page < $max_page) {
                                $next_url = $url . "&page=" . ($current_page + 1);
                                echo '<li class="page-item"><a class="page-link" href="' . $next_url . '"><i class="fas fa-chevron-right"></i></a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Appeler updateDocumentCount après que toutes les variables PHP soient définies
document.addEventListener('DOMContentLoaded', function() {
    updateDocumentCount();
});
</script>

