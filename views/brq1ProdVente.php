<?php 
    require_once 'header.php';
?>
  <title>Production et vente</title>
  
</head>
<body>

<!-- Bouton de retour -->
<div class="row mt-3">
  <a href="<?= self::$config['base_url'] ?>index.php/brq" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
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

<?php 

  if(!$brqFound)
  {
    echo '<div class="alert alert-danger mt-2 text-center w-75 mx-auto" role="alert">
    Le BRQ à la periode précedente n\'a pas été trouvé
    </div>';

    // Créer le bouton pour créer un nouveau brq
    $previousDate = date('Y-m-d', strtotime($date_brq . ' -1 day'));
    echo '<div class="row mt-3 justify-content-center">
    <a href="'.self::$config['base_url'].'index.php/brq/addOrModifyBrq?dateBrq='.$previousDate.'" class="btn btn-primary btn-icon btn-rounded-circle mx-auto w-25">
      <i class="fas fa-plus"></i>
      Remplir le BRQ à la date du '.$previousDate.'
      </a>
    </div>';
  }
  else
  {

?>

<!-- TODO : Vérifier s'il y a des produits dans la BD -->

<div class="container">
<h1 class="text-center mt-3">Production de la journée du : <?= $date_brq ?></h1>
<?php 
  if($isFirstBrq || $date_brq == self::$config['date_begin_brq']) 
  {
    $style = "";
    ?>
    <p class="text-center text-warning">Ce BRQ est le premier BRQ rempli. 
      Veuillez renseigner le stock de début de journée des produits pour la première fois</p>
    
      

  <?php 
  }
  else
  {
    $style = "display: none";
  }

  if(!$brqFound || $isFirstBrq)
  {
    $urlForm = self::$config["base_url"]."index.php/brq/createBrq";
  }
  else
  {
    $urlForm = self::$config["base_url"]."index.php/brq/updateBrq";
  }
  
  ?>
  <div class="alert alert-danger mt-2 text-center w-75 mx-auto" style="display: none" id="erreurSaisie" role="alert">
  </div>
    <form class="mt-4" action=<?= $urlForm ?> method="post">
      
      <table class="table table-striped border">
        <thead>
          <tr>
            <th colspan="3"></th>
            <th class="border text-center" colspan="3">Production</th>
            <th class="border text-center" colspan="3">Vente</th>
          </tr>

          <tr>
            <th class="border">Produits</th>
            <th class="border">Unité de mesure</th>
            <th class="border" style="<?= $style ?>">Stock début de journée</th>
            
            <th class="border">Objectifs</th>
            <th class="border">Réalisations</th>
            <th class="border">Perte en production</th>
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
              if($categorie->getNomCategorie() != 'Autres')
              {
                echo '<tr class="categories" id="rowCategorie'.$categorie->getIdCategorie().'">';
                echo '<td class="fw-bold border" id="nomCategorie'.$categorie->getIdCategorie().'">'.$categorie->getNomCategorie().'</td>';
                echo '<td class="fw-bold border text-center">'.$unitesParId[$categorie->getIdUnite()]["nom"].'</td>';
                echo '<td class="fw-bold border text-center" style="'.$style.'" id="stockDebutCat'.$categorie->getIdCategorie().'"></td>';
                echo '<td class="border text-center" id="objectifCat'.$categorie->getIdCategorie().'"></td>';
                echo '<td class="border" id="realisationCat'.$categorie->getIdCategorie().'"></td>';
                echo '<td class="border" id="perteCat'.$categorie->getIdCategorie().'"></td>';
                echo '<td class="border"></td>';
                echo '<td class="border" id="qteCat'.$categorie->getIdCategorie().'"></td>';
                echo '<td class="border" id="valeurCat'.$categorie->getIdCategorie().'"></td>';
                echo '<td class="border" id="stockFinCat'.$categorie->getIdCategorie().'"></td>';
                echo '</tr>';
              }
              

              // Produits 
              foreach ($produitsParCategorie[$categorie->getIdCategorie()] as $produit)
              {
                // Si les informations du produit existe dans la BD
                $stockDebutProduit = 0;
                if($date_brq == self::$config['date_begin_brq'] && isset($brqProduitsArray[$produit->getIdProduit()]))
                {
                  $stockDebutProduit = $brqProduitsArray[$produit->getIdProduit()]->getStockDebut();
                }
                else if($previousBrq != null)
                {
                  $stockDebutProduit = $previousBrqProduitsArray[$produit->getIdProduit()]->getStockDebut();
                }
              

            
                $objectifProduit = ($brq!=null && $brqProduitsArray!=null) ? $brqProduitsArray[$produit->getIdProduit()]->getObjectif() : null;
                $realisationProduit = ($brq!=null && $brqProduitsArray!=null) ? $brqProduitsArray[$produit->getIdProduit()]->getRealisation() : '';
                $perteProduit = ($brq!=null && $brqProduitsArray!=null) ? $brqProduitsArray[$produit->getIdProduit()]->getPerteProd() : '';
                $qteProduit = ($brq!=null && $brqProduitsArray!=null) ? $brqProduitsArray[$produit->getIdProduit()]->getQuantiteVente() : '';
                $valeurProduit = ($brq!=null && $brqProduitsArray!=null) ? $brqProduitsArray[$produit->getIdProduit()]->getValeurVente() : '';

                echo '<tr id="rowProduct'.$produit->getIdProduit().'">';
                echo '<td class="border" id="nomProduit'.$produit->getIdProduit().'"> - '.$produit->getNomProduit().'</td>';
                echo '<td class="border text-center" id="uniteMesureProduit'.$produit->getIdProduit().'">'.$unitesParId[$produit->getIdUnite()]["nom"].'</td>';

                echo '<td class="border" style="'.$style.'">
                  <input type="number" class="stocksDebuts' . 
                      (($categorie->getCodeCategorie() != 'AUTRES' && $categorie->getCodeCategorie() != 'LAIT PUR') ? " stocksDebutsProduitLaitier" : "") .
                      ' stocksDebutsCat'.$categorie->getIdCategorie(). 
                  '" id="stocksDebutProduit' . $produit->getIdProduit() . '" name="stocksDebutProduit' . $produit->getIdProduit() . '" value="' . $stockDebutProduit .'" />
                  </td>';
                

                //Objectifs
                echo '<td class="border text-center">';
                echo '<input type="hidden" class="objectifs' . 
                    (($categorie->getCodeCategorie() != 'AUTRES' && $categorie->getCodeCategorie() != 'LAIT PUR') ? " objectifsProduitLaitier" : "") .
                    ' objectifsCat' . $categorie->getIdCategorie() . '"  id="objectifProduit' . $produit->getIdProduit() . '" name="objectifProduit' . $produit->getIdProduit() . '" value="' . (($objectifProduit == null) ? $produit->getObjectif() : $objectifProduit) . '" />';
                echo '<span id="affichageObjectifProduit' .$produit->getObjectif().'">' . (($objectifProduit == null) ? $produit->getObjectif() : $objectifProduit) . '</span>';
                echo '</td>';

                // Variable de désactivation si le rôle correspond à la vente
                $disabledProd = $_SESSION["code_role"] == 'VENTE' ? 'disabled' : '';


                // Realisations
                echo '<td class="border">
                    <input type="number" class="realisations' . 
                        (($categorie->getCodeCategorie() != 'AUTRES' && $categorie->getCodeCategorie() != 'LAIT PUR') ? " realisationsProduitLaitier" : "") .
                        ' realisationsCat'.$categorie->getIdCategorie(). 
                    '" id="realisationProduit' . $produit->getIdProduit() . '" name="realisationProduit' . $produit->getIdProduit() . '" '.$disabledProd.' value="'.$realisationProduit.'" />
                </td>';

                // Perte en production
                echo '<td class="border">
                    <input type="number" class="pertes pertesProduit' . 
                        (($categorie->getCodeCategorie() != 'AUTRES' && $categorie->getCodeCategorie() != 'LAIT PUR') ? " pertesProduitLaitier" : "") .
                        ' pertesCat' . $categorie->getIdCategorie() . '" id="perteProduit' . $produit->getIdProduit() . '" name="perteProduit' . $produit->getIdProduit() . '" '.$disabledProd.' value="'.$perteProduit.'" />
                        </td>';

                // Prix unitaire
                echo '<td class="border prixUnitaires" id="PUProduit'.$produit->getIdProduit().'"></td>';

                // Variable de désactivation si le rôle correspond à la production
                $disabledVente = $_SESSION["code_role"] == 'PROD' ? 'disabled' : '';

                // Quantités
                echo '<td class="border"><input type="number" min="0" class="quantites quantitesInput'.
                (($categorie->getCodeCategorie() != 'AUTRES' && $categorie->getCodeCategorie() != 'LAIT PUR') ? " qtesProduitLaitier" : "").
                ' qtesCat'.$categorie->getIdCategorie()
                .'" id="qteProduit'.$produit->getIdProduit().'" name="qteProduit'.$produit->getIdProduit().'" '.$disabledVente.'  value="'.$qteProduit.'" /></td>';
                
                // Valeurs
                echo '<td class="border"><input type="number" class="valeurs valeursInput'.
                (($categorie->getCodeCategorie() != 'AUTRES' && $categorie->getCodeCategorie() != 'LAIT PUR') ? " valeursProduitLaitier" : "").
                ' valeursCat'.$categorie->getIdCategorie()
                .'" id="valeurProduit'.$produit->getIdProduit().'" name="valeurProduit'.$produit->getIdProduit().'" '.$disabledVente.' value="'.$valeurProduit.'" /></td>';
                
                // Stock fin de journée
                echo '<td class="border">';
                echo '<input type="hidden" class="stocksFins' . 
                    (($categorie->getCodeCategorie() != 'AUTRES' && $categorie->getCodeCategorie() != 'LAIT PUR') ? " stocksFinsProduitLaitier" : "") .
                    ' stocksFinsCat' . $categorie->getIdCategorie() . '" id="stockFinProduit' . $produit->getIdProduit() . '" name="stockFinProduit' . $produit->getIdProduit() . '" />';
                echo '<span id="affichageStockFinProduit' . $produit->getIdProduit() . '"></span>';
                echo '</td>';

                // Multiplicateur
                $multiplicateur = $unitesParId[$produit->getIdUnite()]["multiplicateur"];

                // Si le produit est de la crème fraîche
                if(strpos($produit->getCodeProduit(), 'CREME_FRAICHE'))
                {
                  $multiplicateur = 10;
                }

                // Si le produit est du beurre
                if($categorie->getCodeCategorie() == 'BEURRE')
                {
                  $multiplicateur = 1000/$multiplicateur;
                }
                
                echo '<input type="hidden" class="multiplicateurs multiplicateursCat'.$categorie->getIdCategorie().'" 
                id="multiplicateurProduit'.$produit->getIdProduit().'" name="multiplicateurProduit'.$produit->getIdProduit().'" 
                value="'.$multiplicateur.'" />';

                echo '<input type="hidden" id="produitNomCat'.$produit->getIdProduit().'" name="produitNomCat'.$produit->getIdProduit().'" 
                value="'.$categorie->getCodeCategorie().'" />';




                echo '</tr>';
              }
            }


          ?>

          <!-- Produits laitiers -->
          <tr>
            <td class="fw-bold border">Produits laitiers</td>
            <td class="fw-bold border text-center">LEQ</td>
            <?php if ($date_brq == self::$config['date_begin_brq']) { echo '<td class="fw-bold border text-center" id="produitsLaitiersStockDebut"></td>';} ?>
            <td class="fw-bold border text-center" id="produitsLaitiersObjectif"></td>
            <td class="fw-bold border text-center" id="produitsLaitiersRealisation"></td>
            <td class="fw-bold border text-center" id="produitsLaitiersPerte"></td>
            <td class="fw-bold border text-center" id="produitsLaitiersPU"></td>
            <td class="fw-bold border text-center" id="produitsLaitiersQte"></td>
            <td class="fw-bold border text-center" id="produitsLaitiersValeur"></td>
            <td class="fw-bold border text-center" id="produitsLaitiersStockFin"></td>
          </tr>

          <!-- Total tout fait -->
          <tr>
            <td class="fw-bold border">Total tout fait</td>
            <td class="fw-bold border text-center">LEQ</td>
            <?php if ($date_brq == self::$config['date_begin_brq']) {echo '<td class="fw-bold border text-center" id="totalToutFaitStockDebut"></td>';} ?>
            <td class="fw-bold border text-center" id="totalToutFaitObjectif"></td>
            <td class="fw-bold border text-center" id="totalToutFaitRealisation"></td>
            <td class="fw-bold border text-center" id="totalToutFaitPerte"></td>
            <td class="fw-bold border text-center" id="totalToutFaitPU"></td>
            <td class="fw-bold border text-center" id="totalToutFaitQte"></td>
            <td class="fw-bold border text-center" id="totalToutFaitValeur"></td>
            <td class="fw-bold border text-center" id="totalToutFaitStockFin"></td>
          </tr>

        </tbody>
          
      </table>

      <input type="hidden" id="date_brq" name="date_brq" value="<?php echo $date_brq; ?>" />      

      <!--Bouton pour confirmer le tableau -->
      <div class="row mt-2 col-md-8 col-4 mx-auto mb-2">              
        <button type="submit" id="submit" class="btn btn-success mx-auto">
            <i class="fas fa-floppy-disk"></i>
              Suivant
        </button>
      </div>

    </form> 

