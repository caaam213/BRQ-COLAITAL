<?php require_once 'header.php'; ?>
<title>Ajouter un produit</title>
</head>

<body>

<div class="container d-flex align-items-center justify-content-center vh-100">

<?php

    if (($unitesMesure == null || empty($unitesMesure)) && (($categoriesProduit == null || empty($categoriesProduit))))
    {
        ?>
        <div class="row">
            <div class="alert alert-info mt-2 text-center w-75 mx-auto col-12" role="alert">
            Aucune unité de mesure et catégories ne sont disponibles actuellement. Veuillez en ajouter au moins une de chaque avant d'ajouter un produit.
            </div>

            <div class="row mt-2 col-8 col-lg-4 mx-auto">
            <a href="<?= self::$config['base_url'] ?>index.php/unitesMesure/addUniteMesure" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto">
            Ajouter une unité de mesure
            </a>
            </div>
        </div>
    <?php 
    }
    else if ($unitesMesure == null || empty($unitesMesure))
    {
        ?>
        <div class="row">
            <div class="alert alert-info mt-2 text-center w-75 mx-auto col-12" role="alert">
            Aucune unité de mesure n'est disponible actuellement. Veuillez en ajouter au moins une avant d'ajouter des catégories de produits.
            </div>

            <div class="row mt-2 col-8 col-lg-4 mx-auto">
            <a href="<?= self::$config['base_url'] ?>index.php/unitesMesure/addUniteMesure" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto">
            Ajouter une unité de mesure
            </a>
            </div>
        </div>
    <?php
    }
    else if ($categoriesProduit == null || empty($categoriesProduit))
    {
        ?>
        <div class="row">
            <div class="alert alert-info mt-2 text-center w-75 mx-auto col-12" role="alert">
            Aucune catégorie de produit n'est disponible actuellement. Veuillez en ajouter au moins une avant d'ajouter un produit.
            </div>

            <div class="row mt-2 col-8 col-lg-4 mx-auto">
            <a href="<?= self::$config['base_url'] ?>index.php/categoriesProduit/addCategorie" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto">
            Ajouter une catégorie de produit
            </a>
            </div>
        </div>
    <?php
    }
    else
    {
        ?>
        <form action=<?= self::$config["base_url"]."index.php/produits/add"?> method="post">

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

        <h2 class="mb-4 text-center mt-2">Ajouter un produit</h2>

        <div class="row justify-content-center">
            <div class="form-group col-md-5">
                <label for="codeProduit">Code produit:</label>
                <input type="text" class="form-control" id="codeProduit" name="codeProduit" required>
            </div>
            <div class="form-group col-md-5">
                <label for="nomProduit">Nom produit:</label>
                <input type="text" class="form-control" id="nomProduit" name="nomProduit" required>
            </div>
            <div class="form-group col-md-5">
                <label for="typeProduit">Type produit:</label>
                <select class="form-select" id="typeProduit" name="typeProduit" required>
                    <option value="Produit fini">Produit fini</option>
                    <option value="Matière première">Matière première</option>
                </select>
            </div>
            <div class="form-group col-md-5">
                <label for="objectif">Objectif:</label>
                <input type="number" class="form-control" id="objectif" min="0" name="objectif">
            </div>
            
            <div class="form-group col-md-5">
            <label for="idUnite">Unité de mesure :</label>
                <select class="form-select" id="idUnite" name="idUnite" required>
                    <?php
                        foreach ($unitesMesure as $uniteMesure) {
                            echo '<option value="'.$uniteMesure->getIdUnite().'">'.$uniteMesure->getUniteNom().'</option>';
                        }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-5" id="categoriesForm">
            <label for="idCategorie">Catégorie de produit :</label>
                <select class="form-select" id="idCategorie" name="idCategorie" required>
                    <?php
                        foreach ($categoriesProduit as $categorie) {
                            echo '<option value="'.$categorie->getIdCategorie().'">'.$categorie->getNomCategorie().'</option>';
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
                                            <input type="checkbox" name="composer[]" value="<?= $matierePremiere->getIdProduit() ?>" id="matierePremiere<?= $matierePremiere->getIdProduit() ?>" />
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
                    <i class="fas fa-plus"></i>
                    Ajouter le produit
                    
                    </button>
            </div>
        </form>

    <?php
    }
?>

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

    window.onload = function() {
        var selectElement = document.getElementById("typeProduit");
        selectElement.addEventListener("change", toggleMatierePremiereElements);
        toggleMatierePremiereElements();
    };
</script>
</div>
</body>
</html>