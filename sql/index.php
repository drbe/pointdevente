<?php
try {
	$pdo = new PDO('mysql:host=localhost;dbname=pointdevente;charset=utf8', 'root', '');
	//$pdo = new PDO('mysql:host=einfotnoewuser.mysql.db;dbname=einfotnoewuser;charset=utf8', 'einfotnoewuser', 'Djerba2015');
} catch (Exception $e) {
	die('Erreur : ' . $e->getMessage());
}
$x = 0;
$y = -1;
$r = 0;
$nom = "";
$tab = "ptv_";
$sql = "";
if (isset($_REQUEST['dem'])) {
	$sql = "SELECT * FROM " . $tab . "produits WHERE id > " . $_REQUEST['max_id'];
} else {
	die("Acess denied !");
}
$table = explode("FROM ", $sql);
$table = explode(" WHERE", $table[1]);
$table = $table[0];
$reponse = $pdo->query($sql);
$donnees = $reponse->fetch();
foreach ($donnees as $index => $i) {
	$x++;
	($r == 0) ? $r = 1 : $r = 0;
	if ($r == 0) {
		$y++;
	} else {
		$nom .= $index . ",";
	}
}
while ($d = $reponse->fetch()) {
	echo "INSERT INTO " . $table . " (" . substr($nom, 0, -1) . ") VALUES (";
	for ($i = 0; $i <= $y; $i++) {
		if (strlen($d[$i]) <= 255) {
			echo "'" . $d[$i] . "'";
		} else {
			echo '0x' . bin2hex($d[$i]);
		}
		echo ($i < $y) ? ',' : '';
	}
	echo ")";
	//die();
}