</div>
<script>

// Fonctions de calculs


/*
  Mettre à jour le prix unitaire d'un produit en fonction de la quantité et de la valeur
*/



function updatePU(idQteOrVal)
{

  // On récupère l'id du produit
  if( idQteOrVal.includes('qteProduit'))
    {
      var idProduit = idQteOrVal.replace('qteProduit', '');
    }
  else
    {
      var idProduit = idQteOrVal.replace('valeurProduit', '');
    }
  

  // A chaque changement des valeurs de la quantité ou des valeurs, on met à jour le prix unitaire
  var qte = document.getElementById('qteProduit'+idProduit)=== null ? 0 : document.getElementById('qteProduit'+idProduit).value;
  var valeur = document.getElementById('valeurProduit'+idProduit) === null ? 0 : document.getElementById('valeurProduit'+idProduit).value;

  if(qte == 0 && valeur == 0)
  {
      document.getElementById('PUProduit'+idProduit).innerHTML = '';
  }
  else
  {
    // On convertit les valeurs en entier
      qte = parseInt(qte);
      valeur = parseInt(valeur);
      var PU = valeur / qte;

      // On vérifie si PU est négatif ou s'il y a une division par 0
      if(PU < 0 || isNaN(PU))
      {
        document.getElementById('PUProduit'+idProduit).innerHTML = 'ERREUR';
      }
      else if(PU == Infinity)
      {
      document.getElementById('PUProduit'+idProduit).innerHTML = 'DIV/0';
      }
      else
      {
      document.getElementById('PUProduit'+idProduit).innerHTML = PU.toFixed(2);
      }
  }
}

