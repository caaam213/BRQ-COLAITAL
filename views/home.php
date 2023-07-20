<?php 
    require_once 'header.php';
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <!-- Assurez-vous d'inclure Bootstrap CSS si vous ne l'avez pas déjà fait -->
  <title>Interface BRQ</title>
  <style>
    /* CSS to set the size of the buttons and icons */
    .btn-icon i {
      font-size: 1.5rem;
    }

    .btn-fixed-size {
      width: 100px;
      height: 65px;
      padding: 2; /* Remove any padding to ensure fixed size */
    }
  </style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="container">
        <h1 class="text-center">Bienvenue <?= $_SESSION["nom_util"]." ".$_SESSION["prenom_util"]?> </h1>

        <div class="mt-5">
            <div class="container row justify-content-center">

                <!-- BRQ -->
                <div class="col-md-3 col-sm-12 col-12 mb-3"> 
                    <div class="row justify-content-center align-items-center btn-icon">
                        <a href="<?= self::$config["base_url"]."index.php/brq" ?>" class="btn btn-secondary btn-lg btn-fixed-size">
                            <i class="bi bi-newspaper"></i>
                        </a>
                    </div>
                    <div class="row text-center">
                        <p>Interface BRQ</p>
                    </div>
                </div>

                <?php if ($_SESSION['code_role'] == "ADMIN") { ?>

                <!-- Utilisateurs -->
                <div class="col-md-3 col-sm-12 col-12 mb-3"> 
                    <div class="row justify-content-center align-items-center btn-icon">
                        <a href="<?= self::$config["base_url"]."index.php/utilisateurs" ?>" class="btn btn-secondary btn-lg btn-fixed-size">
                        <i class="bi bi-person"></i>
                        </a>
                    </div>
                    <div class="row text-center">
                        <p>Interface Utilisateurs</p>
                    </div>
                </div>
                
                <?php } ?>

                <!-- Deconnexion -->
                <div class="col-md-3 col-sm-12 col-12 mb-3">
                    <div class="row justify-content-center align-items-center btn-icon">
                        <a href="<?= self::$config["base_url"]."index.php/login/deconnexion" ?>" class="btn btn-secondary btn-lg btn-fixed-size">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    </div>
                    <div class="row text-center">
                        <p>Déconnexion</p>
                    </div>
                </div>
            </div>
        </div>

    </div> 
</div>

</body>
</html>
