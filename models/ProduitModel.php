<?php
    require_once 'models/connexionDB.php';
    require_once 'classes/Produit.php';
    require_once 'classes/ModifierProduit.php';
    require_once 'models/HistoriqueModel.php';


class ProduitModel
{    
    /**
     * getAllProduits : récupère tous les produits
     *
     * @return array
     */
    public static function getAllProduits()
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->query("SELECT * FROM Produit");
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $produitsList = array();
            foreach ($produits as $produit)
            {
                $produitObj = new Produit($produit['code_produit'], $produit['nom_produit'], $produit['type_produit'], 
                $produit['objectif'], $produit['date_creation'], $produit['id_unite_mesure'], $produit['id_categorie'], 
                $produit['Id_Utilisateur']);
                
                $produitObj->setIdProduit($produit['id_produit']);
                $produitsList[] = $produitObj;
            }
            return $produitsList;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération des produits : " . $e->getMessage();
            return array();
        }
    }
    
    /**
     * addProduit : ajoute un produit
     *
     * @param  Produit $produit
     * @return int|false
     */
    public static function addProduit($produit)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("INSERT INTO Produit (code_produit, nom_produit, type_produit, objectif, date_creation, id_unite_mesure, id_categorie, Id_Utilisateur) VALUES (:code_produit, :nom_produit, :type_produit, :objectif, :date_creation, :idUnite, :idCategorie, :Id_Utilisateur)");
            $stmt->bindParam(':code_produit', $produit->getCodeProduit());
            $stmt->bindParam(':nom_produit', $produit->getNomProduit());
            $stmt->bindParam(':type_produit', $produit->getTypeProduit());
            $stmt->bindParam(':objectif', $produit->getObjectif());
            $stmt->bindParam(':date_creation', $produit->getDateCreation());
            $stmt->bindParam(':idUnite', $produit->getIdUnite());
            $stmt->bindParam(':idCategorie', $produit->getIdCategorie());
            $stmt->bindParam(':Id_Utilisateur', $produit->getIdUtilisateur());
            $stmt->execute();

            $idLastInserted = $dbConnection->lastInsertId();

            $textForHistoric = "L'utilisateur ".$_SESSION["nom_util"]." ".$_SESSION["prenom_util"]." a ajouté le produit ".$idLastInserted.":".$produit->getNomProduit(). "à la date du ".$produit->getDateCreation();
            HistoriqueModel::addToHistorique($textForHistoric);

            return $idLastInserted;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de l'ajout du produit : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * addComposition : ajoute une composition à un produit
     *
     * @param  int $idProduit
     * @param  int $idMatierePremiere
     * @return boolean
     */
    public static function addComposition($idProduit, $idMatierePremiere)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("INSERT INTO Composer_Produits (id_produit, id_produit_matiere_premiere) VALUES (:id_produit, :id_matiere_premiere)");
            $stmt->bindParam(':id_produit', $idProduit);
            $stmt->bindParam(':id_matiere_premiere', $idMatierePremiere);
            $stmt->execute();

            return true;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de l'ajout de la composition du produit : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * getProduitById : récupère un produit par son id
     *
     * @param  int $id
     * @return Produit|null
     */
    public static function getProduitById($id)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT * FROM Produit WHERE id_produit = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            $produitObj = new Produit($produit['code_produit'], $produit['nom_produit'], $produit['type_produit'], $produit['objectif'], $produit['date_creation'], $produit['id_unite_mesure'], $produit['id_categorie'], $produit['Id_Utilisateur']);
            $produitObj->setIdProduit($produit['id_produit']);
            return $produitObj;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération du produit : " . $e->getMessage();
            return null;
        }
    }
    
    /**
     * updateProduit : met à jour un produit
     *
     * @param  Produit $produit
     * @return boolean|string
     */
    public static function updateProduit($produit)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("UPDATE Produit SET code_produit = :codeProduit,  nom_produit = :nom_produit,
             type_produit = :type_produit, objectif = :objectif, date_creation = :date_creation, 
             id_unite_mesure = :idUniteMesure, id_categorie = :idCategorie, Id_Utilisateur = :idUtilisateur 
             WHERE id_produit = :idProduit");
            $stmt->bindParam(':idProduit', $produit->getIdProduit());
            $stmt->bindParam(':codeProduit', $produit->getCodeProduit());
            $stmt->bindParam(':nom_produit', $produit->getNomProduit());
            $stmt->bindParam(':type_produit', $produit->getTypeProduit());
            $stmt->bindParam(':objectif', $produit->getObjectif());
            $stmt->bindParam(':date_creation', $produit->getDateCreation());
            $stmt->bindParam(':idUniteMesure', $produit->getIdUnite());
            $stmt->bindParam(':idCategorie', $produit->getIdCategorie());
            $stmt->bindParam(':idUtilisateur', $produit->getIdUtilisateur());
            $stmt->execute();

            $textForHistoric = "L'utilisateur ".$_SESSION["nom_util"]." ".$_SESSION["prenom_util"]." a modifié le produit ".$produit->getCodeProduit().":".$produit->getNomProduit(). "à la date du ".$produit->getDateCreation();
            HistoriqueModel::addToHistorique($textForHistoric);


            // Modification de la composition du produit dans la table composer_produit
            return true;
        }
        catch (PDOException $e)
        {
            return "Erreur lors de la mise à jour du produit : " . $e->getMessage();
            //return false;
        }
    }
    
    /**
     * addModifierProduit : ajoute une ligne dans la table modifier_produit
     *
     * @param  ModifierProduit $modifierProduit
     * @return boolean|string
     */
    public static function addModifierProduit($modifierProduit)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("INSERT INTO Modifier_produit (id_Utilisateur, id_produit,  date_heure) VALUES (:id_utilisateur,:id_produit,  :date_modification)");
            $stmt->bindParam(':id_produit', $modifierProduit->getIdProduit());
            $stmt->bindParam(':id_utilisateur', $modifierProduit->getIdUtilisateur());
            $stmt->bindParam(':date_modification', $modifierProduit->getDateModif());
            $stmt->execute();
            return true;
        }
        catch (PDOException $e)
        {
            return "Erreur lors de l'ajout à la table modifierProduit' : " . $e->getMessage();
            //return false;
        }
        

    }
    
    /**
     * deleteComposerProduit : supprime la composition d'un produit
     *
     * @param  int $idProduit
     * @return boolean
     */
    public static function deleteComposerProduit($idProduit)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("DELETE FROM Composer_Produits WHERE id_produit = :id_produit");
            $stmt->bindParam(':id_produit', $idProduit);
            $stmt->execute();
            return true;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la suppression de la composition du produit : " . $e->getMessage();
            return false;
        }

    }
    
    /**
     * getAllMatieresPremieres : récupère toutes les matières premières
     *
     * @return array
     */
    public static function getAllMatieresPremieres()
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->query("SELECT * FROM Produit WHERE type_produit = 'Matière première'");
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $produitsList = array();
            foreach ($produits as $produit)
            {
                $produitObj = new Produit($produit['code_produit'], $produit['nom_produit'], $produit['type_produit'], $produit['objectif'], $produit['date_creation'], $produit['id_unite_mesure'], $produit['id_categorie'], $produit['Id_Utilisateur']);
                $produitObj->setIdProduit($produit['id_produit']);
                $produitsList[] = $produitObj;
            }
            return $produitsList;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération des produits : " . $e->getMessage();
            return array();
        }
    }
    
    /**
     * getCompositionsProduit : récupère la composition d'un produit
     *
     * @param  mixed $idProduit
     * @return array
     */
    public static function getCompositionsProduit($idProduit)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT * FROM Composer_produits WHERE id_produit = :idProduit");
            $stmt->bindParam(':idProduit', $idProduit);
            $stmt->execute();
            $compositionProduit = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $compositionProduitList = array();
            foreach ($compositionProduit as $composition)
            {
                $compositionProduitList[] = $composition['id_produit_matiere_premiere'];
            }
            return $compositionProduitList;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération de la composition du produit : " . $e->getMessage();
            return array();
        }
    }
    
    /**
     * getLastUserModify : récupère le dernier utilisateur ayant modifié le produit
     *
     * @param  int $idProduit
     * @return Utilisateur|null|boolean
     */
    public static function getLastUserModify($idProduit)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT TOP 1 * FROM Modifier_produit WHERE id_Produit = :idProduit 
            ORDER BY date_heure DESC");
            $stmt->bindParam(':idProduit', $idProduit);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $utilisateur = UtilisateurModel::getUtilisateurById($row['Id_Utilisateur']);
                return $utilisateur;
            } else {
                return null; // Aucun utilisateur n'a modifié le produit
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }


    
}