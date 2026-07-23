<h3>Statistique <?php echo $_REQUEST['type'];?></h3>
<div class="container-fluid">
<div class="alert alert-primary" role="alert">
<div class="container">
<form>
  <div class="row">
    <div class="col-sm">
      <input type="hidden" value="stats" name="menu">
      <input type="hidden" value="<?php echo $_REQUEST['type'];?>" name="type">
		<input class="form-control col-sm" type="date" id="date" name="recherche_date" value="<?php echo (isset($_GET['recherche_date']))? $_GET['recherche_date'] : date("Y-m-d"); ?>">
    </div>
    <div class="col-sm">
		<input class="form-control col-sm" type="date" id="date_fin" name="recherche_date_fin" value="<?php echo (isset($_GET['recherche_date_fin']))? $_GET['recherche_date_fin'] : date("Y-m-d"); ?>">
    </div>
    <div class="col-sm">
      <input class="btn btn-outline-success my-2 my-sm-0  col" type="submit" value="Recherche">
    </div>
  </div>
</form>
</div>
</div>
	<table class="table  table-dark">
	  <thead>
<?php
$order="";
$where_date="";
$page=0;
$bloc=20;
if(isset($_REQUEST['bloc'])){$bloc=$_REQUEST['bloc'];}
if(isset($_REQUEST['page'])){$page=$bloc*($_REQUEST['page']-1);}
if(isset($_REQUEST['recherche_date']))
	{
	$where_date=" and date(".$tab."ticket.date_insert) BETWEEN '".$_REQUEST['recherche_date']."' AND '".$_REQUEST['recherche_date_fin']."'";
	}
 switch ($_REQUEST['type'])
 {
	 case "produits":
	 include("stats/produits.php");
	 break;
	 case "clients":
	 include("stats/clients.php");
	 break;
	 case "utilisateurs":
	 include("stats/utilisateurs.php");
	 break;
	 case "familles":
	 include("stats/familles.php");
	 break;
 }
 ?>
		</center>
	</div>
	</div>
</div>
	  </tbody>
</table>
	<div class="alert alert-secondary row" role="alert">
	<div class="col">
	<center>
		<?php
		$i=0;
		$reponse = $pdo->query($sql);
		while ($donnees = $reponse->fetch()){$i++;}
		$max_page=ceil($i/$bloc);
		if($max_page>1){
		for ($i=1;$i<=$max_page;$i++)
		{
			echo "<a class='btn btn-primary' href='admin.php?menu=".$_GET['menu']."&type=".$_GET['type']."&page=".$i."&bloc=".$bloc;
			echo (isset($_GET['recherche_date']))? "&recherche_date=".$_GET['recherche_date'] : "";
			echo (isset($_GET['recherche_date_fin']))? "&recherche_date_fin=".$_GET['recherche_date_fin'] : "";
			echo "&order=";
			echo $order;
			echo "&dir=asc'>";
			echo (isset($_GET["page"]) && $_GET["page"]==$i) ?"<u>".$i."</u>" :$i;
			echo "</a> ";
		}
		}?>