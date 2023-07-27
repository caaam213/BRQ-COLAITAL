<?php 
    require_once 'header.php';
?>
  <title>Interface Utilisateur</title>
  <style>

    .btn-icon i {
      font-size: 1.5rem;
    }

    .btn-fixed-size {
      width: 100px;
      height: 65px;
      padding: 2; 
    }
  </style>
</head>
<body>



<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="container">
        <!-- Bouton de retour -->
        <div class="row">
            <a href="<?= self::$config['base_url'] ?>index.php/home" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
              <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        <h1 class="text-center mt-3">Interface utilisateur</h1>

        <!-- Affichage des messages d'erreur et de succès -->
        <div class="mt-5"></div>
        <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success mt-2 text-center w-75 mx-auto" role="alert">
                '.$_SESSION['success'].'
                </div>';
            }
            ?>
        <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger mt-2 text-center w-75 mx-auto" role="alert">
                '.$_SESSION['error'].'
                </div>';
            }
            ?>
        <?php
            $interfaceArray = require_once 'components/interface_component_utilisateurs.php';
            require_once 'components/interface_components.php';
        ?>
    </div> 
</div>
<script>
  // Initialiser la fenêtre modale au chargement de la page
  $(document).ready(function () {
        
        $('#updatePassword').modal('hide'); 

        
        $('.btn-fixed-size').click(function () {
            $('#updatePassword').modal('show');
        });
    });
  
    function updateErrorMessage() {

      // Récupération des champs de mot de passe
      const newPassword = document.getElementById("newPassword").value;
      const confirmPassword = document.getElementById("confirmPassword").value;
      const errorMessage = document.getElementById("errorMessage");
      const submitBtn = document.getElementById("submitBtn");

      // Vérifier si les deux champs de mot de passe sont identiques
      if (newPassword !== confirmPassword) {
        errorMessage.textContent = "Les champs de mot de passe ne correspondent pas.";
        submitBtn.disabled = true;
      } else {
        errorMessage.textContent = "";
        submitBtn.disabled = false;
      }

      // Vérifier si le mot de passe est suffisamment long (au moins 8 caractères)
      if (newPassword.length < 8) {
        errorMessage.textContent = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
        submitBtn.disabled = true;
      }
  }

  // Vérifier les mots de passe à chaque fois que l'utilisateur tape quelque chose
  document.getElementById("newPassword").addEventListener("input", updateErrorMessage);
  document.getElementById("confirmPassword").addEventListener("input", updateErrorMessage);
  
  // Fonction pour réinitialiser les champs du formulaire
  function resetFormFields() {
    document.getElementById("newPassword").value = "";
    document.getElementById("confirmPassword").value = "";
    document.getElementById("errorMessage").textContent = "";
    document.getElementById("submitBtn").disabled = true;
  }

  // Réinitialiser les champs du formulaire à chaque fois que la fenêtre modale est fermée
  const updatePasswordModal = document.getElementById("updatePassword");
  updatePasswordModal.addEventListener("hide.bs.modal", resetFormFields);

</script>

</body>
</html>