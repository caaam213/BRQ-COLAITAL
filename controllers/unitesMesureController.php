<?php 
    require_once 'models/UniteMesureModel.php';
    require_once 'utilsController.php';

class UnitesMesureController 
{
    private static $config;
    private static $configErrors;

    /**
     * __construct : Constructeur de la classe UnitesMesureController
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
        $this->displayUnitesMesureInterface();
    }

    /**
     * displayUnitesMesureInterface : Affiche l'interface Unités de mesure
     *
     * @return void
    */
    public function displayUnitesMesureInterface()
    {
        if (isset($_SESSION['code_role'])) {
            $unitesMesure = UniteMesureModel::getAllUnitesMesure();
            require_once 'views/unitesMesureManage.php';
            unsetSessionVariables();
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }
    
    /**
     * addUniteMesure : Affiche le formulaire d'ajout d'une unité de mesure
     *
     * @return void
     */
    public function addUniteMesure()
    {
        if (isset($_SESSION['code_role'])) {
            require_once 'views/unitesMesureCreerForm.php';
            unsetSessionVariables();
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }
    
    /**
     * add : Ajoute une unité de mesure
     *
     * @return void
     */
    public function add()
    {
        
        if (isset($_SESSION['code_role'])) {
            if (isset($_POST['codeUnite']) && isset($_POST['uniteNom']) && isset($_POST['multiplicateur'])) {
                // Vérification que le code de l'unité de mesure n'existe pas déjà
                $unites = UniteMesureModel::getAllUnitesMesure();
                $unitesCode = array();
                foreach ($unites as $unite) {
                    array_push($unitesCode, $unite->getCodeUnite());
                }

                $codeUnite = htmlspecialchars($_POST['codeUnite']);
                $uniteNom = htmlspecialchars($_POST['uniteNom']);
                $multiplicateur = htmlspecialchars($_POST['multiplicateur']);
                

                if(in_array($codeUnite, $unitesCode))
                {
                    // Si l'unité de mesure se trouve déjà dans la base de données
                    $_SESSION['error'] = "Le code de l'unité de mesure existe déjà";
                }
                else
                {
                    // Si tout va bien, on ajoute l'unité de mesure
                    $uniteMesure = new UniteMesure($codeUnite, $uniteNom, $multiplicateur);
                    $result = UniteMesureModel::addUniteMesure($uniteMesure);
                    if ($result) {
                        $_SESSION['success'] = "L'unité de mesure ". $codeUnite." a bien été ajoutée";
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'ajout de l'unité de mesure"; // Erreur : L'unité de mesure n'a pas pu être ajoutée
                    }
                }
                
            } 
            else {
                $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
            }
            header('Location: '.self::$config['base_url'].'index.php/unitesMesure/addUniteMesure');
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }
    
    /**
     * modifyUniteMesure : Affiche le formulaire de modification d'une unité de mesure
     *
     * @return void
     */
    public function modifyUniteMesure()
    {
        if (isset($_SESSION['code_role'])) {
            if (isset($_GET['idUnite']))
            {
                // Vérification que l'unité de mesure est bien renseignée
                $idUnite = htmlspecialchars($_GET['idUnite']);
                if ($idUnite == null || $idUnite == "") {
                    $_SESSION['error'] = "L'unité de mesure n'existe pas"; // L'unité de mesure n'existe pas
                    header('Location: '.self::$config['base_url'].'index.php/error');
                    return;
                }

                // On vérifie que l'unité de mesure existe dans la base de données
                $uniteMesure = UniteMesureModel::getUniteMesureById($idUnite);
                if ($uniteMesure != null)
                {
                    require_once 'views/unitesMesureModifierForm.php';
                }
                else
                {
                    $_SESSION['errorPage'] = "L'unité de mesure n'existe pas"; // Erreur : L'unité de mesure n'existe pas
                    header('Location: '.self::$config['base_url'].'index.php/error');
                }
            }
            else
            {
                $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
                header('Location: '.self::$config['base_url'].'index.php/unitesMesure');
            }
            unsetSessionVariables();
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }
    
    /**
     * modify : Modifie une unité de mesure
     *
     * @return void
     */
    public function modify()
    {
        if (isset($_SESSION['code_role'])) {
            if (isset($_POST['codeUnite']) && isset($_POST['uniteNom']) && isset($_POST['multiplicateur'])) {
                $codeUnite = htmlspecialchars($_POST['codeUnite']);
                $uniteNom = htmlspecialchars($_POST['uniteNom']);
                $multiplicateur = htmlspecialchars($_POST['multiplicateur']);
                
                $uniteMesure = new UniteMesure($codeUnite, $uniteNom, $multiplicateur);
                $uniteMesure->setIdUnite(htmlspecialchars($_POST['idUnite']));

                $result = UniteMesureModel::modifyUniteMesure($uniteMesure);
                if ($result) {
                    $_SESSION['success'] = "L'unité de mesure ". $codeUnite." a bien été modifiée";
                } else {
                    $_SESSION['error'] = "L'unité de mesure ". $codeUnite." n'a pas pu être modifiée"; // Erreur : L'unité de mesure n'a pas pu être modifiée
                }
            } else {
                $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
            }
            header('Location: '.self::$config['base_url'].'index.php/unitesMesure');
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }
}
