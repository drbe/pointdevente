<?php
include('fonctions.php');
echo $_REQUEST['id'];
$produits = $pdo->query("SELECT * FROM " . $tab . "produits WHERE id=" . $_REQUEST['id'] . "");
$produit = $produits->fetch();

?>
<h3>Produit : [<?php echo $_REQUEST['id'] . "] " . $produit['nom']; ?></h3>
<div class="Ajout_div">
  <form enctype="multipart/form-data" method="post" name="ajoutContact">
    <input type="hidden" name="menu" value="produits">

    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Libellé</span>
      </div>
      <input type="text" name="nom_produit" class="form-control" placeholder="Libellé" aria-label="Libellé" aria-describedby="basic-addon1" value='<?php echo $produit['nom']; ?>'>
    </div>

    <div class="input-group mb-3">
      <input type="text" name="prix" class="form-control" placeholder="Prix" value='<?php echo number_format($produit['prix'], 3, '.', ''); ?>'>
      <div class="input-group-append">
        <span class="input-group-text" id="basic-addon2">En dianr </span><span class="input-group-text">(1.234)</span></span>
      </div>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">TVA</span>
      </div>
      <input type="text" name="tva" class="form-control" placeholder="TVA" value='<?php echo number_format($produit['tva'], 1, '.', ''); ?>'>
      <div class="input-group-append">
        <span class="input-group-text">%</span></span>
      </div>
    </div>
    <div class="input-group mb-3">
      <input type="text" name="qte" class="form-control" placeholder="quantité" value="<?php echo number_format($produit['qte'], 2, '.', ''); ?>">
    </div>

    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text" for="inputGroupSelect01">Familles</label>
      </div>
      <select class="custom-select" id="inputGroupSelect01" name="famille">
        <option selected>Choix...</option>
        <?php
        $familles = $pdo->query("SELECT * FROM " . $tab . "familles");
        while ($famille = $familles->fetch()) {
          if ($produit['famille'] == $famille['id']) {
            echo '<option selected value="' . $famille['id'] . '">' . $famille['nom'] . '</option>';
          } else {
            echo '<option value="' . $famille['id'] . '">' . $famille['nom'] . '</option>';
          }
        }
        ?>
      </select>
    </div>
    <div class="row">
      <div class="col-9">
        <label for="basic-url">Code a barre</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon3">Exemple : 6 988188 701292</span>
          </div>
          <input value='<?php echo $produit['codebarre']; ?>' type="text" name="codebarre" class="form-control" id="basic-url" aria-describedby="basic-addon3">
        </div>
      </div>
      <div class="col">
        <img src="img/code.png" width="92">
      </div>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Couleur</span>
      </div>
      <input value='<?php echo $produit['couleur']; ?>' type="color" name="couleur" class="form-control" aria-label="Amount (to the nearest dollar)">
      <div class="input-group-append">
        <span class="input-group-text">#FFFFFFFF</span>
      </div>
    </div>

    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Description</span>
      </div>
      <textarea class="form-control" name="description" aria-label="With textarea"><?php echo $produit['description']; ?></textarea>
    </div>

    <div class="input-group">
      <div class="custom-file">
        <input type="file" name="image" class="custom-file-input" id="customFile" accept="image/png, image/jpeg, image/gif">
        <label class="custom-file-label" for="customFile">Choisir une image</label>
      </div>
    </div>
    <div class="input-group">
      <div class="row"><img src="data:image/jpeg;base64,<?php echo base64_encode($produit['image']); ?>" width="72" height="72" /></div>
    </div>
    <input type="hidden" name="edit_produit" value="<?php echo $produit['id']; ?>">
    <button type="submit" class="btn btn-primary btnProduit">💾 Enregistrer</button>
  </form>
</div>