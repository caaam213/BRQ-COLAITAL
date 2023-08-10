<?php require_once 'header.php'; ?>
<title>Modifier une unité de mesure</title>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center vh-100">

        <form action=<?= self::$config["base_url"]."index.php/unitesMesure/modify"?> method="post">
            <!--Bouton de retour -->
            <div class="row">
                <a href="<?= self::$config['base_url'] ?>index.php/unitesMesure" class="btn btn-outline-secondary btn-icon btn-rounded-circle mx-auto w-25">
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
            
            <input type="hidden" name="idUnite" value="<?= $uniteMesure->getIdUnite() ?>">
            
            <h2 class="mb-4 text-center mt-2">Modifier l'unité de mesure <?= $uniteMesure->getCodeUnite() ?></h2>

            <div class="row justify-content-between">
            <div class="form-group col-md-4">
                    <label for="codeUnite">Code unité:</label>
                    <input type="text" class="form-control" id="codeUnite" name="codeUnite" value='<?= $uniteMesure->getCodeUnite() ?>' required>
                </div>
                <div class="form-group col-md-4">
                    <label for="uniteNom">Nom unité:</label>
                    <input type="text" class="form-control" value='<?= $uniteMesure->getUniteNom() ?>' id="uniteNom" name="uniteNom" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="multiplicateur">multiplicateur:</label>
                    <input type="text" step="0.01" min="1" class="form-control" value=<?= $uniteMesure->getMultiplicateur() ?> id="multiplicateur" name="multiplicateur" required>
                </div>

                <!--Bouton pour modifier l'unité de mesure -->
                <div class="row mt-2 col-8 col-md-6 col-4 mx-auto"> 
                    <button type="submit" class="btn btn-success mx-auto">
                    <i class="fas fa-pen"></i>
                    Modifier l'unité de mesure
                    </button>
                </div>
                
            </div>

        </form>
    </div>
</body>
</html>