<?php 

require_once 'classes/ProduitPerte.php';

class BrqProduitPerteModel
{    
    /**
     * getAllProduitPerte : Récupère tous les produits perte de la base de données
     *
     * @return array
     */
    public static function getAllProduitPerte()
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->query("SELECT * FROM Produit_perte");
            $produitsPerte = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $produitsPerteList = array();

            foreach ($produitsPerte as $produitPerte)
            {
                $produitPerteObj = new ProduitPerte($produitPerte['code_produit_perte'], $produitPerte['intitule']);
                $produitsPerteList[] = $produitPerteObj;
            }
            return $produitsPerteList;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération des produits perte : " . $e->getMessage();
            return array();
        }
    
    }
}