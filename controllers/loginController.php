<?php
    session_start();
?>
<?php

require_once 'models/UtilisateurModel.php';
require_once 'config/config_errors.php';


class LoginController
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
        $this->displayLoginForm();
    }
    
    /**
     * Affiche le formulaire de connexion
     * @return void
     */
    public function displayLoginForm()
    {
        require_once 'views/UtilisateurConnexion.php';
    }
    
    /**
     * Vérifie si l'utilisateur existe et si le mot de passe est correct
     * @return void
     */
    public static function verify()
    {
        if (empty($_POST['nom_util']) || empty($_POST['mdp'])) {
            header('Location: '.self::$config["base_url"]);
        }

        $nom_util = htmlspecialchars($_POST['nom_util']);
        $mdp = htmlspecialchars($_POST['mdp']);
        
        $utilisateur = UtilisateurModel::verifyIfUserExists($nom_util);
        session_unset();

        // Si le mdp est correct, on crée les variables de session
        if ($utilisateur) {
            if (password_verify($mdp, $utilisateur->getMdp())) {
                if ($utilisateur->getAAcces() == 1) {
                    
                    $_SESSION['id_util'] = $utilisateur->getId();
                    $_SESSION['nom_util'] = $utilisateur->getNom();
                    $_SESSION['prenom_util'] = $utilisateur->getPrenom();
                    $_SESSION['code_role'] = $utilisateur->getCodeRole();
                    header('Location: '.self::$config["base_url"].'index.php/home');

                } else {
                    // Si l'utilisateur n'a pas accès à l'application
                    $_SESSION['error'] = self::$configErrors[1002];

                }
                

            } else {
                // Si le mdp est incorrect
                $_SESSION['error'] = self::$configErrors[1001];
                header('Location: '.self::$config["base_url"]); 
            }
        } else {
            // Si l'utilisateur n'existe pas
            $_SESSION['error'] = self::$configErrors[1000];
            header('Location: '.self::$config["base_url"]); 
        }
    }
    
    /**
     * Détruit les variables de session et redirige vers la page de connexion
     * @return void
     */
    public function deconnexion()
    {
        session_unset();
        session_destroy();
        header('Location: '.self::$config["base_url"]); 
    }
}