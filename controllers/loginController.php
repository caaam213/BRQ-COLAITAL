<?php

require_once 'models/UtilisateurModel.php';
require_once 'config/config_errors.php';
require_once 'utilsController.php';



class LoginController
{  
    private static $config;
    private static $configErrors;    
    /**
     * __construct : Constructeur de la classe LoginController
     *
     * @return void
     */
    public function __construct()
    {
        self::$config = require 'config/config.php';
        self::$configErrors = require 'config/config_errors.php';
    }  
    
    /**
     * default : Fonction par défaut qui va appeler la fonction displayLoginForm
     *
     * @return void
     */
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
        require_once 'views/utilisateurConnexion.php';
        unsetSessionVariables();
    }
    
    /**
     * Vérifie si l'utilisateur existe et si le mot de passe est correct
     * @return void
     */
    public static function verify()
    {
        if (empty($_POST['email']) || empty($_POST['mdp'])) {
            header('Location: '.self::$config["base_url"]);
        }

        $email = htmlspecialchars($_POST['email']);
        $mdp = htmlspecialchars($_POST['mdp']);
        
        $utilisateur = UtilisateurModel::verifyIfUserExists($email);
        session_unset(); // On supprime les variables de session erreur et success

        // Si le mdp est correct, on crée les variables de session
        if ($utilisateur) {
            if (password_verify($mdp, $utilisateur->getMdp())) {
                if ($utilisateur->getAAcces() == 1) {
                    
                    $_SESSION['email'] = $utilisateur->getAdresseMail();
                    $_SESSION['id_util'] = $utilisateur->getId();
                    $_SESSION['nom_util'] = $utilisateur->getNom();
                    $_SESSION['prenom_util'] = $utilisateur->getPrenom();
                    $_SESSION['code_role'] = $utilisateur->getCodeRole();
                    header('Location: '.self::$config["base_url"].'index.php/home');

                } else {
                    // Si l'utilisateur n'a pas accès à l'application
                    $_SESSION['error'] = self::$configErrors[1002]; // Erreur : Vous n'avez pas accès à l'application

                }
                

            } else {
                // Si le mdp est incorrect
                $_SESSION['error'] = self::$configErrors[1001]; // Erreur : Le mot de passe est incorrect
                header('Location: '.self::$config["base_url"]); 
            }
        } else {
            // Si l'utilisateur n'existe pas
            $_SESSION['error'] = self::$configErrors[1000]; // Erreur : L'adresse e-mail n'existe pas
            header('Location: '.self::$config["base_url"]); 
        }
    }
    
    /**
     * Détruit les variables de session et redirige vers la page de connexion
     * @return void
     */
    public function deconnexion()
    {
        session_unset(); // On supprime les variables de session
        session_destroy(); // On détruit la session
        header('Location: '.self::$config["base_url"]); 
    }
}