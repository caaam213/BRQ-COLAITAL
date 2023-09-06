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

        
    /**
     * displayCategoriesProduitsInterface : Afficher la page de gestion des catégories de produits
     *
     * @return void
     */
    public function displayCategoriesProduitsInterface()
    {
        verifyAccesRoleCode(["ADMIN","CONTROLEUR"]);
        $categoriesProduit = CategorieProduitModel::getAllCategoriesProduits();
        $unitesMesure = UniteMesureModel::getAllUnitesMesure();
        
        // Créer un tableau pour associer l'id de l'unité de mesure avec son nom
        $unitesMesureList = array();
        foreach ($unitesMesure as $uniteMesure) 
        {
            $unitesMesureList[$uniteMesure->getIdUnite()] = $uniteMesure->getUniteNom();
        }
        require_once 'views/categoriesProduitsManage.php';
        unsetSessionVariables();
        
    }
    
    /**
     * addCategorie : Afficher la page de création d'une catégorie de produit
     *
     * @return void
     */
    public function addCategorie()
    {
        verifyAccesRoleCode(["ADMIN","CONTROLEUR"]);
        $unitesMesure = UniteMesureModel::getAllUnitesMesure();
        require_once 'views/categoriesProduitsCreerForm.php';
        unsetSessionVariables();
    }
    
    /**
     * add : Ajouter une catégorie de produit
     *
     * @return void
     */
    public function add()
    {
        verifyAccesRoleCode(["ADMIN","CONTROLEUR"]);
        if (isset($_POST['codeCategorie']) && isset($_POST['nomCategorie'])) 
        {

            // On vérifie si la catégorie n'existe pas déjà
            $categoriesProduit = CategorieProduitModel::getAllCategoriesProduits();
            foreach ($categoriesProduit as $categorieProduit)
            {
                if ($categorieProduit->getCodeCategorie() == $_POST['codeCategorie']) 
                {
                    $_SESSION['error'] = "La catégorie existe déjà"; // Erreur : La catégorie existe déjà
                    header('Location: '.self::$config['base_url'].'index.php/categoriesProduits/addCategorie');
                    exit();
                }
            }

            $codeCategorie = htmlspecialchars($_POST['codeCategorie']);
            $nomCategorie =  htmlspecialchars($_POST['nomCategorie']);
            $idUnite =  htmlspecialchars($_POST['idUnite']);

            $categorieProduit = new CategorieProduit($codeCategorie, $nomCategorie, $idUnite);
            $response = CategorieProduitModel::addCategorieProduit($categorieProduit);
            if (!$response) 
            {
                $_SESSION['error'] = "La catégorie n'a pas pu être ajoutée"; // Erreur : La catégorie n'a pas pu être ajoutée
            }
            else
            {
                $_SESSION['success'] = "La catégorie a bien été ajoutée"; // Succès : La catégorie a bien été ajoutée
            }
            header('Location: '.self::$config['base_url'].'index.php/categoriesProduits/addCategorie');
            exit();
        } 
        $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
        header('Location: '.self::$config['base_url'].'index.php/categoriesProduits/addCategorie');
        exit();
    }
    
    /**
     * modifyCategorieProduct : Afficher la page de modification d'une catégorie de produit
     * 
     * @return void
     */
    public function modifyCategorieProduct()
    {
        verifyAccesRoleCode(["ADMIN","CONTROLEUR"]);
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
        
    }

    
    /**
     * modify : Modifier une catégorie de produit
     *
     * @return void
     */
    public function modify()
    {
        verifyAccesRoleCode(["ADMIN","CONTROLEUR"]);
        $idCategorie = htmlspecialchars($_POST['idCategorie']);
        if (isset($_POST['codeCategorie']) && isset($_POST['nomCategorie'])) 
        {
            $codeCategorie = htmlspecialchars($_POST['codeCategorie']);
            $nomCategorie =  htmlspecialchars($_POST['nomCategorie']);
            $idUnite =  htmlspecialchars($_POST['idUnite']);


            $categorieProduit = new CategorieProduit($codeCategorie, $nomCategorie, $idUnite);
            $categorieProduit->setIdCategorie($idCategorie);
            $response = CategorieProduitModel::updateCategorie($categorieProduit);
                
            if (!$response) 
            {
                $_SESSION['error'] = "Erreur lors de la modification de la catégorie ".$idCategorie; // Erreur dans la modification
            }
            else
            {
                $_SESSION['success'] = "La catégorie ".$idCategorie." a bien été modifiée"; // Succès : La catégorie a bien été modifiée
            }   
            header('Location: '.self::$config['base_url'].'index.php/categoriesProduits');
            exit();
        } 
        else 
        {
            $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
            header('Location: '.self::$config['base_url'].'index.php/categoriesProduits/modifyCategorieProduct?idCategorie='.$idCategorie);
            exit();
        }
        
    }





    
}
