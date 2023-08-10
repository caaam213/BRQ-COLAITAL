

<?php
require_once 'models/UtilisateurModel.php';
require_once 'config/config_errors.php';
require_once 'utils/generateRandomPassword.php';
require_once 'models/RoleUtilisateurModel.php';
require_once 'utilsController.php';

class UtilisateursController
{
    private static $config;
    private static $configErrors;    
    /**
     * __construct : Constructeur de la classe UtilisateursController
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
        $this->displayUtilisateursInterface();
    }
    
    
    /**
     * displayUtilisateursInterface : Afficher l"interface de gestion des utilisateurs
     *
     * @return void
     */
    public function displayUtilisateursInterface()
    {
        if (isset($_SESSION['code_role']))
        {
            require_once 'views/utilisateurInterface.php';
            unsetSessionVariables();
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page   
            header('Location: '.self::$config["base_url"]); 
        }
    }
    
    /**
     * addUser : Afficher le formulaire de création d'un utilisateur
     *
     * @return void
     */
    public function addUser()
    {
        if (isset($_SESSION['code_role']))
        {
            if ($_SESSION['code_role'] == 'ADMIN')
            {
                $mdp = generateRandomPassword();
                $roleUtilisateurs = RoleUtilisateurModel::getAllRoleUtilisateurs();
                require_once 'views/utilisateurCreerForm.php';
                unsetSessionVariables();
            }
            else
            {
                $_SESSION['errorPage'] = self::$config['1002']; // Erreur : Vous n'avez pas les droits d'accès à cette page
                header('Location: '.self::$config["base_url"].'index.php/error');  
            }
            
        }
        else
        {
            $_SESSION['errorPage'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config["base_url"].'index.php/error');  
        }
    }
    
