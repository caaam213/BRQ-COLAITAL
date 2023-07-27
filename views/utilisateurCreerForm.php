<?php require_once 'header.php'; ?>
<title>Ajouter un utilisateur</title>
</head>

<body>

<div class="container d-flex align-items-center justify-content-center vh-100">


    
    <form action=<?= self::$config["base_url"]."index.php/utilisateurs/add"?> method="post">
        <div class="row">
            <a href="<?= self::$config['base_url'] ?>index.php/home" class="btn btn-outline-secondary btn-icon btn-rounded-circle mx-auto w-25">
              <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        <!--Affichage de l'erreur -->
        <h2 class="mb-4 text-center mt-2">Ajouter un utilisateur</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger mt-2 text-center w-75 mx-auto" role="alert">
            ' . $_SESSION['error'] . '
            </div>';
        }
        ?>

        
        <div class="row">

            <!--Input du nom -->
            <div class="form-group col-md-6">
                <label for="nom_util" class="text-center">Nom</label>
                <input type="text" class="form-control" id="nom_util" name="nom_util" required>
            </div>

            <!--Input du prénom -->
            <div class="form-group col-md-6">
                <label for="prenom_util" class="text-center">Prénom</label>
                <input type="text" class="form-control" id="prenom_util" name="prenom_util" required>
            </div>
        </div>

        <div class="row">
            <!--Input du mail -->
            <div class="form-group col-md-6">
                <label for="adresse_mail" class="text-center">Adresse e-mail</label>
                <input type="email" class="form-control" id="adresse_mail" name="adresse_mail" required>
            </div>

            <!--Combobox du rôle -->
            <div class="form-group col-md-6">
                <label for="code_role">Rôle</label>
                <br/>
                <select name="code_role" class="form-control">
                    <?php foreach ($roleUtilisateurs as $roleUtilisateur) { ?>
                        <option value="<?php echo $roleUtilisateur->getCodeRole(); ?>"><?php echo $roleUtilisateur->getNomRole(); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        
        <input type="hidden" name="mdp" value=<?= $mdp?>>

        <!--Affichage du mot de passe -->
        <div class="row mt-2">
            <div class="">
                <p class="text-center">
                    Mot de passe pour cet utilisateur : <span class="fw-bold"><?= $mdp?></span>
                    <br/> <span class="fw-light"> Pour des questions de sécurité, ce mot de passe sera visible uniquement sur cette page.
                    Veuillez le noter et le lui fournir. </span>
                </p>
            </div>
        </div>

       <div class="row"> 
       
        <!--Bouton pour ajouter l'utilisateur -->
        <button type="submit" class="btn btn-outline-success w-50 mx-auto">
        <i class="fas fa-plus"></i>
        Ajouter l'utilisateur
        
        </button>
        
       </div>
    </form>
</div>

</body>
</html>