/*
  Fonction qui permet de calculer la valeur des champs avec un multiplicateur pour les LEQ
*/
function calculateWMultiplicateur(element, codeCat, parseMethod, multiplicateur)
{
  if(parseMethod == 'parseInt')
  {
    value = isNaN(parseInt(element)) ? 0 : parseInt(element);
  }
  else if(parseMethod == 'parseFloat')
  {
    value = isNaN(parseFloat(element)) ? 0 : parseFloat(element);
  }
  else
  {
    value = element;
  }
  
  value = value *multiplicateur;
  if(codeCat == 'BEURRES')
  {
    value = value * 24;
  }
  return value;
}

/*
  Fonction utilitaire pour mettre à jour les valeurs des champs qui nécessite un calcul
*/
function updateValues(className, parseMethod, idName)
{
  var elements = document.getElementsByClassName(className);
  var total = 0;
  for (var i = 0; i < elements.length; i++) {

    var element = elements[i].value == '' ? 0 : elements[i].value;

    if(parseMethod == 'parseInt')
    {
      total += isNaN(parseInt(element)) ? 0 : parseInt(element);
      
    }
    else if(parseMethod == 'parseFloat')
    {
      total += isNaN(parseFloat(element)) ? 0 : parseFloat(element);
    }
    else
    {
      total += element;
    }
  }
  
  document.getElementById(idName).innerHTML = total;
  document.getElementById(idName).value = total;
}

