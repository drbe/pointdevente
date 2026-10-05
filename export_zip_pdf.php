<?php
require_once('fonctions.php');
require_once('bibliotheque/fpdf.php');
require_once('bibliotheque/chiffre_lettre.php');

// Vérification de la session utilisateur
if (!isset($_SESSION['etat']) || $_SESSION['etat'] <= 2) {
    header('Location: login.php');
    exit;
}

// Récupération des paramètres de filtrage
$type = isset($_GET['type']) ? (int)$_GET['type'] : 1;
$date_debut = isset($_GET['recherche_date']) ? $_GET['recherche_date'] : date("Y-m-d");
$date_fin = isset($_GET['recherche_date_fin']) ? $_GET['recherche_date_fin'] : date("Y-m-d");

// Nom du type de document
switch ($type) {
    case 1: $doc_label = "Facture"; break;
    case 2: $doc_label = "Devis"; break;
    case 3: $doc_label = "Bon_de_commande"; break;
    case 4: $doc_label = "Bon_de_livraison"; break;
    default: $doc_label = "Vente"; break;
}

// Clause WHERE
$WHERE = "WHERE 1=1";
if ($type <> 0) {
    $WHERE .= " AND type = " . $type;
}
$WHERE .= " AND DATE(date_insert) BETWEEN " . $pdo->quote($date_debut) . " AND " . $pdo->quote($date_fin);

// Récupérer les tickets correspondant au filtre
$query = "SELECT * FROM " . $tab . "ticket " . $WHERE . " ORDER BY id ASC";
$stmt = $pdo->query($query);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($tickets)) {
    echo "<script>alert('Aucun document trouvé pour la période sélectionnée (du $date_debut au $date_fin).'); window.history.back();</script>";
    exit;
}

// Fonction pour nettoyer les noms de fichiers
function sanitize_filename($string) {
    $string = mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string));
    $accents = array(
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'ç'=>'c',
        'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i',
        'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
        'ý'=>'y', 'ÿ'=>'y', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A',
        'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
        'Ï'=>'I', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ù'=>'U', 'Ú'=>'U',
        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y'
    );
    $string = strtr($string, $accents);
    $string = preg_replace('/[^a-zA-Z0-9_-]/', '_', $string);
    $string = preg_replace('/_+/', '_', $string);
    return trim($string, '_');
}

// Fonction d'encodage texte pour FPDF
function pdf_txt($str) {
    if ($str === null) return '';
    return iconv('UTF-8', 'windows-1252//TRANSLIT', $str);
}

// Classe personnalisée FPDF pour la facture
class PDF_Facture extends FPDF {
    public $doc_type = 'FACTURE';

