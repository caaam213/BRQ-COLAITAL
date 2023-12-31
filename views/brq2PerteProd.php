<?php 
    require_once 'header.php';
?>
  <title>Production et vente</title>
  
</head>
<body>

<!-- Bouton de retour -->
<div class="row mt-3">
  <a href="<?= self::$config['base_url'] ?>index.php/brq/addOrModifyBrq?dateBrq=<?=$dateBrq?>" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
    <i class="fas fa-arrow-left"></i>
  </a>
</div>


<!-- Affichage des messages d'erreur et de succès -->
<div class="mt-2"></div>
<?php
  if (isset($_SESSION['success'])) {
      echo '<div class="alert alert-success mt-2 text-center w-75 mx-auto" role="alert">
      '.$_SESSION['success'].'
      </div>';
  }
?>
<?php
  if (isset($_SESSION['error'])) {
      echo '<div class="alert alert-danger mt-2 text-center w-75 mx-auto" role="alert">
      '.$_SESSION['error'].'
      </div>';
  }
?>

<div class="container">
<h1 class="text-center mt-3">Perte à la production du : <?= $dateBrq ?></h1>
<form class="mt-4" action=<?= self::$config["base_url"]."index.php/brq/addInfoPertesProd"?> method="post">
    <table class="table table-striped border">
        <thead>
          <tr>
            <th rowspan="2" class="border"></th>
            <th class="border">Quantités (leq)</th>
            <th class="border">Taux (%)</th>
          </tr>
        </thead>
        <tbody>
            <?php 
              foreach ($produitsPerte as $produit)
              {
                  echo '<tr>
                          <td class="border">'.$produit->getIntitule().'</td>
                          <td class="border"><input type="number" name="quantite_'.$produit->getCodeProduitPerte().'" value="0" min="0" max="100000" class="form-control"></td>
                          <td class="border"><input type="number" name="taux_'.$produit->getCodeProduitPerte().'" value="0" min="0" max="100" class="form-control"></td>
                        </tr>';
              }

            ?>
        
        <tbody>
    </table>

    <button type="submit" class="btn btn-success btn-lg btn-block">Valider</button>

</form>
