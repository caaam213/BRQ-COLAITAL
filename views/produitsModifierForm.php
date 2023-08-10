<?php require_once 'header.php'; ?>
<title>Modifier un produit</title>
</head>

<body>

<div class="container d-flex align-items-center justify-content-center vh-100">

    <form action=<?= self::$config["base_url"]."index.php/produits/modify"?> method="post">

    <!--Bouton de retour -->
    <div class="row">
        <a href="<?= self::$config['base_url'] ?>index.php/produits" class="btn btn-outline-secondary btn-icon btn-rounded-circle mx-auto w-25">
        <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <!--Affichage de l'erreur -->
    <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger mt-2 text-center w-75 mx-auto" role="alert">
            ' . $_SESSION['error'] . '
            </div>';
        }
        ?>

    <!--Affichage du succès -->
    <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success mt-2 text-center w-75 mx-auto" role="alert">
            ' . $_SESSION['success'] . '
            </div>';
        }
        ?>

    <h2 class="mb-4 text-center mt-2">Modifier le produit <?= $produit->getCodeProduit()?></h2>

    <input type="hidden" name="idProduit" value="<?= $produit->getIdProduit() ?>">
    <input type="hidden" name="formerCode" value="<?= $produit->getCodeProduit() ?>">
    <input type="hidden" name="idUtilisateur" value="<?= $produit->getIdUtilisateur() ?>">
    <input type="hidden" name="dateCreation" value="<?= $produit->getDateCreation() ?>">

    <div class="row justify-content-center">
        <div class="form-group col-md-5">
            <label for="codeProduit">Code produit:</label>
            <input type="text" class="form-control" id="codeProduit" value='<?= $produit->getCodeProduit() ?>' name="codeProduit" required>
        </div>

        <div class="form-group col-md-5">
            <label for="nomProduit">Nom produit:</label>
            <input type="text" class="form-control" id="nomProduit" value='<?= $produit->getNomProduit() ?>' name="nomProduit" required>
        </div>

        <div class="form-group col-md-5">
            <label for="typeProduit">Type produit:</label>
            <select class="form-select" name="typeProduit" id="typeProduit" aria-label="Default select example">
                <option value="Produit fini" <?php if($produit->getTypeProduit() == "Produit fini") {echo "selected";}?>>Produit fini</option>
                <option value="Matière première" <?php if($produit->getTypeProduit() == "Matière première") {echo "selected";}?>>Matière première</option>
            </select>
        </div>

        <div class="form-group col-md-5">
            <label for="objectif">Objectif:</label>
            <input type="number" class="form-control" id="objectif" min="0" name="objectif" value='<?= $produit->getObjectif()!=0 ? $produit->getObjectif() : null ?>'>
        </div>
            
        <div class="form-group col-md-5">
            <label for="idUnite">Unité de mesure:</label>
                
            <select class="form-select" name="idUnite" aria-label="Default select example">
            <?php
                foreach ($unitesMesure as $unite) {
                    echo '<option value="' . $unite->getIdUnite() . '"';
                    echo $unite->getIdUnite() == $produit->getIdUnite() ? "selected" : "";
                    echo '>' . $unite->getUniteNom() . '</option>';
                }
                    
                ?>
            </select>
        </div>

        <div class="form-group col-md-5" id="categoriesForm">
            <label for="idCategorie">Catégorie de produit:</label>
                
            <select class="form-select" name="idCategorie"  aria-label="Default select example">
            <?php
                foreach ($categoriesProduit as $categorie) {
                    echo '<option value="' . $categorie->getIdCategorie() . '"';
                    echo $categorie->getIdCategorie() == $produit->getIdCategorie() ? "selected" : "";
                    echo '>' . $categorie->getNomCategorie() . '</option>';
                }
                    
                ?>
            </select>
        </div>
            
            <?php 
                if ($matieresPremiere != null && !empty($matieresPremiere)) {
                    ?>
                        <div class="row col-md-10 col-12 mx-auto">

                            <a class="toggle-collapse btn btn-outline-secondary btn-icon btn-rounded-circle mt-2 mx-auto" 
                                data-bs-toggle="collapse" href="#matieresPremieres" role="button" aria-expanded="false"
                                aria-controls="matieresPremieres" id="matieresPremieresLink">
                                Choix des matières premières
                            </a>
                        
                            <div class="collapse mt-2 mx-auto w-50" id="matieresPremieres">
                                <div class="card card-body">
                                    <?php 
                                    foreach ($matieresPremiere as $matierePremiere) {
                                    ?>
                                        <div style="display: inline-block; margin-right: 10px;">
                                            <input type="checkbox" name="composer[]" value="<?= $matierePremiere->getIdProduit() ?>"id="matierePremiere<?= $matierePremiere->getIdProduit() ?>" <?php if (in_array($matierePremiere->getIdProduit(), $compositionsList)) echo 'checked' ?> />
                                            <label for="matierePremiere<?= $matierePremiere->getIdProduit() ?>"><?= $matierePremiere->getNomProduit() ?></label>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                    <?php
                    }
                    ?>
            
            
            
            
        </div>

        
        <div class="row mt-2 col-md-6 col-10 mx-auto"> 

                <button type="submit" class="btn btn-success mx-auto">
                <i class="fas fa-pen"></i>
                Modifier le produit
                    
                </button>
        </div>
                
    </form>
    </div>


<script>
    function toggleMatierePremiereElements() {
        var selectElement = document.getElementById("typeProduit");
        
        var matieresPremieresLink = document.getElementById("matieresPremieresLink");
        
        var categoriesForm = document.getElementById("categoriesForm");
        
        var matieresPremieres = document.getElementById("matieresPremieres");

        
        if (selectElement.value === "Matière première") {

            if (matieresPremieresLink)
            {
                matieresPremieresLink.style.display = "none";
                //matieresPremieres.style.display = "none";

            }

            categoriesForm.style.display = "none";
            
        } else {

            if(matieresPremieresLink)
            {
                matieresPremieresLink.style.display = "block"; 
                //matieresPremieres.style.display = "block";
            }
            
            categoriesForm.style.display = "block";
            
             
        }
    }

    toggleMatierePremiereElements();
    window.onload = function() {
        var selectElement = document.getElementById("typeProduit");
        selectElement.addEventListener("change", toggleMatierePremiereElements);
    };



    /*document.addEventListener("DOMContentLoaded", function() {
        // Fonction pour cacher ou afficher l'élément en fonction de la valeur sélectionnée
        function toggleCategoriesForm() {
            var typeProduit = document.getElementById("typeProduit").value;
            var categoriesForm = document.getElementById("categoriesForm");
            
            if (typeProduit === "Matière première") {
                categoriesForm.style.display = "none";
            } else {
                categoriesForm.style.display = "block";
            }
        }

        // Appel de la fonction lors du chargement de la page pour cacher/afficher en fonction de la valeur initiale
        toggleCategoriesForm();

        // Ajout d'un événement pour mettre à jour lors du changement de sélection
        document.getElementById("typeProduit").addEventListener("change", toggleCategoriesForm);
    });*/
</script>

</body>
</html>