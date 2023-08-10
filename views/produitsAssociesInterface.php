<?php 
    require_once 'header.php';
?>
  <title>Interface Produits & associés</title>
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
        <h1 class="text-center mt-3">Interface Produits & associés</h1>

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
            $interfaceArray = require_once 'components/interface_component_produitsAssocies.php';
            require_once 'components/interface_components.php';
        ?>
    </div> 
</div> 