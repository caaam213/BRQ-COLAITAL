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



  <?php 
    if($isFirstBrq)
    {
      echo '<div class="alert alert-danger mt-2 text-center w-75 mx-auto" role="alert">
      Aucun BRQ n\'existe encore
      </div>';

      // Créer le bouton pour créer un nouveau brq
      $beginDate = self::$config['date_begin_brq'];
      echo '<div class="row mt-3 justify-content-center">
      <a href="'.self::$config['base_url'].'index.php/brq/addOrModifyBrq?dateBrq='.$beginDate.'" class="btn btn-primary btn-icon btn-rounded-circle mx-auto w-25">
        <i class="fas fa-plus"></i>
        Créer le premier BRQ
      </a>';
    }
    else
    {
  ?>
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
          <option value="fill">Afficher seulement les BRQ remplis</option>
          <option value="incomplete">Afficher seulement les BRQ incomplet</option>
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
        $buttonsArray[] = include 'views/components/brq_button_brq_filled.php';
      }
      else if(in_array($date, $brqNotFilled))
      {
        $buttonsArray[] = require 'views/components/brq_button_brq_not_filled.php';
      }
      else
      {
        $url = self::$config['base_url'].$brqNotComplete[$date]."?dateBrq=".$date;
        $buttonsArray[] = require 'views/components/brq_button_not_complete.php';
      }

      $buttonCounter++;
      
      if ($buttonCounter % 2 === 0) {
        echo '<div class="clearfix d-md-none"></div>';
      }

      $date = date ("Y-m-d", strtotime("-1 day", strtotime($date)));
    }
    else if($filter_list == "fill")
    {
      if (in_array($date, $brqFilled))
      {
        $buttonsArray[] = require 'views/components/brq_button_brq_filled.php';
        $buttonCounter++;
        
        if ($buttonCounter % 2 === 0) {
          echo '<div class="clearfix d-md-none"></div>';
        }
      }
    }
    else if($filter_list == "incomplete")
    {
      if (in_array($date, $brqNotComplete))
      {
        $buttonsArray[] = require 'views/components/brq_button_not_complete.php';
        $buttonCounter++;
        
        if ($buttonCounter % 2 === 0) {
          echo '<div class="clearfix d-md-none"></div>';
        }
      }
    }
    else if($filter_list == "not_created")
    {
      if (in_array($date, $brqNotFilled))
      {
        $url = $brqNotComplete[$date]."?dateBrq=".$date;
        $buttonsArray[] = require 'views/components/brq_button_brq_not_filled.php';
        $buttonCounter++;
        
        if ($buttonCounter % 2 === 0) {
          echo '<div class="clearfix d-md-none"></div>';
        }
      }
    }

    
  }

  ?>

</div>
<?php 
  }
?>



</div>




<script>
    const beginDateInput = document.getElementById('begin_date');
    const endDateInput = document.getElementById('end_date');

    // Mettre à jour les valeurs min et max initiales
    if(beginDateInput != null)
    {
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
    }
    
</script>




</body>
</html>
