<?php 
    require_once 'models/CategorieProduitModel.php';
    require_once 'models/UniteMesureModel.php';
    require_once 'utilsController.php';



class CategoriesProduitsController
{
    private static $config;
    private static $configErrors;
    
    /**
     * __construct : Fonction appelée automatiquement à l'instanciation de la classe
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
        $this->displayCategoriesProduitsInterface();
    }

    
    public function displayCategoriesProduitsInterface()
    {
        if (isset($_SESSION['code_role'])) {
            $categoriesProduit = CategorieProduitModel::getAllCategoriesProduits();
            $unitesMesure = UniteMesureModel::getAllUnitesMesure();
            // Créer un tableau pour associer l'id de l'unité de mesure avec son nom
            $unitesMesureList = array();
            foreach ($unitesMesure as $uniteMesure) {
                $unitesMesureList[$uniteMesure->getIdUnite()] = $uniteMesure->getUniteNom();
            }
            require_once 'views/categoriesProduitsManage.php';
            unsetSessionVariables();
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
            exit();
        }
    }

    public function addCategorie()
    {
        if (isset($_SESSION['code_role'])) {
            $unitesMesure = UniteMesureModel::getAllUnitesMesure();
            require_once 'views/categoriesProduitsCreerForm.php';
            unsetSessionVariables();
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
            exit();
        }
    }

    public function add()
    {
        if (isset($_SESSION['code_role'])) {
            if (isset($_POST['codeCategorie']) && isset($_POST['nomCategorie'])) {

                // On vérifie si la catégorie n'existe pas déjà
                $categoriesProduit = CategorieProduitModel::getAllCategoriesProduits();
                foreach ($categoriesProduit as $categorieProduit) {
                    if ($categorieProduit->getCodeCategorie() == $_POST['codeCategorie']) {
                        $_SESSION['error'] = self::$configErrors['1008']; // Erreur : La catégorie existe déjà
                        header('Location: '.self::$config['base_url'].'index.php/categoriesProduits/addCategorie');
                        exit();
                    }
                }

                $codeCategorie = htmlspecialchars($_POST['codeCategorie']);
                $nomCategorie =  htmlspecialchars($_POST['nomCategorie']);
                $idUnite =  htmlspecialchars($_POST['idUnite']);

                $categorieProduit = new CategorieProduit($codeCategorie, $nomCategorie, $idUnite);
                $response = CategorieProduitModel::addCategorieProduit($categorieProduit);
                if (!$response) {
                    $_SESSION['error'] = "La catégorie n'a pas pu être ajoutée"; // Erreur : La catégorie n'a pas pu être ajoutée
                }
                else
                {
                    $_SESSION['success'] = "La catégorie a bien été ajoutée"; // Succès : La catégorie a bien été ajoutée
                }
                header('Location: '.self::$config['base_url'].'index.php/categoriesProduits/addCategorie');
                exit();
            } else {
                $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
                header('Location: '.self::$config['base_url'].'index.php/categoriesProduits/addCategorie');
                exit();
            }
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
            exit();
        }
    }

    public function modifyCategorieProduct()
    {
        if (isset($_SESSION['code_role'])) {
            if (isset($_GET['idCategorie']))
            {
                $idCategorie = htmlspecialchars($_GET['idCategorie']);
                $categorieProduit = CategorieProduitModel::getCategorieById($idCategorie);
                if ($categorieProduit != null)
                {
                    $unitesMesure = UniteMesureModel::getAllUnitesMesure();
                    require_once 'views/categoriesProduitsModifierForm.php';
                }
                else
                {
                    $_SESSION['errorPage'] = "La catégorie n'existe pas"; // Erreur : La catégorie n'existe pas
                    header('Location: '.self::$config['base_url'].'index.php/error');
                    exit();
                }
            }
            else
            {
                $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
                header('Location: '.self::$config['base_url'].'index.php/categoriesProduits');
                exit();
            }
            unsetSessionVariables();
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
            exit();
        }
    }


    public function modify()
    {
        if (isset($_SESSION['code_role'])) {
            $idCategorie = htmlspecialchars($_POST['idCategorie']);
            if (isset($_POST['codeCategorie']) && isset($_POST['nomCategorie'])) {
                $codeCategorie = htmlspecialchars($_POST['codeCategorie']);
                $nomCategorie =  htmlspecialchars($_POST['nomCategorie']);
                $idUnite =  htmlspecialchars($_POST['idUnite']);


                $categorieProduit = new CategorieProduit($codeCategorie, $nomCategorie, $idUnite);
                $categorieProduit->setIdCategorie($idCategorie);
                $response = CategorieProduitModel::updateCategorie($categorieProduit);
                
                if (!$response) {
                    $_SESSION['error'] = "Erreur lors de la modification de la catégorie ".$idCategorie; // Erreur dans la modification
                }
                else
                {
                    $_SESSION['success'] = "La catégorie ".$idCategorie." a bien été modifiée"; // Succès : La catégorie a bien été modifiée

                }   
                header('Location: '.self::$config['base_url'].'index.php/categoriesProduits');
                exit();
            } 
            else {
                $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
                header('Location: '.self::$config['base_url'].'index.php/categoriesProduits/modifyCategorieProduct?idCategorie='.$idCategorie);
                exit();
            }
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
            exit();
        }
    }





    
}