/* 
  Fonction qui met à jour les champs qui nécessite un multiplicateur  
*/
function updateValuesWMultiplicateur(className, parseMethod, idName)
{
  var elements = document.getElementsByClassName(className);
  var total = 0;

  for (var i = 0; i < elements.length; i++) {

    var element = elements[i].value == '' ? 0 : elements[i].value;

    // Récupérer des ids 
    var idProduit = elements[i].id.replace(elements[i].id.match(/[a-zA-Z]+/g), '');
    
    

    // Récupérer le nom de la catégorie
    var codeCat = document.getElementById('produitNomCat'+idProduit).value;
    

    // Multiplicateur
    var multiplicateur = document.getElementById("multiplicateurProduit"+idProduit).value;


    if(parseMethod == 'parseInt')
    {
      value = calculateWMultiplicateur(element, codeCat, 'parseInt', multiplicateur);
      total += value;
      
    }
    else if(parseMethod == 'parseFloat')
    {
      value = calculateWMultiplicateur(element, codeCat, 'parseFloat', multiplicateur);
      total += value;
    }
    else
    {
      total += element;
    }

  }

  document.getElementById(idName).innerHTML = Number((total).toFixed(2));
  document.getElementById(idName).value = Number((total).toFixed(2));

}



/*
  Récupérer tous les id des catégories
*/
function getAllIdCategories()
{
  
  var categories = document.getElementsByClassName('categories');
  var categoriesId = [];
  for (var i = 0; i < categories.length; i++) {
    var categorie = categories[i].id.replace('rowCategorie', '');
    categoriesId.push(categorie);
  };
  return categoriesId;
}

/*
  Mettre à jour les stocks finaux des catégories en ajoutant les stocks de chaque produit liés
*/ 
function updateAllStockCat()
{
  
  var categoriesId = getAllIdCategories();
  for (var i = 0; i < categoriesId.length; i++) {
    updateValuesWMultiplicateur('stocksFinsCat'+categoriesId[i], 'parseInt','stockFinCat'+categoriesId[i] )
  }
}

/*
  Mettre à jour les stocks finaux des produits laitiers et total
*/
function updateAllStockProduit(className, idToUpdateName)
{ 
  updateValuesWMultiplicateur(className, 'parseInt',idToUpdateName )
}

