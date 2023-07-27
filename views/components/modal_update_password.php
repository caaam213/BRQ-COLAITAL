<div class="col-md-<?=$nbRow ?> col-sm-12 col-12 mb-3"> 
    <div class="row justify-content-center align-items-center btn-icon">
        <!-- Bouton pour ouvrir la fenêtre modale -->
        <button class="btn btn-secondary btn-lg btn-fixed-size" data-toggle="modal" data-target="#updatePassword">
            <i class="<?= $interface["icon"]?> mt-2"></i>
        </button>
    </div>

    <div class="row text-center">
        <p><?= $interface["name"] ?></p>
    </div>

    <!-- Fenêtre modale pour modifier le mot de passe -->
    <div class="modal fade" id="updatePassword" tabindex="-1" role="dialog" aria-labelledby="updatePasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modification du mot de passe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2>Modifier le mot de passe</h2>

                <form class="row" method="post" onsubmit="return validateForm()" action=<?=self::$config['base_url']."index.php/utilisateurs/updatePassword" ?>>
                <div class="form-group">
                    <label for="newPassword">Nouveau mot de passe:</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirmer le mot de passe:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                <p id="errorMessage" class="text-danger text-center mt-2"></p>
                <button type="submit" class="btn btn-primary mx-auto w-50" id="submitBtn" disabled>Enregistrer les modifications</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
            </div>
        </div>
    </div>
</div>


