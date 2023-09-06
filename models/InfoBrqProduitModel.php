<?php 
    require_once 'models/connexionDB.php'; 
    require_once 'classes/InfoBrqProduit.php';
    require_once 'models/HistoriqueModel.php';

    class InfoBrqProduitModel
    {        
        /**
         * addInfoBrqProduit : Ajoute une info BRQ produit dans la base de données
         *
         * @param  mixed $info_brq_produit
         * @return boolean
         */
        public static function addInfoBrqProduit($info_brq_produit)
        {
            try
            {
                $dbConnection = ConnexionDB::getInstance()->getConnection();
                $stmt = $dbConnection->prepare("INSERT INTO Info_Brq_Produit 
                (realisation, objectif, stock_fin_journee, quantite_vente, valeur_vente, couverture_jour,
                id_produit, date_brq, perte_prod, stock_debut) VALUES (:realisation, :objectif, :stock_fin_journee, :quantite_vente, :valeur_vente, :couverture_jour, 
                :id_produit, :date_brq, :perte_prod, :stock_debut)");
                $stmt->bindParam(':realisation', $info_brq_produit->getRealisation());
                $stmt->bindParam(':objectif', $info_brq_produit->getObjectif());
                $stmt->bindParam(':stock_fin_journee', $info_brq_produit->getStockFinJournee());
                $stmt->bindParam(':quantite_vente', $info_brq_produit->getQuantiteVente());
                $stmt->bindParam(':valeur_vente', $info_brq_produit->getValeurVente());
                $stmt->bindParam(':couverture_jour', $info_brq_produit->getCouvertureJour());
                $stmt->bindParam(':id_produit', $info_brq_produit->getIdProduit());
                $stmt->bindParam(':date_brq', $info_brq_produit->getDateBrq());
                $stmt->bindParam('perte_prod', $info_brq_produit->getPerteProd());
                $stmt->bindParam('stock_debut', $info_brq_produit->getStockDebut());
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
        
        /**
         * addInfosBrqProduits : Ajoute des infos BRQ produits dans la base de données
         *
         * @param  mixed $infosProduits
         * @return boolean
         */
        public static function addInfosBrqProduits($infosProduits)
        {
            try
            {
                $dbConnection = ConnexionDB::getInstance()->getConnection();
                $stmt = $dbConnection->prepare("INSERT INTO Info_Brq_Produit 
                (realisation, objectif, stock_fin_journee, quantite_vente, valeur_vente, couverture_jour,
                id_produit, date_brq, perte_prod, stock_debut) VALUES (:realisation, :objectif, :stock_fin_journee, :quantite_vente, 
                :valeur_vente, :couverture_jour, :id_produit, :date_brq, :perte_prod, :stock_debut)");

                $stmt->bindParam(':realisation', $realisation);
                $stmt->bindParam(':objectif', $objectif);
                $stmt->bindParam(':stock_fin_journee', $stock_fin_journee);
                $stmt->bindParam(':quantite_vente', $quantite_vente);
                $stmt->bindParam(':valeur_vente', $valeur_vente);
                $stmt->bindParam(':couverture_jour', $couverture_jour);
                $stmt->bindParam(':id_produit', $id_produit);
                $stmt->bindParam(':date_brq', $date_brq);
                $stmt->bindParam('perte_prod', $perte_prod);
                $stmt->bindParam('stock_debut', $stock_debut);

                foreach ($infosProduits as $infoProduit)
                {
                    $realisation = $infoProduit->getRealisation();
                    $objectif = $infoProduit->getObjectif();
                    $stock_fin_journee = $infoProduit->getStockFinJournee();
                    $quantite_vente = $infoProduit->getQuantiteVente();
                    $valeur_vente = $infoProduit->getValeurVente();
                    $couverture_jour = $infoProduit->getCouvertureJour();
                    $id_produit = $infoProduit->getIdProduit();
                    $date_brq = $infoProduit->getDateBrq();
                    $perte_prod = $infoProduit->getPerteProd();
                    $stock_debut = $infoProduit->getStockDebut();
                    $stmt->execute();
                }

                $textForHistoric = "L'utilisateur avec l'id " . $_SESSION['id_util'] . " a ajouté des infos BRQ produits du BRQ du " . $date_brq;
                HistoriqueModel::addToHistorique($textForHistoric);

                return true;
            }
            catch (PDOException $e)
            {
                return false;
            }
        }
        
        /**
         * getInfosBrqProduitsByDate : Récupère les infos BRQ produits d'un BRQ par sa date
         *
         * @param  mixed $date
         * @return array
         */
        public static function getInfosBrqProduitsByDate($date)
        {
            try
            {
                $dbConnection = ConnexionDB::getInstance()->getConnection();
                $stmt = $dbConnection->prepare("SELECT * FROM Info_brq_produit WHERE date_brq = :date");
                $stmt->bindParam(':date', $date);
                $stmt->execute();
                $infosBrqProduits = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $infosBrqProduitsByProduct = array();
                foreach ($infosBrqProduits as $infoBrqProduit)
                {
                    $infoProduit = new InfoBrqProduit(
                    $infoBrqProduit['realisation'], 
                    $infoBrqProduit['objectif'], 
                    $infoBrqProduit['stock_fin_journee'], 
                    $infoBrqProduit['quantite_vente'], 
                    $infoBrqProduit['valeur_vente'], 
                    $infoBrqProduit['couverture_jour'],
                    $infoBrqProduit['id_produit'],
                    $infoBrqProduit['date_brq'],
                    $infoBrqProduit['perte_prod'],
                    $infoBrqProduit['stock_debut']
                    );

                    $infosBrqProduitsByProduct[$infoProduit->getIdProduit()] = $infoProduit;
                }
                return $infosBrqProduitsByProduct;
            }
            catch (PDOException $e)
            {
                echo "Erreur lors de la récupération des infos brq produits : " . $e->getMessage();
                return array();
            }
        }
        
        /**
         * updateInfosBrqProduit : Modifie les infos BRQ produit dans la base de données
         *
         * @param  mixed $infosProduits
         * @return boolean
         */
        public static function updateInfosBrqProduit($infosProduits)
        {
            try
            {
                $dbConnection = ConnexionDB::getInstance()->getConnection();
                $stmt = $dbConnection->prepare("UPDATE Info_Brq_Produit SET realisation = :realisation, objectif = :objectif, stock_fin_journee = :stock_fin_journee,
                quantite_vente = :quantite_vente, valeur_vente = :valeur_vente, couverture_jour = :couverture_jour, perte_prod = :perte_prod, stock_debut = :stock_debut WHERE 
                id_produit = :id_produit AND date_brq = :date_brq
                ");
                $stmt->bindParam(':realisation', $realisation);
                $stmt->bindParam(':objectif', $objectif);
                $stmt->bindParam(':stock_fin_journee', $stock_fin_journee);
                $stmt->bindParam(':quantite_vente', $quantite_vente);
                $stmt->bindParam(':valeur_vente', $valeur_vente);
                $stmt->bindParam(':couverture_jour', $couverture_jour);
                $stmt->bindParam(':id_produit', $id_produit);
                $stmt->bindParam(':date_brq', $date_brq);
                $stmt->bindParam('perte_prod', $perte_prod);
                $stmt->bindParam('stock_debut', $stock_debut);

                foreach ($infosProduits as $infoProduit)
                {
                    $realisation = $infoProduit->getRealisation();
                    $objectif = $infoProduit->getObjectif();
                    $stock_fin_journee = $infoProduit->getStockFinJournee();
                    $quantite_vente = $infoProduit->getQuantiteVente();
                    $valeur_vente = $infoProduit->getValeurVente();
                    $couverture_jour = $infoProduit->getCouvertureJour();
                    $id_produit = $infoProduit->getIdProduit();
                    $date_brq = $infoProduit->getDateBrq();
                    $perte_prod = $infoProduit->getPerteProd();
                    $stock_debut = $infoProduit->getStockDebut();
                    $stmt->execute();
                }

                $textForHistoric = "L'utilisateur avec l'id " . $_SESSION['id_util'] . " a modifié des infos BRQ produits
                du BRQ du " . $date_brq;
                HistoriqueModel::addToHistorique($textForHistoric);
                return true;
            }
            catch (PDOException $e)
            {
                echo "Erreur lors de la modification des informations des produits : " . $e->getMessage();
                return false;
            }


        }
        
    }