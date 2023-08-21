<?php 
    require_once 'models/connexionDB.php'; 
    require_once 'classes/InfoBrqProduit.php';
    require_once 'models/HistoriqueModel.php';

    class InfoBrqProduitModel
    {
        public static function addInfoBrqProduit($info_brq_produit)
        {
            try
            {
                $dbConnection = ConnexionDB::getInstance()->getConnection();
                $stmt = $dbConnection->prepare("INSERT INTO Info_Brq_Produit 
                (realisation, objectif, stock_fin_journee, quantite_vente, valeur_vente, couverture_jour,
                id_produit, date_brq) VALUES (:realisation, :objectif, :stock_fin_journee, :quantite_vente, :valeur_vente, :couverture_jour, :id_produit, :date_brq)");
                $stmt->bindParam(':realisation', $info_brq_produit->getRealisation());
                $stmt->bindParam(':objectif', $info_brq_produit->getObjectif());
                $stmt->bindParam(':stock_fin_journee', $info_brq_produit->getStockFinJournee());
                $stmt->bindParam(':quantite_vente', $info_brq_produit->getQuantiteVente());
                $stmt->bindParam(':valeur_vente', $info_brq_produit->getValeurVente());
                $stmt->bindParam(':couverture_jour', $info_brq_produit->getCouvertureJour());
                $stmt->bindParam(':id_produit', $info_brq_produit->getIdProduit());
                $stmt->bindParam(':date_brq', $info_brq_produit->getDateBrq());
                $stmt->execute();

                $textForHistoric = "L'utilisateur avec l'id " . $_SESSION['id_util'] . " a ajouté une info BRQ produit avec l'id " . $info_brq_produit->getIdProduit();
                HistoriqueModel::addToHistorique($textForHistoric);

                return true;
            }
            catch (PDOException $e)
            {
                return false;
            }
        }
    }