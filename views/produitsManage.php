<?php 
    require_once 'header.php';
?>
  <title>Gérer les produits</title>

</head>
<style> 
    td a {
    display:block;
    width:100%;
}

body {
        overflow-x: hidden;
    }

</style>

<body>




<!-- Bouton de retour -->
<div class="row mt-3">
    <a href="<?= self::$config['base_url'] ?>index.php/produitsAssocies" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
</div>


<!--Affichage des erreurs -->
<?php
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger mt-2 text-center w-75 mx-auto" role="alert">
        '.$_SESSION['error'].'
        </div>';
    }
?>

<!--Affichage des succès -->
<?php 
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success mt-2 text-center w-75 mx-auto" role="alert">
        '.$_SESSION['success'].'
        </div>';
    }
?>

<h1 class="text-center mt-2">Gérer les produits</h1>

<!-- Bouton d'ajout des produits -->
<div class="row">
    <a href="<?= self::$config['base_url'] ?>index.php/produits/addProduit" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
        <i class="fa-solid fa-plus"></i>
    </a>
</div>



<?php



    if (!isset($produits) || empty($produits))
    {
        echo '<div class="alert alert-info mt-2 text-center w-75 mx-auto" role="alert">
        Aucun produit n\'est disponible actuellement.
        </div>';
    }
    else
    {
        ?>
        <table class="table table-striped border mt-2 table-responsive w-75 mx-auto">
        <thead>
        <tr>
                <th scope="col" class="text-center">Produits</th>
            </tr>
        </thead>

        <tbody>
        <?php
            foreach ($produits as $produit)
            {?>
            <tr>
                <td> 
                    <a class="toggle-collapse link-secondary link-underline-opacity-0 text-center" 
                    data-bs-toggle="collapse" href="collapseProduit<?=$produit->getIdProduit() ?>" 
                    role="button" aria-expanded="false" aria-controls="collapseProduit<?=$produit->getIdProduit()?>">
                                    <?=$produit->getNomProduit()?>
                    </a>



                        <div class="collapse" id="collapseProduit<?=$produit->getIdProduit()?>">
                            <div class="card card-body">
                                Code du produit : <?=$produit->getCodeProduit()?><br>
                                Nom du produit : <?=$produit->getNomProduit()?><br>
                                Type du produit : <?=$produit->getTypeProduit()?><br>
                                Unité de mesure : <?=$unitesMesureList[$produit->getIdUnite()]?><br>

                                <?php 
                                if($produit->getTypeProduit() == "Produit fini")
                                {
                                    ?>
                                Catégorie : <?=$categoriesProduitList[$produit->getIdCategorie()]?><br>
                                <?php 
                                }?>
                                Objectif du produit : <?=$produit->getObjectif()==0 ? "Non défini" : $produit->getObjectif()?><br>
                                Date de création : <?=date("d/m/Y", strtotime($produit->getDateCreation()))?>
                                <br>
                                
                                
                                <?php 
                                    $textCompo = "";
                                    if (isset($produitsCompositions[$produit->getIdProduit()]))
                                    {
                                        echo "Produits composants : ";
                                        foreach ($produitsCompositions[$produit->getIdProduit()] as $produitComposant)
                                        {
                                            $textCompo .= $produitComposant->getNomProduit().", ";
                                        }
                                        echo substr($textCompo, 0, -2);
                                        echo "<br>";
                                    }
                                    
                                ?>
                                
                                Utilisateur qui a crée ce produit : <?=$utilisateursList[$produit->getIdUtilisateur()]?><br>
                                <?php 
                                    if(isset($lastUserModif[$produit->getIdProduit()]))
                                    {
                                        echo "Dernier utilisateur qui a modifié ce produit : ".$lastUserModif[$produit->getIdProduit()]."<br>";
                                    }
                                    else
                                    {
                                        echo "Dernier utilisateur qui a modifié ce produit : ".$utilisateursList[$produit->getIdUtilisateur()]."<br>";
                                    }

                                ?>
                                <div class="row mt-2 justify-content-center">
                                    <a class="btn btn-primary col-4" href=<?=self::$config["base_url"]."index.php/produits/modifyProduit?idProduit=".$produit->getIdProduit() ?>>
                                        Modifier
                                    </a>
                                </div>
                            </div>
                        </div>
                </td>
            </tr>
                <?php
                }
            ?>
        </tbody>

    <?php
    }

    ?>

<script>
  // Attendre que le document soit prêt
  $(document).ready(function () {
    // Sélectionner tous les éléments avec la classe "toggle-collapse"
    var collapseToggles = document.querySelectorAll(".toggle-collapse");

    // Ajouter un gestionnaire d'événement à chaque bouton
    collapseToggles.forEach(function(button) {
      button.addEventListener("click", function(e) {
        e.preventDefault();
        
        // Obtenir l'ID du collapse cible depuis l'attribut href du bouton
        var targetCollapseId = this.getAttribute("href");

        // Sélectionner le collapse correspondant à l'aide de l'ID obtenu
        var targetCollapse = document.getElementById(targetCollapseId);


        // Vérifier si le collapse est actuellement affiché ou caché
        if (targetCollapse.classList.contains("show")) {
          // Le collapse est actuellement affiché, nous allons le masquer
          targetCollapse.classList.remove("show");
        } else {
          // Le collapse est actuellement caché, nous allons l'afficher
          targetCollapse.classList.add("show");
        }
      });
    });
  });
</script>


</body>
</html>