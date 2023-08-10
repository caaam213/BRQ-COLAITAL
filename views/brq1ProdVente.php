<?php 
    require_once 'header.php';
?>
  <title>Production et vente</title>
</head>
<body>

<!-- Bouton de retour -->
<div class="row">
  <a href="<?= self::$config['base_url'] ?>index.php/home" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
    <i class="fas fa-arrow-left"></i>
  </a>
</div>
<h1 class="text-center mt-3">Interface brq</h1>

<!-- Affichage des messages d'erreur et de succès -->
<div class="mt-5"></div>
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
  <h2>Production de la journée du : <?= $date_brq ?></h2>
    <table class="table table-striped border">
      <thead>
        <tr>
          <th class="border">Produits</th>
          <th class="border">Unité de mesure</th>
          <th class="border">Objectifs</th>
          <th class="border">Réalisations</th>
          <th class="border">Prix unitaire</th>
          <th class="border">Quantité</th>
          <th class="border">Valeur (DA)</th>
          <th class="border">Stock fin de journée</th>
        </tr>
      </thead>
      <tbody>
        
        <?php
          foreach($categoriesProduits as $categorie)
          {
            echo '<tr>';
            echo '<td class="fw-bold border">'.$categorie->getNomCategorie().'</td>';
            echo '<td class="fw-bold border text-center">'.$unitesParId[$categorie->getIdUnite()].'</td>';
            echo '<td class="border text-center">'.$objectifsParCategorie[$categorie->getIdCategorie()].'</td>';
            echo '<td class="border"></td>';
            echo '<td class="border"></td>';
            echo '<td class="border"></td>';
            echo '<td class="border"></td>';
            echo '<td class="border"></td>';
            echo '</tr>';

            // Produits 
            foreach ($produitsParCategorie[$categorie->getIdCategorie()] as $produit)
            {
              echo '<tr>';
              echo '<td class="border"> - '.$produit->getNomProduit().'</td>';
              echo '<td class="border text-center">'.$unitesParId[$produit->getIdUnite()].'</td>';
              echo '<td class="border text-center">'.$produit->getObjectif().'</td>';
              echo '<td class="border"><input type="number" id="realisationProduit'.$produit->getIdProduit().'" name="realisationProduit'.$produit->getIdProduit().'" /></td>';
              echo '<td class="border"></td>';
              echo '<td class="border"><input type="number" id="qteProduit'.$produit->getIdProduit().'" name="qteProduit'.$produit->getIdProduit().'" /></td>';
              echo '<td class="border"><input type="number" id="valeurProduit'.$produit->getIdProduit().'" name="valeurProduit'.$produit->getIdProduit().'" /></td>';
              echo '<td class="border"></td>';
              echo '</tr>';
            }
          }


        ?>
      </tbody>
        
    </table> 

</div>