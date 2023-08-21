<?php 
    require_once 'header.php';
?>
  <title>Gérer les utilisateurs</title>

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
<div class="row mt-1">
    <a href="<?= self::$config['base_url'] ?>index.php/utilisateurs" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
        <i class="fas fa-arrow-left"></i>
    </a>
</div>


<h1 class="text-center mt-3">Gérer les utilisateurs</h1>

<!-- Bouton d'ajout des unités de mesure -->
<div class="row mt-2">
    <a href="<?= self::$config['base_url'] ?>index.php/utilisateurs/addUser" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
        <i class="fas fa-plus"></i>
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


<?php 
    if (!isset($utilisateursList) || empty($utilisateursList))
    {
        echo '<div class="alert alert-info mt-2 text-center w-75 mx-auto" role="alert">
        Aucun utilisateur n\'a été trouvé dans la base de données.
        </div>';
    }
    else
    {
?>

<table class="table table-striped border mt-2 table-responsive w-75 mx-auto">
    <thead>
        <tr>
            <th scope="col" class="text-center">Utilisateurs</th>
        </tr>
    </thead>

    <tbody>
        <?php
            foreach ($utilisateursList as $utilisateur)
            {?>
                <tr>
                    

                    <td>
                        <a class="toggle-collapse link-secondary link-underline-opacity-0 text-center" data-bs-toggle="collapse" href="collapseUtil<?=$utilisateur->getId() ?>" role="button" aria-expanded="false" aria-controls="collapseUtil<?=$utilisateur->getId() ?>">
                            <?= $utilisateur->getNom()." ".$utilisateur->getPrenom() ?>
                        </a>
                    
                    
                        <div class="collapse" id="collapseUtil<?=$utilisateur->getId() ?>">
                            <div class="card card-body mx-auto w-75">
                                <div class="row">
                                    <div class="col-md-12">
                                        <span class="fw-bold">Adresse mail : </span> <?= $utilisateur->getAdresseMail() ?>
                                    </div>
                                    
                                    <div class="col-md-12 mt-2 mt-md-1">
                                    <span class="fw-bold">Code du rôle : </span>
                                    
                                        <?php
                                            foreach ($roleUtilisateurs as $roleUtilisateur)
                                            {
                                                if ($roleUtilisateur->getCodeRole() == $utilisateur->getCodeRole())
                                                {
                                                    echo $roleUtilisateur->getNomRole();
                                                }
                                            }
                                        
                                        ?>
                                    </div>
                                    

                                    
                                    
                                    <div class=" mt-2 mt-md-1">
                                        <span class="fw-bold">
                                            <?php if ($utilisateur->getAAcces() == 1) {
                                                echo ' - L\'utilisateur a accès au site';
                                            } else {
                                                echo ' - L\'utilisateur n\'a pas accès au site';
                                            } ?>
                                        </span>
                                        
                                    </div>
                                    
                                    
                                    <div class="mx-auto justify-content-center col-12 row mt-4 mt-md-3">
                                    
                                        <div class="col-md-4 mx-auto">
                                            <a class="btn btn-secondary link-underline-opacity-1" href="<?= self::$config["base_url"] . "index.php/utilisateurs/updateAcces?idUtilisateur=" . $utilisateur->getId() . "&acces=" . $utilisateur->getAAcces() ?>">
                                                Modifier l'accès
                                            </a>
                                        </div>
                                        <div class="col-md-4 mx-auto">
                                            <a type="button" class="btn btn-warning link-underline-opacity-1 open-modal-btn-update mt-2 mt-md-0" data-modal-id="<?= $utilisateur->getId() ?>">
                                                Réinitialiser le mot de passe
                                        </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    

                    <!-- Fenêtre modale pour la réinitialisation du mot de passe -->
                    <div class="modal fade" id="modalResetPassword<?= $utilisateur->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="modalResetPasswordLabel<?= $utilisateur->getId() ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalResetPasswordLabel<?= $utilisateur->getId() ?>">Réinitialiser le mot de passe</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            </div>
                            <div class="modal-body">
                                <!-- Contenu de la fenêtre modale -->
                                <p>Voulez-vous vraiment réinitialiser le mot de passe pour l'utilisateur <span class="fw-bold"><?=$utilisateur->getNom().' '.$utilisateur->getPrenom() ?></span>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <a href="<?= self::$config["base_url"]."index.php/utilisateurs/resetPassword?idUtilisateur=".$utilisateur->getId() ?>" class="btn btn-primary">Réinitialiser le mot de passe</a>
                            </div>
                            </div>
                        </div>
                    </div>


                    

                
                </tr>
            <?php
            }
        ?>
    </tbody>



    

</table>
<?php
    }
?>

<script>

    // On charge les fenêtres modales au chargement de la page
    $(document).ready(function () {
    
    // Modales pour réinitialiser le mot de passe
    $('.open-modal-btn-update').click(function () {
      const modalId = $(this).data('modal-id');
      $(`#modalResetPassword${modalId}`).modal('hide');
    });

    $('.open-modal-btn-update').click(function () {
      const modalId = $(this).data('modal-id');
      $(`#modalResetPassword${modalId}`).modal('show');
    });
  });

    // Pour la modification d'accès
    const accessCheckboxes = document.querySelectorAll(".access-checkbox");

    
    accessCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            const url = this.dataset.href; 
            const checked = this.checked; 

            // On redirige vers le lien php
            window.location.href = `${url}`;
        });
    });

    
  $(document).ready(function () {
    // Gestion des collapses
    var collapseToggles = document.querySelectorAll(".toggle-collapse");

    
    collapseToggles.forEach(function(button) {
      button.addEventListener("click", function(e) {
        e.preventDefault();
        
        // Obtenir l'ID 
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