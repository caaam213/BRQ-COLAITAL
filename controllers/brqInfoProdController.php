<?php

require_once 'utilsController.php';
require_once 'models/BrqModel.php';
require_once 'models/ProduitModel.php';
require_once 'models/CategorieProduitModel.php';
require_once 'models/UniteMesureModel.php';
require_once 'models/InfoBrqProduitModel.php';
require_once 'models/BrqProduitPerteModel.php';


class BrqInfoProdController
{
    private static $config;

    private static $configErrors;
    
    /**
     * __construct : Constructeur de la classe BrqInfoProdController
     *
     * @return void
     */
    public function __construct()
    {
        self::$config = require 'config/config.php';
        self::$configErrors = require 'config/config_errors.php';
    }

    /**
     * default : Fonction appelée par défaut par le routeur
     *
     * @return void
     */
    public function default()
    {
        $this->displayInfosPertesProd();
    }

    /**
     * infosPertesProd : Afficher la page des infos pertes
     *
     * @return void
     */
    public function displayInfosPertesProd()
    {
        unsetSessionVariables();
        verifyAccesRoleCode();
        
        // Vérifier la date du brq en paramètre
        $dateBrq = createValidDate($_GET['dateBrq']);

        if(!isDateValid($dateBrq))
        {
            $erreur_msg = $dateBrq;
            $_SESSION['error'] = $erreur_msg." ".$_GET['dateBrq'];
            header('Location: '.self::$config['base_url'].'index.php/brq');
            exit();
        }

        // Récupérer les champs des produits perte
        $produitsPerte = BrqProduitPerteModel::getAllProduitPerte();

        require_once 'views/brq2PerteProd.php';
        
    }
}