    function Header() {
        // En-tête de la facture
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(110, 7, pdf_txt('MODERN Orthodontics Tunisia'), 0, 0, 'L');
        
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(13, 110, 253);
        $this->Cell(80, 7, pdf_txt(strtoupper($this->doc_type)), 0, 1, 'R');
        
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(110, 5, pdf_txt('MF : 1591615/W/P/M/000'), 0, 1, 'L');
        $this->Cell(110, 5, pdf_txt('Web : mot.tn | Email : contact@mot.tn'), 0, 1, 'L');
        $this->Ln(4);
        
        $this->SetDrawColor(200, 200, 200);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(6);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, pdf_txt('Expert informatique - Tous droits réservés - Page ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Création du fichier ZIP temporaire
$zip_filename = "Factures_" . $date_debut . "_au_" . $date_fin . ".zip";
$temp_zip_path = tempnam(sys_get_temp_dir(), 'zip_factures_');

$zip = new ZipArchive();
if ($zip->open($temp_zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Impossible de créer l'archive ZIP temporaire.");
}

// Boucle sur chaque ticket pour générer son PDF
foreach ($tickets as $ticket) {
    // Récupérer le client
    $stmt_c = $pdo->query("SELECT * FROM " . $tab . "client WHERE id = " . (int)$ticket['id_client']);
    $client = $stmt_c->fetch(PDO::FETCH_ASSOC);

    $nom_client = (!empty($client['nom']) || !empty($client['prenom'])) 
        ? trim($client['nom'] . ' ' . $client['prenom']) 
        : 'Client_Anonyme';

    $clean_client = sanitize_filename($nom_client);
    $date_doc = substr($ticket['date_insert'], 0, 10);
    $id_insertion = sprintf("%05d", $ticket['id_insertion']);

    // Format exact demandé : Facture_00123_Client_Date.pdf
    $pdf_entry_name = "Facture_" . $id_insertion . "_" . $clean_client . "_" . $date_doc . ".pdf";

    // Libellé document
    switch ($ticket['type']) {
        case '1': $type_name = "FACTURE"; break;
        case '2': $type_name = "DEVIS"; break;
        case '3': $type_name = "BON DE COMMANDE"; break;
        case '4': $type_name = "BON DE LIVRAISON"; break;
        default:  $type_name = "FACTURE"; break;
    }

    // Instancier FPDF pour ce document
    $pdf = new PDF_Facture('P', 'mm', 'A4');
    $pdf->doc_type = $type_name;
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Bloc Infos Facture & Client
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(240, 244, 248);
    $pdf->SetDrawColor(210, 220, 230);

    // Encadré Numéro / Date Document
    $pdf->Rect(10, $pdf->GetY(), 88, 30, 'DF');
    $pdf->SetXY(12, $pdf->GetY() + 3);
    $pdf->Cell(84, 5, pdf_txt($type_name . ' N° : ' . $ticket['id_insertion']), 0, 1, 'L');
    $pdf->SetX(12);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(84, 5, pdf_txt('Date : ' . $date_doc), 0, 1, 'L');
    $pdf->SetX(12);
    $pdf->Cell(84, 5, pdf_txt('Code Client : ' . $ticket['id_client']), 0, 1, 'L');

    // Encadré Information Client
    $pdf->SetXY(102, $pdf->GetY() - 13);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Rect(102, $pdf->GetY() - 3, 98, 30, 'DF');
    $pdf->SetX(104);
    $pdf->Cell(94, 5, pdf_txt('CLIENT : ' . $nom_client), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(104);
    $pdf->Cell(94, 5, pdf_txt('MF : ' . ($client['MF'] ?? '-')), 0, 1, 'L');
    $pdf->SetX(104);
    $pdf->Cell(94, 5, pdf_txt('Tél/GSM : ' . ($client['tel'] ?? '') . ' / ' . ($client['gsm'] ?? '')), 0, 1, 'L');
    $pdf->SetX(104);
    $pdf->Cell(94, 5, pdf_txt('Adresse : ' . ($client['adresse'] ?? '-')), 0, 1, 'L');

    $pdf->SetY(60);

    // Tableau des Articles
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(52, 58, 64);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetDrawColor(52, 58, 64);

    $pdf->Cell(90, 7, pdf_txt('Désignation'), 1, 0, 'L', true);
    $pdf->Cell(20, 7, pdf_txt('Qte'), 1, 0, 'C', true);
    $pdf->Cell(20, 7, pdf_txt('TVA %'), 1, 0, 'C', true);
    $pdf->Cell(30, 7, pdf_txt('P.U. HT'), 1, 0, 'R', true);
    $pdf->Cell(30, 7, pdf_txt('Total HT'), 1, 1, 'R', true);

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetTextColor(30, 30, 30);
    $pdf->SetDrawColor(220, 220, 220);

    // Récupérer les lignes de détails
    $stmt_d = $pdo->query("SELECT * FROM " . $tab . "details_ticket WHERE id_tickets = " . (int)$ticket['id']);
    $details = $stmt_d->fetchAll(PDO::FETCH_ASSOC);

    $nbr_prod = 0;
    $total_tht = 0;
    $total_ttc = 0;
    $timbre = 1.000;

    foreach ($details as $detail) {
        $nbr_prod++;
        $stmt_p = $pdo->query("SELECT * FROM " . $tab . "produits WHERE id = " . (int)$detail['id_produit']);
        $produit = $stmt_p->fetch(PDO::FETCH_ASSOC);

        $nom_p = $produit['nom'] ?? 'Produit #' . $detail['id_produit'];
        $tva = isset($produit['tva']) ? (float)$produit['tva'] : 0;
        $tva_val = (1 + ($tva / 100));

        $qte = (float)$detail['qte'];
        $prix_unit_ttc = isset($produit['prix']) ? (float)$produit['prix'] : 0;
        $pu_ht = ($tva_val > 0) ? ($prix_unit_ttc / $tva_val) : $prix_unit_ttc;
        $tht = $pu_ht * $qte;
        $ttc = $prix_unit_ttc * $qte;

        $total_tht += $tht;
        $total_ttc += $ttc;

        $pdf->Cell(90, 6, pdf_txt($nom_p), 'LRB', 0, 'L');
        $pdf->Cell(20, 6, number_format($qte, 2, ',', ' '), 'RB', 0, 'C');
        $pdf->Cell(20, 6, number_format($tva, 0), 'RB', 0, 'C');
        $pdf->Cell(30, 6, number_format($pu_ht, 3, ',', ' '), 'RB', 0, 'R');
        $pdf->Cell(30, 6, number_format($tht, 3, ',', ' '), 'RB', 1, 'R');
    }

    $net_a_payer = $total_ttc + $timbre;
    $total_tva = $total_ttc - $total_tht;

    $pdf->Ln(6);

    // Bloc Recapitulatif Totaux
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(245, 245, 245);
    $pdf->SetDrawColor(200, 200, 200);

    $pdf->SetX(110);
    $pdf->Cell(40, 6, pdf_txt('Total HT :'), 1, 0, 'L', true);
    $pdf->Cell(40, 6, number_format($total_tht, 3, ',', ' ') . ' DT', 1, 1, 'R');

    $pdf->SetX(110);
    $pdf->Cell(40, 6, pdf_txt('Total TVA :'), 1, 0, 'L', true);
    $pdf->Cell(40, 6, number_format($total_tva, 3, ',', ' ') . ' DT', 1, 1, 'R');

    $pdf->SetX(110);
    $pdf->Cell(40, 6, pdf_txt('Timbre Fiscal :'), 1, 0, 'L', true);
    $pdf->Cell(40, 6, number_format($timbre, 3, ',', ' ') . ' DT', 1, 1, 'R');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(230, 240, 255);
    $pdf->SetTextColor(13, 110, 253);
    $pdf->SetX(110);
    $pdf->Cell(40, 7, pdf_txt('Net à Payer (TTC) :'), 1, 0, 'L', true);
    $pdf->Cell(40, 7, number_format($net_a_payer, 3, ',', ' ') . ' DT', 1, 1, 'R', true);

    $pdf->SetTextColor(30, 30, 30);
    $pdf->Ln(6);

    // Montant en toutes lettres
    $pdf->SetFont('Arial', 'I', 9);
    $str_net = number_format($net_a_payer, 3, '.', '');
    $lettres = function_exists('Lettre') ? Lettre($str_net) : '';
    $pdf->MultiCell(0, 5, pdf_txt("Arrêtée la présente " . $type_name . " à la somme de : " . $lettres), 0, 'L');

    // Ajouter la chaîne PDF générée dans l'archive ZIP
    $pdf_content = $pdf->Output('S');
    $zip->addFromString($pdf_entry_name, $pdf_content);
}

$zip->close();

// Envoi de l'archive ZIP au navigateur pour téléchargement direct
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
header('Content-Length: ' . filesize($temp_zip_path));
header('Pragma: no-cache');
header('Expires: 0');

readfile($temp_zip_path);
@unlink($temp_zip_path);
exit;
