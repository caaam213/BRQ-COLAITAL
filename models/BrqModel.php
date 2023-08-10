<?php 
    require_once 'models/connexionDB.php'; 
    require_once 'classes/BRQ.php';

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
                $brq['Id_dernier_util_modif']);
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
}

