<?php 
    require_once 'header.php';
?>
  <title>Gérer les unités de mesure</title>

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

<h1 class="text-center mt-2">Gérer les unités de mesure</h1>

<!-- Bouton d'ajout des unités de mesure -->
<div class="row mt-2">
    <a href="<?= self::$config['base_url'] ?>index.php/unitesMesure/addUniteMesure" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
        <i class="fa-solid fa-plus"></i>
    </a>
</div>

<?php



    if (!isset($unitesMesure) || empty($unitesMesure))
    {
        echo '<div class="alert alert-info mt-2 text-center w-75 mx-auto" role="alert">
        Aucune unité de mesure n\'est disponible actuellement.
        </div>';
    }
    else
    {
        ?>
        <table class="table table-striped border mt-2 table-responsive w-75 mx-auto">
        <thead>
            <tr>
                <th scope="col" class="text-center">Unités de mesure</th>
            </tr>
        </thead>

        <tbody>
            <?php
                foreach ($unitesMesure as $uniteMesure)
                {?>
                    <tr>
                        <td> <a class="toggle-collapse link-secondary link-underline-opacity-0 text-center" data-bs-toggle="collapse" href="collapseUniteMesure<?=$uniteMesure->getCodeUnite() ?>" role="button" aria-expanded="false" aria-controls="collapseUniteMesure<?=$uniteMesure->getCodeUnite()?>">
                                <?=$uniteMesure->getUniteNom()?>
                            </a>

                            <div class="collapse" id="collapseUniteMesure<?=$uniteMesure->getCodeUnite()?>">
                                <div class="card card-body">
                                    Code de l'unité de mesure : <?=$uniteMesure->getCodeUnite()?><br>
                                    Désignation de l'unité de mesure : <?=$uniteMesure->getUniteNom()?><br>
                                    Multiplicateur : <?= $uniteMesure->getMultiplicateur()?>
                                    
                                    
                                    <div class="row mt-2 justify-content-center">
                                        <a class="btn btn-primary col-4" href=<?=self::$config["base_url"]."index.php/unitesMesure/modifyUniteMesure?idUnite=".$uniteMesure->getIdUnite() ?>>
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
        </table>

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