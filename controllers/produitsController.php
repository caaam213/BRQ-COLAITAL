<?php

require_once 'utilsController.php';
require_once 'models/produitModel.php';
require_once 'models/uniteMesureModel.php';
require_once 'models/categorieProduitModel.php';
require_once 'models/utilisateurModel.php';


class ProduitsController
{
    private static $config;
    private static $configErrors;
    
    /**
     * __construct : Constructeur de la classe produitsController
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
        $this->displayProduitsInterface();
    }

    /**
     * displayProduitsInterface : Affiche l'interface Produits
     *
     * @return void
     */
    public function displayProduitsInterface()
    {
        if (isset($_SESSION['code_role'])) {
            $produits = ProduitModel::getAllProduits();
            $unitesMesure = UniteMesureModel::getAllUnitesMesure();
            $categoriesProduit = CategorieProduitModel::getAllCategoriesProduits();
            $utilisateurs = UtilisateurModel::getAllUtilisateurs();

            // Trouver le nom de l'unité de mesure en fonction de l'id
            $unitesMesureList = array();
            foreach ($unitesMesure as $uniteMesure) {
                $unitesMesureList[$uniteMesure->getIdUnite()] = $uniteMesure->getUniteNom();
            }

            // Trouver le nom de la catégorie en fonction de l'id
            $categoriesProduitList = array();
            foreach ($categoriesProduit as $categorieProduit) {
                $categoriesProduitList[$categorieProduit->getIdCategorie()] = $categorieProduit->getNomCategorie();
            }

            // Trouver le nom et le prénom de l'utilisateur en fonction de l'id
            $utilisateursList = array();
            foreach ($utilisateurs as $utilisateur) {
                $utilisateursList[$utilisateur->getId()] = $utilisateur->getPrenom()." ".$utilisateur->getNom();
            }

            // Avoir les compositions des produits 
            $produits = ProduitModel::getAllProduits();
            

            // Trouver les produits composants
            $produitsCompositions = array();
            $lastUserModif = array();
            foreach ($produits as $produit) {
                $compositionsList = ProduitModel::getCompositionsProduit($produit->getIdProduit());                
                foreach ($compositionsList as $composition) {
                    $produitsCompositions[$produit->getIdProduit()][] = ProduitModel::getProduitById($composition);
                }

                // Trouver le dernier utilisateur qui a modifié le produit
                $lastModif = ProduitModel::getLastUserModify($produit->getIdProduit());
                if ($lastModif != null)
                {
                    $lastUserModif[$produit->getIdProduit()] = $lastModif->getPrenom()." ".$lastModif->getNom();
                }
                
            }


            require_once 'views/produitsManage.php';
            unsetSessionVariables();
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }
    
    /**
     * addProduit : Affiche le formulaire de création d'un produit
     *
     * @return void
     */
    public function addProduit()
    {
        if (isset($_SESSION['code_role'])) {
            $unitesMesure = UniteMesureModel::getAllUnitesMesure();
            $categoriesProduit = CategorieProduitModel::getAllCategoriesProduits();
            $matieresPremiere = ProduitModel::getAllMatieresPremieres();
            

            require_once 'views/produitsCreerForm.php';
            unsetSessionVariables();
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }
    
    /**
     * add : Ajoute un produit dans la base de données
     *
     * @return void
     */
    public function add()
    {
        if (isset($_SESSION['code_role']))
        {
            if(isset($_POST['codeProduit']) && isset($_POST['nomProduit']) && isset($_POST['typeProduit'])
            && isset($_POST['idUnite']))
            {
                // Vérifier que le code prduit n'existe pas déjà
                $produits = ProduitModel::getAllProduits();
                $produitsCode = array();
                foreach ($produits as $produit) {
                    array_push($produitsCode, $produit->getCodeProduit());
                }

                $codeProduit = htmlspecialchars($_POST['codeProduit']);
                $nomProduit = htmlspecialchars($_POST['nomProduit']);
                $typeProduit = htmlspecialchars($_POST['typeProduit']);
                $objectif = isset($_POST['objectif']) ? htmlspecialchars($_POST['objectif']) : null;
                $idUnite = htmlspecialchars($_POST['idUnite']);
                $composer = $_POST['composer'];

                if($typeProduit == "Produit fini")
                {
                    $idCategorie = htmlspecialchars($_POST['idCategorie']);
                }
                else
                {
                    $idCategorie = null;
                }
                

                if (in_array($codeProduit, $produitsCode)) {
                    $_SESSION['error'] = "Le produit existe déjà"; // Erreur : Le code produit existe déjà
                    header('Location: '.self::$config['base_url'].'index.php/produits/addProduit');
                } else {
                    $dateCreation = date('d/m/Y');
                    $idUtilisateur = $_SESSION['id_util'];
                    $produit = new Produit($codeProduit, $nomProduit, $typeProduit, $objectif, $dateCreation,
                    $idUnite, $idCategorie, $idUtilisateur);

                    $idLastInserted = ProduitModel::addProduit($produit);
                    if ($composer != null && !empty($composer))
                    {
                        foreach ($composer as $matierePremiere) {
                            $response = ProduitModel::addComposition($idLastInserted, $matierePremiere);
                        }
                    }
                    else
                    {
                        $response = true;
                    }

                    if ($response)
                    {
                        $_SESSION['success'] = "Le produit a bien été ajouté"; // Succès : Le produit a bien été ajouté
                    }
                    else
                    {
                        $_SESSION['error'] = "Erreur lors de l'ajout du produit"; // Erreur : Le produit n'a pas été ajouté
                        
                        
                    }
                    header('Location: '.self::$config['base_url'].'index.php/produits/addProduit');
                }
            }
            else
            {
                $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
                header('Location: '.self::$config['base_url'].'index.php/produits/addProduit');
            }
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }
    
    /**
     * modifyProduit : Affiche le formulaire de modification d'un produit
     *
     * @return void
     */
    public function modifyProduit()
    {
        if (isset($_SESSION['code_role'])) {
            if (isset($_GET['idProduit'])) {
                $idProduit = htmlspecialchars($_GET['idProduit']);
                $produit = ProduitModel::getProduitById($idProduit);
                $unitesMesure = UniteMesureModel::getAllUnitesMesure();
                $categoriesProduit = CategorieProduitModel::getAllCategoriesProduits();
                $matieresPremiere = ProduitModel::getAllMatieresPremieres();
                if ($produit->getTypeProduit() == "Produit fini") {
                    $compositionsList = ProduitModel::getCompositionsProduit($produit->getIdProduit());    
                } else {
                    $compositionsList = array();
                }
                            


                require_once 'views/produitsModifierForm.php';
                unsetSessionVariables();
            } else {
                $_SESSION['error'] = self::$configErrors['1007']; // Erreur : Tous les champs n'ont pas été remplis
                header('Location: '.self::$config['base_url'].'index.php/produits');
            }
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }
    
    /**
     * modifyErrors : Affiche le formulaire de modification d'un produit avec un message d'erreur
     *
     * @param  string $msg
     * @param  int $idProduit
     * @return void
     */
    public function modifyErrors($msg, $idProduit)
    {
        $_SESSION['error'] = self::$configErrors['1111']. " ".$msg; 
        header('Location: '.self::$config['base_url'].'index.php/produits/modifyProduit?idProduit='.$idProduit);
        
    }
    
    /**
     * modify : Modifie un produit dans la base de données
     *
     * @return void
     */
    public function modify()
    {
        if (isset($_SESSION['code_role'])) {
            $idProduit = htmlspecialchars($_POST['idProduit']);
            if(isset($_POST['codeProduit']) && isset($_POST['nomProduit']) && isset($_POST['typeProduit'])
            && isset($_POST['idUnite']) && isset($_POST['idCategorie']))
            {

                // Vérifier que le code prduit n'existe pas déjà
                $produits = ProduitModel::getAllProduits();
                $produitsCode = array();
                foreach ($produits as $produit) {
                    array_push($produitsCode, $produit->getCodeProduit());
                }

                $codeProduit = htmlspecialchars($_POST['codeProduit']);
                $formerCodeProduit = htmlspecialchars($_POST['formerCode']);
                $nomProduit = htmlspecialchars($_POST['nomProduit']);
                $typeProduit = htmlspecialchars($_POST['typeProduit']);
                $objectif = isset($_POST['objectif']) ? htmlspecialchars($_POST['objectif']) : null;
                $idUnite = htmlspecialchars($_POST['idUnite']);
                $composer = $_POST['composer'];
                $dateCreation = date('d/m/Y', strtotime(htmlspecialchars($_POST['dateCreation'])));
                $idUtilisateur = htmlspecialchars($_POST['idUtilisateur']);

                if($formerCodeProduit != $codeProduit)
                {
                    if(in_array($codeProduit, $produitsCode))
                    {
                        $_SESSION['error'] = "Le produit existe déjà"; // Erreur : Le code produit existe déjà
                        header('Location: '.self::$config['base_url'].'index.php/produits/modifyProduit?idProduit='.$idProduit);
                        return;
                    }
                }

                if($typeProduit == "Produit fini")
                {
                    $idCategorie = htmlspecialchars($_POST['idCategorie']);
                }
                else
                {
                    $idCategorie = null;
                }
                
                
                    $produit = new Produit($codeProduit, $nomProduit, $typeProduit, $objectif, $dateCreation,
                    $idUnite, $idCategorie, $idUtilisateur);
                    $produit->setIdProduit($idProduit);

                    // Modification du produit dans la base de données
                    $responseUpdate = ProduitModel::updateProduit($produit);
                    if (!$responseUpdate)
                    {
                        self::modifyErrors($responseUpdate, $idProduit);
                        //echo $responseUpdate;
                        return;
                    }

                    // Enregistrement de l'utilisateur qui a modifier dans la table modifier_produit
                    $modifierProduit = new ModifierProduit($_SESSION['id_util'], $idProduit, date('d/m/Y'));
                    $responseAddModify = ProduitModel::addModifierProduit($modifierProduit);
                    if (!$responseAddModify)
                    {
                        self::modifyErrors("lors de l'ajout à la table modifier_produit", $idProduit);
                        return;
                    }

                    // On supprime les anciennes compositions du produit
                    $responseDelete = ProduitModel::deleteComposerProduit($idProduit);
                    if (!$responseDelete)
                    {
                        self::modifyErrors("lors de la suppression des compositions du produit", $idProduit);
                        return;
                    }

                    // On ajoute les nouvelles compositions du produit
                    if ($composer != null && !empty($composer))
                    {
                        foreach ($composer as $matierePremiere) {
                            $responseAdd = ProduitModel::addComposition($idProduit, $matierePremiere);
                            if (!$responseAdd)
                            {
                                self::modifyErrors("lors de l'ajout des compositions du produit", $idProduit);
                                return;
                            }
                        }
                    }

                    $_SESSION['success'] = "Le produit ". $produit->getCodeProduit() ."a bien été modifié"; // Succès : Le produit a bien été modifié
                    header('Location: '.self::$config['base_url'].'index.php/produits');
                    

                    
                }


            
        } else {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url']);
        }
    }


    
}