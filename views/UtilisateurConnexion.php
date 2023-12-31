<?php
require_once 'header.php';
?>
<title>Connexion</title>
</head>

<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center vh-100">
  <form action="index.php/login/verify" method="post" class="col-lg-6 col-md-8 col-sm-10">
    <img src="./resources/images/colaital_logo.png" alt="logo" class="logo img-thumbnail mx-auto d-block">

    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger mt-2 text-center w-75 mx-auto" role="alert">
        '.$_SESSION['error'].'
        </div>';
    }
    ?>

    <!--Adresse mail -->
    <div class="form-group row w-75 mx-auto">
      <label for="email" class="text-center">Adresse email</label>
      <input type="email" class="form-control" name="email" id="email" required>
    </div>

    <!--Mot de passe -->
    <div class="form-group row mt-2 w-75 mx-auto">
      <label for="mdp" class="text-center">Mot de passe</label>
      <input type="password" class="form-control" name="mdp" id="mdp" required>
    </div>

    <div class="row">
        <button type="submit" class="btn btn-outline-secondary mt-3 mx-auto col-lg-6 col-md-8 col-10">Connexion</button>
    </div>
  </form>
</div>

</body>