/*
  Change les couleurs de l'input des réalisations lorsque : 
  rouge : objectif > réalisation
  vert : objectif <= réalisation
*/
function applyColorCoding() {
  var realisations = document.getElementsByClassName('realisations');
  for (var i = 0; i < realisations.length; i++) {
      var idProduit = realisations[i].id.replace('realisationProduit', '');
      var objectif = document.getElementById('objectifProduit'+idProduit).value;
      var realisation = document.getElementById('realisationProduit'+idProduit).value;

      // On convertit les valeurs en entier
      objectif = parseInt(objectif);
      realisation = parseInt(realisation);


      if (isNaN(realisation) || (realisation==0)) {
        realisations[i].style.backgroundColor = '#ffffff';
      } else if (realisation < objectif) {   
      // On colore en rouge la ligne du produit
      realisations[i].style.backgroundColor = '#ffcccc';
      } else {
      // On colore en vert la ligne du produit
        realisations[i].style.backgroundColor = '#ccffcc';
      }
  }
}

/*
  Vérifier si les réalisations sont inférieures aux pertes
*/
function verifyRealisationAndPertes(realisationId, perteId)
{
  var realisation = parseInt(document.getElementById(realisationId).value);
  var perte = parseInt(document.getElementById(perteId).value);

  if(realisation < perte)
  {
    document.getElementById('erreurSaisie').innerHTML = "Attention, au moins une valeurs des pertes est incorrecte";
    document.getElementById('erreurSaisie').style.display = "inline";
    document.getElementById('stockFinProduit'+realisationId.replace('realisationProduit', '')).innerHTML = "ERREUR";

    // On désactive le bouton de confirmation
    document.getElementById('submit').disabled = true;

  } 
  else
  {
    document.getElementById('erreurSaisie').innerHTML = "";
    document.getElementById('erreurSaisie').style.display = "none";

    // On active le bouton de confirmation
    document.getElementById('submit').disabled = false;
  }
}


/*
  --- Changement du couleur de l'input des réalisations ---
*/

// Ajouter un évènements aux inputs de réalisation afin de les comparer aux objectifs
var realisations = document.getElementsByClassName('realisations');
for (var i = 0; i < realisations.length; i++) {
    realisations[i].addEventListener('keyup', applyColorCoding);
}




/*
  --- Mettre à jour les produits unitaires ---
*/

// On ajoute l'évènement aux inputs
var quantites = document.getElementsByClassName('quantitesInput');
var valeurs = document.getElementsByClassName('valeursInput');
for (var i = 0; i < quantites.length; i++) {
    quantites[i].addEventListener('keyup', function()
  {
    updatePU(this.id);
  });
    valeurs[i].addEventListener('keyup', function()
  {
    updatePU(this.id);
  });
  updatePU(quantites[i].id);
}



/*
  --- Stocks début de journée --- 
*/

// A chaque fois qu'on change les valeurs des stocks, on recalcule le stock total des produits laitiers
var stocksDebutProduitLaitier = document.getElementsByClassName('stocksDebutsProduitLaitier');
// Evènement sur les inputs de stock
for (var i = 0; i < stocksDebutProduitLaitier.length; i++) {
  stocksDebutProduitLaitier[i].addEventListener('keyup', function()
  {
    updateValuesWMultiplicateur('stocksDebutsProduitLaitier', 'parseInt', 'produitsLaitiersStockDebut');
  });
};

// A chaque fois qu'on change les valeurs des stocks de début de journée, on recalcule le stock de début de journée total
var stocksDebut = document.getElementsByClassName('stocksDebuts');
  // Evènement sur les inputs de stock de début de journée
  for (var i = 0; i < stocksDebut.length; i++) {
    stocksDebut[i].addEventListener('keyup',function()
    {
      updateValuesWMultiplicateur('stocksDebuts', 'parseInt', 'totalToutFaitStockDebut');
    });
  };

  updateValuesWMultiplicateur('stocksDebutsProduitLaitier', 'parseInt', 'produitsLaitiersStockDebut');
  updateValuesWMultiplicateur('stocksDebuts', 'parseInt', 'totalToutFaitStockDebut');

/** Objectifs */

// Mettre à jour les objectifs laitiers et totaux
updateValuesWMultiplicateur('objectifsProduitLaitier', 'parseInt', 'produitsLaitiersObjectif');
updateValuesWMultiplicateur('objectifs', 'parseInt', 'totalToutFaitObjectif');


  
/** Réalisations */

// A chaque fois qu'on change les valeurs des réalisations, on recalcule la réalisation totale des produits laitiers 
var realisationsProduitLaitier = document.getElementsByClassName('realisationsProduitLaitier');
// Evènement sur les inputs de réalisation
for (var i = 0; i < realisationsProduitLaitier.length; i++) {
  realisationsProduitLaitier[i].addEventListener('keyup',function()
  {
    updateValuesWMultiplicateur('realisationsProduitLaitier', 'parseInt', 'produitsLaitiersRealisation');
    
  });
};

