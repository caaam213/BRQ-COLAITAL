<?php 
    require_once 'models/connexionDB.php'; 
    require_once 'classes/BRQ.php';
    require_once 'models/HistoriqueModel.php';

class BrqModel
{    
    /**
     * getAllBrq : Récupère tous les brqs de la base de données
     *
     * @return array : tableau de BRQ
     */
    public static function getAllBrq()
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->query("SELECT * FROM BRQ");
            $brqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $brqsList = array();
            foreach ($brqs as $brq)
            {
                $brqObj = new BRQ($brq['date_brq'], $brq['date_creation'], $brq['num_brq'], 
                $brq['date_derniere_modif'], $brq['faits_saillants'], $brq['Id_Utilisateur_cree'],
                $brq['Id_dernier_util_modif'], $brq['etat_brq']);
                $brqsList[] = $brqObj;
            }
            return $brqsList;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération des brqs : " . $e->getMessage();
            return array();
        }
    }

    public static function createBrq($brq)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("INSERT INTO BRQ (
            date_brq, 
            date_creation, 
            num_brq, 
            date_derniere_modif,
            faits_saillants, 
            Id_Utilisateur_cree, 
            Id_dernier_util_modif, 
            etat_brq) VALUES 
            (:date_brq, 
            :date_creation, 
            :num_brq, 
            :date_derniere_modif, 
            :faits_saillants, 
            :Id_Utilisateur_cree, 
            :Id_dernier_util_modif, 
            :etat_brq)");
            $stmt->bindParam(':date_brq', $brq->getDateBrq());
            $stmt->bindParam(':date_creation', $brq->getDateCreation());
            $stmt->bindParam(':num_brq', $brq->getNumBrq());
            $stmt->bindParam(':date_derniere_modif', $brq->getDateDerniereModif());
            $stmt->bindParam(':faits_saillants', $brq->getFaitsSaillants());
            $stmt->bindParam(':Id_Utilisateur_cree', $brq->getIdUtilisateurCree());
            $stmt->bindParam(':Id_dernier_util_modif', $brq->getIdDernierUtilModif());
            $stmt->bindParam(':etat_brq', $brq->getEtatBrq());
            $stmt->execute();

            $textForHistoric = "L'utilisateur avec l'id ". $_SESSION["id_util"] ." a crée le brq de la date du " . $brq->getDateBrq() . " le " . $brq->getDateCreation();
            HistoriqueModel::addToHistorique($textForHistoric);
            return true;

        }
        catch (PDOException $e)
        {
            //echo "Erreur lors de la création du brq : " . $e->getMessage();
            return false;
        }
    }

    public static function getNbrBrqByYear($year)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT COUNT(*) FROM BRQ WHERE YEAR(date_brq) = :year");
            $stmt->bindParam(':year', $year);
            $stmt->execute();
            $nbrBrq = $stmt->fetch(PDO::FETCH_ASSOC);
            return $nbrBrq['COUNT(*)'];
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération du nombre de brq : " . $e->getMessage();
            return 0;
        }
    }
}

