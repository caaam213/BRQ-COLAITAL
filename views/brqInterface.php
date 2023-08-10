<?php 
    require_once 'header.php';
?>
  <title>Interface Brq et produits</title>
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

<!-- Bouton de retour -->
<div class="row">
  <a href="<?= self::$config['base_url'] ?>index.php/home" class="btn btn-outline-success btn-icon btn-rounded-circle mx-auto w-25">
    <i class="fas fa-arrow-left"></i>
  </a>
</div>
<h1 class="text-center mt-3">Interface brq</h1>

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

<div class="container">

<div class="d-flex justify-content-center">
    <form action="<?= self::$config['base_url'] ?>index.php/brq/" method="POST" class="row align-items-center">
      <div class="col-md-12">
        <label for="begin_date">Date début :</label>
        <input type="date" id="begin_date" name="begin_date" value="<?= $beginDate ?>" class="form-control" />
      </div>

      <div class="col-md-12">
        <label for="end_date">Date fin :</label>
        <input type="date" id="end_date" name="end_date" value="<?= $endDate ?>" class="form-control" />
      </div>

      <div class="form-group col-md-12">
        <label for="filter_list">Filtrer les brq</label>

        <select name="filter_list" class="form-select">
          <option value="all">Afficher tous les BRQ</option>
          <option value="created">Afficher seulement les BRQ crées</option>
          <option value="not_created">Afficher les BRQ non crées</option>

        </select>
      </div>

      <div class="col-md-8 mt-2 row">
        <button type="submit" class="btn btn-primary btn-block mx-auto">Valider</button>
      </div>
    </form>
  </div>

  <div class="row mt-3 justify-content-between border">
  <?php
  $buttonCounter = 0; // Compteur pour grouper les boutons deux par deux
  $buttonsArray = []; // Tableau pour stocker les boutons générés

  while (strtotime($date) >= strtotime($beginDate))
  {
    if ($filter_list == "all")
    {
      $dateConverted = date("d/m/Y", strtotime($date));
      if (in_array($date, $brqFilled))
      {
        $buttonsArray[] = '<div class="col-md-4 mb-3 d-flex justify-content-center mt-1">
                            <a href="'.self::$config['base_url'].'index.php/brq/modifyBrq?dateBrq='.$date.'" class="btn btn-success">
                              '.$dateConverted.'
                            </a>
                          </div>';
      }
      else
      {
        $buttonsArray[] = '<div class="col-md-4 mb-3 d-flex justify-content-center mt-1">
                            <a href="'.self::$config['base_url'].'index.php/brq/addBrq?dateBrq='.$date.'" class="btn btn-danger">
                              <i class="fas fa-exclamation-triangle"></i>
                              '.$dateConverted.'
                            </a>
                          </div>';
      }

      $buttonCounter++;
      // Ajouter une div avec "clearfix" pour régler les sauts de ligne entre les groupes de boutons
      if ($buttonCounter % 2 === 0) {
        echo '<div class="clearfix d-md-none"></div>';
      }

      $date = date ("Y-m-d", strtotime("-1 day", strtotime($date)));
    }
    
  }

  

  // Afficher les boutons
  echo implode("", $buttonsArray);
  ?>

</div>



</div>




<script>
    const beginDateInput = document.getElementById('begin_date');
    const endDateInput = document.getElementById('end_date');

    // Mettre à jour les valeurs min et max initiales
    endDateInput.min = beginDateInput.value;
    endDateInput.max = new Date().toISOString().split('T')[0]; // Date d'aujourd'hui

    beginDateInput.addEventListener('change', () => {
        // Mettre à jour la valeur min du calendrier de fin
        endDateInput.min = beginDateInput.value;
        
        // Valider la date du calendrier de fin
        if (endDateInput.value < endDateInput.min) {
            endDateInput.value = endDateInput.min;
        }
    });

    endDateInput.addEventListener('change', () => {
        // Mettre à jour la valeur max du calendrier de début
        beginDateInput.max = endDateInput.value;
        
        // Valider la date du calendrier de début
        if (beginDateInput.value > beginDateInput.max) {
            beginDateInput.value = beginDateInput.max;
        }
    });
</script>




</body>
</html>
