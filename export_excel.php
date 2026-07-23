<?php
require('fonctions.php');

// Vérifier les paramètres nécessaires
if (!isset($_GET['export']) || $_GET['export'] !== 'excel') {
    die('Accès refusé');
}

// Récupérer les paramètres de filtrage
$type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
$order = isset($_GET['order']) ? $_GET['order'] : "id";

// Valider l'ordre pour éviter les injections SQL
$allowed_orders = array('id', 'total', 'nbr_produits', 'id_client', 'id_utilisateur', 'date_insert');
if (!in_array($order, $allowed_orders)) {
    $order = 'id';
}

// Construire la clause WHERE
if($type != 0) {
    $WHERE = "WHERE type=" . intval($type);
} else {
    $WHERE = "WHERE id = id";
}

// Filtrer par intervalle de dates (toujours appliqué)
$date_debut = (isset($_GET['recherche_date']) && $_GET['recherche_date']) ? $_GET['recherche_date'] : date("Y-m-d");
$date_fin = (isset($_GET['recherche_date_fin']) && $_GET['recherche_date_fin']) ? $_GET['recherche_date_fin'] : date("Y-m-d");
$WHERE .= " AND date(date_insert) BETWEEN " . $pdo->quote($date_debut) . " and " . $pdo->quote($date_fin);

// Récupérer les données
$reponse = $pdo->query("SELECT * FROM " . $tab . "ticket " . $WHERE . " ORDER BY " . $order . " DESC");

// Créer le fichier CSV avec séparateur point-virgule (format Excel français)
$filename = "factures_export_" . date('Y-m-d_His') . ".csv";

// En-têtes HTTP pour Excel
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// BOM UTF-8 pour Excel
echo "\xEF\xBB\xBF";

// Écrire les en-têtes
$headers = array(
    'N°',
    'Total',
    'Total HT',
    'TVA',
    'Nombre Articles',
    'Type',
    'Client',
    'Utilisateur',
    'Date'
);

echo implode(';', $headers) . "\r\n";

// Écrire les données
while ($donnees = $reponse->fetch(PDO::FETCH_ASSOC)) {
    // Conversion du type
    $type_text = '';
    switch ($donnees['type']) {
        case "1": $type_text = "Facture"; break;
        case "2": $type_text = "Devis"; break;
        case "3": $type_text = "Bon de commande"; break;
        case "4": $type_text = "Bon de livraison"; break;
        default: $type_text = $donnees['type'];
    }

    // Récupérer les informations du client
    $clients = $pdo->query("SELECT * FROM " . $tab . "client WHERE id=" . intval($donnees['id_client']));
    $client = $clients->fetch(PDO::FETCH_ASSOC);
    $client_name = ($client && (isset($client['nom']) || isset($client['prenom']))) ? trim((isset($client['nom']) ? $client['nom'] : "") . " " . (isset($client['prenom']) ? $client['prenom'] : "")) : "N/A";

    // Récupérer les informations de l'utilisateur
    $utilisateurs = $pdo->query("SELECT * FROM " . $tab . "utilisateurs WHERE id=" . intval($donnees['id_utilisateur']));
    $utilisateur = $utilisateurs->fetch(PDO::FETCH_ASSOC);
    $util_name = ($utilisateur && (isset($utilisateur['nom']) || isset($utilisateur['prenom']))) ? trim((isset($utilisateur['nom']) ? $utilisateur['nom'] : "") . " " . (isset($utilisateur['prenom']) ? $utilisateur['prenom'] : "")) : "N/A";

    // Préparer les valeurs (échappement des guillemets et points-virgules)
    $row = array(
        str_replace(array(';', '"'), array(',', '""'), $donnees['id_insertion']),
        str_replace(array(';', '"'), array(',', '""'), number_format($donnees['total'], 3, ',', ' ')),
        str_replace(array(';', '"'), array(',', '""'), number_format($donnees['total_ht'], 3, ',', ' ')),
        str_replace(array(';', '"'), array(',', '""'), number_format($donnees['total_tva'], 3, ',', ' ')),
        str_replace(array(';', '"'), array(',', '""'), $donnees['nbr_produits']),
        str_replace(array(';', '"'), array(',', '""'), $type_text),
        str_replace(array(';', '"'), array(',', '""'), $client_name),
        str_replace(array(';', '"'), array(',', '""'), $util_name),
        str_replace(array(';', '"'), array(',', '""'), $donnees['date_insert'])
    );

    // Écrire la ligne
    echo implode(';', $row) . "\r\n";
}

$reponse->closeCursor();
exit;
?>
