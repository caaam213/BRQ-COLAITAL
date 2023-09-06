<?php
require_once 'models/UtilisateurModel.php';
require_once 'config/config_errors.php';
require_once 'utilsController.php';

class HomeController
{
    private static $config;
    private static $configErrors;    
    /**
     * __construct : Constructeur de la classe HomeController
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
        $this->displayHome();
    }
    
    
    /**
     * displayHome : Afficher la page d'accueil une fois l'utilisateur connecté
     *
     * @return void
     */
    public function displayHome()
    {
        unsetSessionVariables();
        verifyAccesRoleCode(["ADMIN","CONTROLEUR"]);
        require_once 'views/home.php';
    }
}