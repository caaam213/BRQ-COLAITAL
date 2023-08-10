<?php
require_once 'models/connexionDB.php';
require_once 'classes/CategorieProduit.php';
require_once 'models/HistoriqueModel.php';

class CategorieProduitModel 
{    
    /**
     * getAllCategoriesProduits : Récupère toutes les catégories de produits
     *
     * @return array
     */
    public static function getAllCategoriesProduits()
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->query("SELECT * FROM Categorie_produit");
            $categoriesProduits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categoriesProduitsList = array();

            foreach ($categoriesProduits as $categorieProduit)
            {
                $categorieProduitObj = new CategorieProduit($categorieProduit['code_categorie'], $categorieProduit['nom_categorie'], $categorieProduit['id_unite_mesure']);
                $categorieProduitObj->setIdCategorie($categorieProduit['id_categorie']);
                $categoriesProduitsList[] = $categorieProduitObj;
            }
            return $categoriesProduitsList;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération des catégories de produits : " . $e->getMessage();
            return array();
        }
    
    }
    
    /**
     * addCategorieProduit : Ajoute une catégorie de produit
     *
     * @param  CategorieProduit $categorie
     * @return boolean
     */
    public static function addCategorieProduit($categorie)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("INSERT INTO Categorie_produit (code_categorie, nom_categorie,  id_unite_mesure)
            VALUES (:code_categorie, :nom_categorie,  :id_unite_mesure)");
            $stmt->bindParam(':code_categorie', $categorie->getCodeCategorie());
            $stmt->bindParam(':nom_categorie', $categorie->getNomCategorie());
            $stmt->bindParam(':id_unite_mesure', $categorie->getIdUnite());
            $stmt->execute();

            $idLastInserted = $dbConnection->lastInsertId();

            $textForHistoric = "La catégorie de produit " . $idLastInserted . " " ."ajoutée à la date du " . date("d/m/Y") . " à " . date("H:i:s") . " par l'utilisateur ".$_SESSION["nom_util"]." ".$_SESSION["prenom_util"].".\n";
            HistoriqueModel::addToHistorique($textForHistoric);
            return true;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de l'ajout de la catégorie de produit : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * getCategorieById : Récupère une catégorie de produit par son id
     *
     * @param  int $idCategorie
     * @return CategorieProduit|null
     */
    public static function getCategorieById($idCategorie)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT * FROM Categorie_produit WHERE id_categorie = :idCategorie");
            $stmt->bindParam(':idCategorie', $idCategorie);
            $stmt->execute();
            $categorieProduit = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$categorieProduit)
            {
                return null;
            }
            
            $categorieProduit = new CategorieProduit($categorieProduit['code_categorie'], $categorieProduit['nom_categorie'],$categorieProduit['id_unite_mesure']);
            $categorieProduit->setIdCategorie($idCategorie);
            return $categorieProduit;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération de la catégorie de produit : " . $e->getMessage();
            return null;
        }
    }
    
    /**
     * updateCategorie : Modifie une catégorie de produit
     *
     * @param  CategorieProduit $categorie
     * @return boolean
     */
    public static function updateCategorie($categorie)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("UPDATE Categorie_produit SET code_categorie = :codeCategorie,  nom_categorie = :nomCategorie, id_unite_mesure = :idUnite WHERE id_categorie = :idCategorie");
            $stmt->bindParam(':idCategorie', $categorie->getIdCategorie());
            $stmt->bindParam(':codeCategorie', $categorie->getCodeCategorie());
            $stmt->bindParam(':nomCategorie', $categorie->getNomCategorie());
            $stmt->bindParam(':idUnite', $categorie->getIdUnite());
            $stmt->execute();

            $textForHistoric = "La catégorie de produit " . $categorie->getIdCategorie() . " " ."modifiée à la date du " . date("d/m/Y") . " à " . date("H:i:s") . " par l'utilisateur ".$_SESSION["nom_util"]." ".$_SESSION["prenom_util"].".\n";
            HistoriqueModel::addToHistorique($textForHistoric);
            return true;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la modification de la catégorie de produit : " . $e->getMessage();
            return false;
        }
    }
}