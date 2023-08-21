<?php
require_once 'config/config_errors.php';
require_once 'utilsController.php';


class ErrorController
{
    private static $config;
    private static $configErrors;    
    /**
     * __construct : Constructeur de la classe ErrorController
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
        $this->displayError();
    }
    
    
    /**
     * displayError : Afficher la page d'erreur
     *
     * @return void
     */
    public function displayError()
    {
        if (isset($_SESSION['errorPage']))
        {
            require_once 'views/error.php'; // Redirection vers la page d'erreur avec le message d'erreur
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1006']; // Erreur : Vous avez été déconnecté de l'application
            header('Location: '.self::$config["base_url"]); 
            exit();
        }
        
        
    }
}
