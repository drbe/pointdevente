<?php
try
{
	$db = new PDO('mysql:host=localhost;dbname=pointdevente;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
var_dump($db);
if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
    $stmt = $db->prepare('select * from produits',
         array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
} else {
    die("mon application fonctionne seulement avec mysql; Je devrais utiliser \$stmt->fetchAll() à la place");
}
var_dump($stmt);
$reponse = $db->query('SELECT * FROM familles');

$sql = "INSERT INTO familles (nom) VALUES (?)";
$stmt= $db->prepare($sql);
$stmt->execute(["wael"]);

var_dump($reponse);
while ($donnees = $reponse->fetch())
{
echo $donnees['id'];
}
echo "fin";
?>