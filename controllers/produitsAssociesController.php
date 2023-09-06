<?php

require_once 'utilsController.php';

class produitsAssociesController 
{
    private static $config;
    private static $configErrors;
    
    /**
     * __construct : Constructeur de la classe produitsController
     *
     * @param  mixed $config
     * @param  mixed $configErrors
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
        $this->displayProduitsAssociesInterface();
    }

    /**
     * displayProduitsInterface : Affiche l'interface Produits
     *
     * @return void
     */
    public function displayProduitsAssociesInterface()
    {
        verifyAccesRoleCode(["ADMIN","CONTROLEUR"]);
        require_once 'views/produitsAssociesInterface.php';
        unsetSessionVariables();
    }

    
}