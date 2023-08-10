<?php 
    require_once 'header.php';
?>
  <title>Accueil</title>
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
        <h1 class="text-center">Bienvenue <?= $_SESSION["nom_util"]." ".$_SESSION["prenom_util"]?> </h1>
        <div class="mt-5"></div>
        <?php
            $interfaceArray = require_once 'components/interface_component_home.php';
            require_once 'components/interface_components.php';
        ?>
    </div> 
</div>

</body>
</html>
