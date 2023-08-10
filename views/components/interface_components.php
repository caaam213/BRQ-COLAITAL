<?php 

    if (sizeof($interfaceArray)%2 == 0) {
        $nbRow = 2;
    } else {
        $nbRow = 3;
    }
?>
    <!-- Affichage des interfaces -->
    <div class="container row justify-content-center">
    <?php 
    foreach ($interfaceArray as $interface) {
        $display = true;
        if ($interface["restrict_access"] == true) {
            if ($_SESSION['code_role'] != $interface["roleToAccess"]) {
                $display = false;
            }
        }
        

        if ($display) {
            if (isset($interface["modal_name_file"])) {
                require_once $interface["modal_name_file"];
            }
            else
            {
            ?>
                <div class="col-md-<?=$nbRow ?> col-sm-12 col-12 mb-3"> 
                    <div class="row justify-content-center align-items-center btn-icon">
                        <a href="<?= $interface["url"] ?>" class="btn btn-secondary btn-lg btn-fixed-size">
                            <i class="<?= $interface["icon"]?> mt-2"></i>
                        </a>
                    </div>

                    
                    <div class="row text-center">
                        <p><?= $interface["name"] ?></p>
                    </div>
                </div>
            <?php
            }
        }
    }
    ?>
    </div>