// A chaque fois qu'on change les valeurs des réalisations, on recalcule la réalisation totale
var realisations = document.getElementsByClassName('realisations');
  // Evènement sur les inputs de réalisation
  for (var i = 0; i < realisations.length; i++) {
    realisations[i].addEventListener('keyup',function()
    {
      updateValuesWMultiplicateur('realisations', 'parseInt', 'totalToutFaitRealisation');
      // On récupère l'id du produit
      var idProduit = this.id.replace('realisationProduit', '');
      verifyRealisationAndPertes('realisationProduit'+idProduit, 'perteProduit'+idProduit);

    });
};

// Lors du chargement de la page
updateValuesWMultiplicateur('realisationsProduitLaitier', 'parseInt', 'produitsLaitiersRealisation');
updateValuesWMultiplicateur('realisations', 'parseInt', 'totalToutFaitRealisation');



/** Pertes */

// A chaque fois qu'on change les valeurs des pertes, on recalcule la perte totale des produits laitiers
var pertesProduitLaitier = document.getElementsByClassName('pertesProduitLaitier');
// Evènement sur les inputs de perte
for (var i = 0; i < pertesProduitLaitier.length; i++) {
  pertesProduitLaitier[i].addEventListener('keyup',function()
  {
    updateValuesWMultiplicateur('pertesProduitLaitier', 'parseInt', 'produitsLaitiersPerte');
    
  });
};

