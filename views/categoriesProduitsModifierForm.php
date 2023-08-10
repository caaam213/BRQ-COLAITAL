<?php require_once 'header.php'; ?>
<title>Ajouter une catégorie</title>
</head>

<body>


<div class="container d-flex align-items-center justify-content-center vh-100">
<?php 
    if ($unitesMesure == null || empty($unitesMesure))
    {?>
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
    else
    {
        ?>
    <form action=<?= self::$config["base_url"]."index.php/categoriesProduits/modify"?> method="post">
        <!--Bouton de retour -->
        <div class="row">
            <a href="<?= self::$config['base_url'] ?>index.php/categoriesProduits" class="btn btn-outline-secondary btn-icon btn-rounded-circle mx-auto w-25">
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
        
        <h2 class="mb-4 text-center mt-2">Modifier la catégorie <?= $categorieProduit->getCodeCategorie() ?></h2>
        <input type="hidden" name="idCategorie" value=<?= $categorieProduit->getIdCategorie() ?>>
        <div class="row justify-content-md-between justify-content-center">
            <div class="form-group col-10 col-md-5">
                <label for="codeCategorie">Code catégorie:</label>
                <input type="text" class="form-control" id="codeCategorie" value='<?=$categorieProduit->getCodeCategorie() ?>' name="codeCategorie" required>
            </div>
            <div class="form-group col-10 col-md-5">
                <label for="nomCategorie">Nom catégorie:</label>
                <input type="text" class="form-control" id="nomCategorie" value='<?=$categorieProduit->getNomCategorie() ?>' name="nomCategorie" required>
            </div>
            

            <div class="form-group col-10 col-md-5">
                <label for="idUnite">Unité de mesure:</label>
                
                <select class="form-select" name="idUnite" aria-label="Default select example">
                <?php
                    foreach ($unitesMesure as $unite) {
                        echo '<option value="' . $unite->getIdUnite() . '"';
                        echo $unite->getIdUnite() == $categorieProduit->getIdUnite() ? "selected" : "";
                        echo '>' . $unite->getUniteNom() . '</option>';
                    }
                    
                    ?>
                </select>
            </div>

            <div class="row mt-2 col-md-8 col-10 mx-auto"> 
       
                    <button type="submit" class="btn btn-success mx-auto">
                    <i class="fas fa-pen"></i>
                    Modifier la catégorie
                    
                    </button>
            </div>
        </div>

    </form>
</div>
<?php
    }
?>
</body>
</html>