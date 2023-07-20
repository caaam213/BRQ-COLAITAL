<?php
    session_start();
?>

<?php
require_once 'models/UtilisateurModel.php';
require_once 'config/config_errors.php';

class HomeController
{
    private static $config;
    private static $configErrors;
    public function __construct()
    {
        self::$config = require 'config/config.php';
        self::$configErrors = require 'config/config_errors.php';
    } 
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
        if (isset($_SESSION['code_role']))
        {
            require_once 'views/home.php';
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003'];
            header('Location: '.self::$config["base_url"]); 
        }
        
           
    }
}