// A chaque fois qu'on change les valeurs des pertes, on recalcule la perte totale
var pertes = document.getElementsByClassName('pertes');
  // Evènement sur les inputs de perte
  for (var i = 0; i < pertes.length; i++) {
    pertes[i].addEventListener('keyup',function()
    {
      updateValuesWMultiplicateur('pertes', 'parseInt', 'totalToutFaitPerte');
      // On récupère l'id du produit
      var idProduit = this.id.replace('perteProduit', '');
      verifyRealisationAndPertes('realisationProduit'+idProduit, 'perteProduit'+idProduit);
    });
    
  };

  // Lors du chargement de la page
  updateValuesWMultiplicateur('pertesProduitLaitier', 'parseInt', 'produitsLaitiersPerte');
  updateValuesWMultiplicateur('pertes', 'parseInt', 'totalToutFaitPerte');
  


  /** Quantités */

  // A chaque fois qu'on change les valeurs des quantités, on recalcule la quantité totale des produits laitiers
  var quantitesProduitLaitier = document.getElementsByClassName('qtesProduitLaitier');

  // Evènement sur les inputs de quantité
  for (var i = 0; i < quantitesProduitLaitier.length; i++) {
      quantitesProduitLaitier[i].addEventListener('keyup', function() {
        updateValuesWMultiplicateur('qtesProduitLaitier', 'parseInt', 'produitsLaitiersQte');
      });
  };

  // A chaque fois qu'on change les valeurs des quantités, on recalcule la quantité totale
  var quantites = document.getElementsByClassName('quantites');
  // Evènement sur les inputs de quantité
  for (var i = 0; i < quantites.length; i++) {
      quantites[i].addEventListener('keyup', function() {
        updateValuesWMultiplicateur('quantites', 'parseInt', 'totalToutFaitQte');
      });
  };

  // Lors du chargement de la page
  updateValuesWMultiplicateur('qtesProduitLaitier', 'parseInt', 'produitsLaitiersQte');
  updateValuesWMultiplicateur('quantites', 'parseInt', 'totalToutFaitQte');
  

  /** Valeurs */

  // A chaque fois qu'on change les valeurs des valeurs, on recalcule la valeur totale des produits laitiers
  var valeursProduitLaitier = document.getElementsByClassName('valeursProduitLaitier');
  // Evènement sur les inputs de valeur
  for (var i = 0; i < valeursProduitLaitier.length; i++) {
    valeursProduitLaitier[i].addEventListener('keyup', function()
    {
      updateValuesWMultiplicateur('valeursProduitLaitier', 'parseFloat', 'produitsLaitiersValeur');
    });
  };

  // A chaque fois qu'on change les valeurs des valeurs, on recalcule la valeur totale
  var valeurs = document.getElementsByClassName('valeurs');
  // Evènement sur les inputs de valeur
  for (var i = 0; i < valeurs.length; i++) {
    valeurs[i].addEventListener('keyup', function()
    {
      updateValues('valeurs', 'parseFloat', 'totalToutFaitValeur');
    });
  };

  // Lors du chargement de la page
  updateValuesWMultiplicateur('valeursProduitLaitier', 'parseFloat', 'produitsLaitiersValeur');
  updateValues('valeurs', 'parseFloat', 'totalToutFaitValeur');

  

  /** Stocks fin journée */ 

  // A chaque fois qu'on change les valeurs des stocks, on recalcule le stock total des produits laitiers
  var stocksFinProduitLaitier = document.getElementsByClassName('stocksFinsProduitLaitier');
  // Evènement sur les inputs de stock
  for (var i = 0; i < stocksFinProduitLaitier.length; i++) {
    stocksFinProduitLaitier[i].addEventListener('keyup', function()
    {
      updateValuesWMultiplicateur('stocksFinsProduitLaitier', 'parseInt', 'produitsLaitiersStockFin');
    });
  };

  // A chaque fois qu'on change les valeurs des stocks de fin de journée, on recalcule le stock de fin de journée total
  var stocksFin = document.getElementsByClassName('stocksFins');
  // Evènement sur les inputs de stock de fin de journée
  for (var i = 0; i < stocksFin.length; i++) {
    stocksFin[i].addEventListener('keyup',function()
    {
      updateValuesWMultiplicateur('stocksFins', 'parseInt', 'totalToutFaitStockFin');
    });
  };

  // Lors du chargement de la page
  updateValuesWMultiplicateur('stocksFinsProduitLaitier', 'parseInt', 'produitsLaitiersStockFin');
  updateValuesWMultiplicateur('stocksFins', 'parseInt', 'totalToutFaitStockFin');

  



  /** Valeurs des catégories */
  var categoriesId = getAllIdCategories();

  for (var i = 0; i < categoriesId.length; i++) {
    var realisationsProduit = document.getElementsByClassName('realisationsCat'+categoriesId[i]);
    var objectifsProduit = document.getElementsByClassName('objectifsCat'+categoriesId[i]);
    var qtes = document.getElementsByClassName('qtesCat'+categoriesId[i]);
    var valeurs = document.getElementsByClassName('valeursCat'+categoriesId[i]);
    var stockDebutJournees = document.getElementsByClassName('stocksDebutsCat'+categoriesId[i]);
    var stockFinJournees = document.getElementsByClassName('stocksFinsCat'+categoriesId[i]);
    var perteProds = document.getElementsByClassName('pertesCat'+categoriesId[i]);

    updateValuesWMultiplicateur('objectifsCat'+categoriesId[i], 'parseInt', 'objectifCat'+categoriesId[i]);
    updateValuesWMultiplicateur('realisationsCat'+categoriesId[i], 'parseInt', 'realisationCat'+categoriesId[i]);
    updateValuesWMultiplicateur('qtesCat'+categoriesId[i], 'parseInt', 'qteCat'+categoriesId[i]);
    updateValuesWMultiplicateur('valeursCat'+categoriesId[i], 'parseFloat', 'valeurCat'+categoriesId[i]);
    updateValuesWMultiplicateur('stocksDebutsCat'+categoriesId[i], 'parseInt', 'stockDebutCat'+categoriesId[i]);
    updateValuesWMultiplicateur('stocksFinsCat'+categoriesId[i], 'parseInt', 'stockFinCat'+categoriesId[i]);
    updateValuesWMultiplicateur('pertesCat'+categoriesId[i], 'parseInt', 'perteCat'+categoriesId[i]);


    for (var j = 0; j < realisationsProduit.length; j++) {


        realisationsProduit[j].addEventListener('keyup', ((categoryId) => {
            return () => {
              updateValuesWMultiplicateur('realisationsCat'+categoryId, 'parseInt', 'realisationCat'+categoryId);
            };
        })(categoriesId[i]));

        qtes[j].addEventListener('keyup', ((categoryId) => {
            return () => {
              updateValuesWMultiplicateur('qtesCat'+categoryId, 'parseInt', 'qteCat'+categoryId);
            };
        })(categoriesId[i]));

        valeurs[j].addEventListener('keyup', ((categoryId) => {
            return () => {
                updateValues('valeursCat'+categoryId, 'parseFloat', 'valeurCat'+categoryId);
            };
        })(categoriesId[i]));

        stockDebutJournees[j].addEventListener('keyup', ((categoryId) => {
            return () => {
              updateValuesWMultiplicateur('stocksDebutsCat'+categoryId, 'parseInt', 'stockDebutCat'+categoryId);
            };
        })(categoriesId[i]));

        perteProds[j].addEventListener('keyup', ((categoryId) => {
            return () => {
              updateValuesWMultiplicateur('pertesCat'+categoryId, 'parseInt', 'perteCat'+categoryId);
            };
        })(categoriesId[i]));

        // Mettre à jour les stocks finaux
        stockFinJournees[j].addEventListener('change', ((categoryId) => {
            return () => {
              updateValuesWMultiplicateur('stocksFinsCat'+categoryId, 'parseInt', 'stockFinCat'+categoryId);
            };
        })(categoriesId[i]));
    }
  }

  // Calcul des stocks finaux à chaque changement :
  // Stock début de journée +réalisation - quantité - perteProd
  function updateStockFin(idQte)
  {
    if (typeof idQte === 'string')
    {
      if(idQte.includes(('qteProduit')))
      {
        var idProduit = idQte.replace('qteProduit', '');
      }
    }
    
    else
    {
      if( this.id.includes('qteProduit'))
      {
        var idProduit = this.id.replace('qteProduit', '');
      }
      else if(this.id.includes('realisationProduit'))
      {
        var idProduit = this.id.replace('realisationProduit', '');
      }
      else if(this.id.includes('stocksDebutProduit'))
      {
        var idProduit = this.id.replace('stocksDebutProduit', '');
      }
      else if(this.id.includes('perteProduit'))
      {
        var idProduit = this.id.replace('perteProduit', '');
      }
    }
    

    var stockDebut = document.getElementById('stocksDebutProduit'+idProduit).value=='' ? 0 : document.getElementById('stocksDebutProduit'+idProduit).value;
    var realisation = document.getElementById('realisationProduit'+idProduit).value=='' ? 0 : document.getElementById('realisationProduit'+idProduit).value;
    var quantite = document.getElementById('qteProduit'+idProduit).value=='' ? 0 : document.getElementById('qteProduit'+idProduit).value;
    var perteProd = document.getElementById('perteProduit'+idProduit).value=='' ? 0 : document.getElementById('perteProduit'+idProduit).value;

    var stockFinal = parseInt(stockDebut) + parseInt(realisation) - parseInt(quantite) - parseInt(perteProd);
    if (isNaN(stockFinal)) {
      stockFinal = 0;
    }

    if (stockFinal<0)
    {
      stockFinal = 'ERREUR';
    }
    if(stockFinal != 'ERREUR' && stockFinal != 0)
    {
      stockFinal = Number((stockFinal).toFixed(2));
    }
    document.getElementById('stockFinProduit'+idProduit).value = stockFinal;
    document.getElementById('affichageStockFinProduit'+idProduit).textContent = stockFinal;
    
    
  }

  // Ajout d'évènements à chaque fois qu'on modifie ces valeurs
  var qtes = document.getElementsByClassName('quantitesInput');
  var realisations = document.getElementsByClassName('realisations');
  var stocksDebut = document.getElementsByClassName('stocksDebuts');
  var pertesProd = document.getElementsByClassName('pertes');

  for (var i = 0; i < qtes.length; i++) {
    qtes[i].addEventListener('keyup', updateStockFin);
    realisations[i].addEventListener('keyup', updateStockFin);
    stocksDebut[i].addEventListener('keyup', updateStockFin);
    pertesProd[i].addEventListener('keyup', updateStockFin);

    updateStockFin(qtes[i].id);

    qtes[i].addEventListener('keyup', updateAllStockCat);
    realisations[i].addEventListener('keyup', updateAllStockCat);
    stocksDebut[i].addEventListener('keyup', updateAllStockCat);
    pertesProd[i].addEventListener('keyup', updateAllStockCat);

    updateAllStockCat(qtes[i].id);

    // Produit laitiers
    qtes[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFinsProduitLaitier', 'produitsLaitiersStockFin'));

    realisations[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFinsProduitLaitier', 'produitsLaitiersStockFin'));

    stocksDebut[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFinsProduitLaitier', 'produitsLaitiersStockFin'));

    pertesProd[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFinsProduitLaitier', 'produitsLaitiersStockFin'));

    // Totaux
    qtes[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFins', 'totalToutFaitStockFin'));

    realisations[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFins', 'totalToutFaitStockFin'));

    stocksDebut[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFins', 'totalToutFaitStockFin'));

    pertesProd[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFins', 'totalToutFaitStockFin'));

    updateAllStockProduit('stocksFinsProduitLaitier', 'produitsLaitiersStockFin');
    updateAllStockProduit('stocksFins', 'totalToutFaitStockFin');
  
  };


  // A chaque fois qu'on modifie une valeur, on vérifie qu'il n'y a pas une erreur. Si c'est le cas, on désactive le bouton
  var inputs = document.getElementsByTagName('input');
  for (var i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('keyup', function()
    {
      // On vérifie les valeurs des prix unitaires 
      var prixUnitaires = document.getElementsByClassName('prixUnitaires');
      var disabled = false;
      for (var i = 0; i < prixUnitaires.length; i++) {
        if(prixUnitaires[i].innerHTML == 'ERREUR' || prixUnitaires[i].innerHTML == 'DIV/0')
        {
          document.getElementById('submit').disabled = true;
          return;
        }
      }

      // On vérifie la valeur des stocks finaux
      var stocksFin = document.getElementsByClassName('stocksFins');
      for (var i = 0; i < stocksFin.length; i++) {
        if(stocksFin[i].value == 'ERREUR')
        {
          document.getElementById('submit').disabled = true;
          return;
        }
      }

      if (disabled == false) {
        document.getElementById('submit').disabled = false;
      }
    });
  };
  applyColorCoding();

</script>
<?php } ?>
</body>

</html>