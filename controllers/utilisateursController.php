

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
        verifyAccesRoleCode();
        require_once 'views/utilisateurInterface.php';
        unsetSessionVariables();
    }
    
    /**
     * addUser : Afficher le formulaire de création d'un utilisateur
     *
     * @return void
     */
    public function addUser()
    {
        verifyAccesRoleCode(['ADMIN']);
        $mdp = generateRandomPassword();
        $roleUtilisateurs = RoleUtilisateurModel::getAllRoleUtilisateurs();
        require_once 'views/utilisateurCreerForm.php';
        unsetSessionVariables();    
    }
    
    /**
     * add : Ajouter un utilisateur dans la base de données
     *
     * @return void
     */
    public function add()
    {
        verifyAccesRoleCode(['ADMIN']);
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
                exit();
            }
            else
            {
                // Ajout de l'utilisateur dans la base de données
                $utilisateurModel = new UtilisateurModel();
                $utilisateurModel->addUtilisateur($utilisateur);
                $_SESSION['success'] = "L'utilisateur a bien été ajouté";
                header('Location: '.self::$config["base_url"].'index.php/utilisateurs');
                exit();
            }
        }
        else
        {
            $_SESSION['error'] = "Erreur lors de l'ajout d'un utilisateur"; 
            header('Location: '.self::$config["base_url"].'index.php/utilisateurs/addUser');
            exit();
        }
    }
            
    
    /**
     * updateAcces : Mettre à jour l'accès d'un utilisateur
     *
     * @return void
     */
    public function updateAcces()
    {
        verifyAccesRoleCode(["ADMIN"]);
        $acces = htmlspecialchars($_GET['acces']);
        $idUtilisateur = htmlspecialchars($_GET['idUtilisateur']);

        $utilisateurModel = new UtilisateurModel();
        $utilisateurModel->updateAcces($acces, $idUtilisateur);
        $_SESSION['success'] = "L'accès a bien été modifié";
        header('Location: '.self::$config["base_url"].'index.php/utilisateurs/allUsers');
        exit();
    }

    
    /**
     * allUsers : Afficher la liste des utilisateurs
     *
     * @return void
     */
    public function allUsers()
    {
        verifyAccesRoleCode(["ADMIN"]);
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
    
    /**
     * updatePassword : Mettre à jour le mot de passe d'un utilisateur
     *
     * @return void
     */
    public function updatePassword()
    {
        verifyAccesRoleCode();
        $password = htmlspecialchars($_POST['newPassword']);
        $passwordHashed = password_hash($password , PASSWORD_DEFAULT); // Hashage du mot de passe
        $response = UtilisateurModel::updatePassword($_SESSION["id_util"],$passwordHashed);
            
        if ($response)
        {
            $_SESSION['success'] = "Votre mot de passe a bien été modifié";
            header('Location: '.self::$config["base_url"].'index.php/utilisateurs');
            exit();
        }
        else
        {
            $_SESSION['error'] = "Erreur lors de la modification du mot de passe"; // Erreur : Erreur lors de la modification du mot de passe
            header('Location: '.self::$config["base_url"].'index.php/utilisateurs');
            exit();
        }
    }
    
    /**
     * resetPassword : Réinitialiser le mot de passe d'un utilisateur
     *
     * @return void
     */
    public function resetPassword()
    {
        verifyAccesRoleCode(["ADMIN"]);
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
            exit();
        }
        else
        {
            $_SESSION['error'] = "Erreur lors de la réinitialisation du mot de passe"; // Erreur : Erreur lors de la réinitialisation du mot de passe
            header('Location: '.self::$config["base_url"].'index.php/utilisateurs/allUsers');
            exit();
        }
    }

}