    /**
     * add : Ajouter un utilisateur dans la base de données
     *
     * @return void
     */
    public function add()
    {
        if (isset($_SESSION['code_role']))
        {
            if ($_SESSION['code_role'] == 'ADMIN')
            {

                if (isset($_POST['nom_util']) && isset($_POST['prenom_util']) && isset($_POST['adresse_mail'])  && isset($_POST['code_role']))
                {
                    // Récupérer la liste des adresses mail des utilisateurs
                    $utilisateursList = UtilisateurModel::getAllUtilisateurs();
                    $emailLists = array();
                    foreach ($utilisateursList as $utilisateur)
                    {
                        array_push($emailLists, $utilisateur->getAdresseMail());
                    }

                    // Récupération des valeurs du formulaire
                    $nom_util = htmlspecialchars($_POST['nom_util']);
                    $prenom_util = htmlspecialchars($_POST['prenom_util']);
                    $adresse_mail = htmlspecialchars($_POST['adresse_mail']);
                    $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

                    $a_acces = 1;
                    $code_role = $_POST['code_role'];

                    // Création de l'objet Utilisateur
                    $utilisateur = new Utilisateur(null, $nom_util, $prenom_util, $adresse_mail, $mdp, $a_acces, $code_role);

                    if (in_array($adresse_mail, $emailLists))
                    {
                        $_SESSION['error'] = self::$configErrors['1005']; // Erreur : L'adresse e-mail existe déjà      
                        header('Location: '.self::$config["base_url"].'index.php/utilisateurs/addUser');
                    }
                    else
                    {
                        // Ajout de l'utilisateur dans la base de données
                        $utilisateurModel = new UtilisateurModel();
                        $utilisateurModel->addUtilisateur($utilisateur);
                        $_SESSION['success'] = "L'utilisateur a bien été ajouté";
                        header('Location: '.self::$config["base_url"].'index.php/utilisateurs');
                    }
                }
                else
                {
                    $_SESSION['error'] = "Erreur lors de l'ajout d'un utilisateur"; // Erreur : Erreur lors de l'ajout à la BD
                    header('Location: '.self::$config["base_url"].'index.php/utilisateurs/addUser');
                }
            }
            else
            {
                $_SESSION['error'] = self::$configErrors['1004']; // Erreur : Vous n'avez pas les droits pour accéder à cette page
                header('Location: '.self::$config["base_url"]);
            }
            
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config["base_url"]); 
        }
    }
    
    /**
     * updateAcces : Mettre à jour l'accès d'un utilisateur
     *
     * @return void
     */
    public function updateAcces()
    {
        $acces = htmlspecialchars($_GET['acces']);
        $idUtilisateur = htmlspecialchars($_GET['idUtilisateur']);

        if (isset($_SESSION['code_role']))
        {
            if ($_SESSION['code_role'] == 'ADMIN')
            {
                $utilisateurModel = new UtilisateurModel();
                $utilisateurModel->updateAcces($acces, $idUtilisateur);
                $_SESSION['success'] = "L'accès a bien été modifié";
                header('Location: '.self::$config["base_url"].'index.php/utilisateurs/allUsers');
            }
            else
            {
                $_SESSION['error'] = self::$configErrors['1004']; // Erreur : Vous n'avez pas les droits pour accéder à cette page
                header('Location: '.self::$config["base_url"]);
            }
            
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config["base_url"]); 
        }
    }

    
    /**
     * allUsers : Afficher la liste des utilisateurs
     *
     * @return void
     */
    public function allUsers()
    {
        if (isset($_SESSION['code_role']))
        {
            if ($_SESSION['code_role'] == 'ADMIN')
            {
                $utilisateursList = UtilisateurModel::getAllUtilisateurs(true);
                // On supprime les utilisateurs qui sont des administrateurs lors de l'affichage
                foreach ($utilisateursList as $key => $utilisateur)
                {
                    if ($utilisateur->getCodeRole() == 'ADMIN')
                    {
                        unset($utilisateursList[$key]);
                    }
                }
                $roleUtilisateurs = RoleUtilisateurModel::getAllRoleUtilisateurs();
                require_once 'views/utilisateursListManage.php';
                unsetSessionVariables();
            }
            else
            {
                $_SESSION['errorPage'] = self::$config['1002']; // Erreur : Vous n'avez pas les droits d'accès à cette page
                header('Location: '.self::$config["base_url"].'index.php/error');  
            }
            
        }
        else
        {
            $_SESSION['errorPage'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config["base_url"].'index.php/error');  
        }
    }
    
    /**
     * updatePassword : Mettre à jour le mot de passe d'un utilisateur
     *
     * @return void
     */
    public function updatePassword()
    {
        if(isset($_SESSION['code_role']))
        {
            $password = htmlspecialchars($_POST['newPassword']);
            $passwordHashed = password_hash($password , PASSWORD_DEFAULT); // Hashage du mot de passe
            $response = UtilisateurModel::updatePassword($_SESSION["id_util"],$passwordHashed);
            
            if ($response)
            {
                $_SESSION['success'] = "Votre mot de passe a bien été modifié";
                header('Location: '.self::$config["base_url"].'index.php/utilisateurs');
            }
            else
            {
                $_SESSION['error'] = "Erreur lors de la modification du mot de passe"; // Erreur : Erreur lors de la modification du mot de passe
                header('Location: '.self::$config["base_url"].'index.php/utilisateurs');
            }
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config["base_url"]);
        }
        

    }
    
    /**
     * resetPassword : Réinitialiser le mot de passe d'un utilisateur
     *
     * @return void
     */
    public function resetPassword()
    {
        if(isset($_SESSION['code_role']))
        {
            if ($_SESSION['code_role'] != 'ADMIN')
            {
                $_SESSION['error'] = self::$configErrors['1004']; // Erreur : Vous n'avez pas les droits pour accéder à cette page
                header('Location: '.self::$config["base_url"].'index.php/error');  
            }
            else
            {
                $idUtilisateur = htmlspecialchars($_GET['idUtilisateur']);
                $utilisateur = UtilisateurModel::getUtilisateurById($idUtilisateur);

                $password = generateRandomPassword();
                $passwordHashed = password_hash($password , PASSWORD_DEFAULT);

                $response = UtilisateurModel::updatePassword($idUtilisateur,$passwordHashed);
                
                if ($response)
                {
                    $_SESSION['success'] = "Le mot de passe a bien été réinitialisé. Le nouveau mot de passe de l'utilisateur ".$utilisateur->getNom()." ".$utilisateur->getPrenom()." 
                    est : ".$password."";
                    header('Location: '.self::$config["base_url"].'index.php/utilisateurs/allUsers');
                }
                else
                {
                    $_SESSION['error'] = "Erreur lors de la réinitialisation du mot de passe"; // Erreur : Erreur lors de la réinitialisation du mot de passe
                    header('Location: '.self::$config["base_url"].'index.php/utilisateurs/allUsers');
                }
            }
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config["base_url"]);
        }
        
        
    }

}

