<?php require_once 'header.php'; ?>
<title>Ajouter une unité de mesure</title>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center vh-100">

        <form action=<?= self::$config["base_url"]."index.php/unitesMesure/add"?> method="post">
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
            
            <h2 class="mb-4 text-center mt-2">Ajouter une unité de mesure</h2>

            <div class="row justify-content-between">
                <div class="form-group col-md-4">
                    <label for="codeUnite">Code unité:</label>
                    <input type="text" class="form-control" id="codeUnite" name="codeUnite" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="uniteNom">Nom unité:</label>
                    <input type="text" class="form-control" id="uniteNom" name="uniteNom" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="multiplicateur">Multiplicateur:</label>
                    <input type="number" step="0.01" min="0.05" class="form-control" id="multiplicateur" name="multiplicateur" required>
                </div>

                <!--Bouton pour ajouter l'unité de mesure -->
                <div class="row mt-2 col-md-8 col-4 mx-auto">              
                    <button type="submit" class="btn btn-success mx-auto">
                        <i class="fas fa-plus"></i>
                        Ajouter l'unité de mesure
                    </button>
                </div>
                
            </div>

        </form>
    </div>
</body>
</html>