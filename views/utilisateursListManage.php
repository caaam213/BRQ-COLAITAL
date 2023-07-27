<?php 
    require_once 'header.php';
?>
  <title>Gérer les utilisateurs</title>

</head>

<body>

<h1 class="text-center mt-3">Gérer les utilisateurs</h1>

<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Adresse mail</th>
            <th scope="col">Rôle</th>
            <th scope="col">Activer / désactiver l'accès</th>
            <th scope="col">Réinitialiser le mot de passe</th>
            <th scope="col">Supprimer l'utilisateur</th>
        </tr>
    </thead>

    <!-- Affichage des messages d'erreur et de succès -->
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger mt-2 text-center w-75 mx-auto" role="alert">
        '.$_SESSION['error'].'
        </div>';
    }
    ?>

<?php
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success mt-2 text-center w-75 mx-auto" role="alert">
        '.$_SESSION['success'].'
        </div>';
    }
    ?>

    <tbody>
        <?php
            foreach ($utilisateursList as $utilisateur)
            {?>
                <tr>
                    <td><?= $utilisateur->getNom() ?></td>
                    <td><?= $utilisateur->getPrenom() ?></td>
                    <td><?= $utilisateur->getAdresseMail() ?></td>
                    <td>
                        <?php
                            foreach ($roleUtilisateurs as $roleUtilisateur)
                            {
                                if ($roleUtilisateur->getCodeRole() == $utilisateur->getCodeRole())
                                {
                                    echo $roleUtilisateur->getNomRole();
                                }
                            }
                        
                        ?>
                    </td>
                    <!-- Modification de l'accès pour un utilisateur -->
                    <td>
                        <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input access-checkbox" name="aAcces" id="aAcces<?= $utilisateur->getId() ?>" data-href="<?= self::$config["base_url"]."index.php/utilisateurs/updateAcces?idUtilisateur=".$utilisateur->getId()."&acces=".$utilisateur->getAAcces() ?>" <?php if ($utilisateur->getAAcces() == 1) { echo 'checked'; } ?>>
                        <label class="form-check-label" for="aAcces<?= $utilisateur->getId() ?>"><?php if ($utilisateur->getAAcces() == 1) { echo 'Désactiver l\'accès'; } else { echo 'Activer l\'accès'; } ?></label>
                        </div>
                    </td>

                    <!-- Vos boutons pour ouvrir les fenêtres modales -->
                    <td>
                    <button type="button" class="btn btn-primary btn-fixed-size open-modal-btn-update" data-modal-id="<?= $utilisateur->getId() ?>">
                        Réinitialiser le mot de passe
                    </button>
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

                    <!-- Vos boutons pour ouvrir les fenêtres modales -->
                    <td>
                    <button type="button" class="btn btn-danger btn-fixed-size open-modal-btn-remove" data-modal-id="<?= $utilisateur->getId() ?>">
                        Supprimer l'utilisateur
                    </button>
                    </td>

                    <!-- Fenêtre modale pour la réinitialisation du mot de passe -->
                    <div class="modal fade" id="modalRemoveUser<?= $utilisateur->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="modalRemoveUserLabel<?= $utilisateur->getId() ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalResetPasswordLabel<?= $utilisateur->getId() ?>">Suppression d'un utilisateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            </div>
                            <div class="modal-body">
                                <!-- Contenu de la fenêtre modale -->
                                <p>
                                    Voulez-vous vraiment supprimer l'utilisateur : <span class="fw-bold"><?=$utilisateur->getNom().' '.$utilisateur->getPrenom() ?></span>?
                                    Cette décision est irrévocable.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                <a href="<?= self::$config["base_url"]."index.php/utilisateurs/removeUtilisateur?idUtilisateur=".$utilisateur->getId() ?>" class="btn btn-primary">Oui</a>
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

<script>

    // Initialiser les fenêtres modales lors du chargement de la page
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

    // Modales pour supprimer un utilisateur
    $('.open-modal-btn-remove').click(function () {
      const modalId = $(this).data('modal-id');
      $(`#modalRemoveUser${modalId}`).modal('hide');
    });

    $('.open-modal-btn-remove').click(function () {
      const modalId = $(this).data('modal-id');
      $(`#modalRemoveUser${modalId}`).modal('show');
    });
  });

    // Récupération de toutes les checkboxes avec la classe "access-checkbox" pour modifier l'accès
    const accessCheckboxes = document.querySelectorAll(".access-checkbox");

    // Parcourir chaque checkbox pour ajouter un gestionnaire d'événement
    accessCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            const url = this.dataset.href; // Récupérer le lien du data-href
            const checked = this.checked; // Savoir si la checkbox est cochée ou décochée

            // Redirection vers le lien php lorsque l'on clique sur la checkbox
            window.location.href = `${url}`;
        });
    });
</script>
</body>

</html>