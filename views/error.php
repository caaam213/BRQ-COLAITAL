<?php 
    require_once 'header.php';
?>
  <title>Page d'erreur</title>
</head>

<body>
  
    <div class="container d-flex align-items-center justify-content-center vh-100">
    <div> 
            <iframe src="https://giphy.com/embed/nVTa8D8zJUc2A" width="480" height="270" frameBorder="0" class="giphy-embed" 
                allowFullScreen></iframe>

            <h2 class="mb-4 text-center">Oups !</h2>

            <div class="alert alert-danger mt-2 text-center mx-auto" role="alert">
                <?php echo $_SESSION['errorPage']; ?>
            </div>

            <div class="row mt-2">
                <div class="col-md-12 text-center">
                    <?php 
                    if (isset($_SESSION['code_role']))
                    {?>
                        <a href=<?= self::$config["base_url"].'index.php/home'?> class="btn btn-warning">Retour à l'accueil</a>
                    <?php
                    }
                    else
                    {?>
                        <a href=<?= self::$config["base_url"]?> class="btn btn-warning">Retour à la page de Connexion</a>
                    <?php
                    }
                    ?>
                </div>
            </div>
    </div>
    
    </div>
</body>
</html>