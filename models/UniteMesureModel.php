<?php 
    require_once 'models/connexionDB.php';
    require_once 'classes/UniteMesure.php';
    require_once 'models/HistoriqueModel.php';

class UniteMesureModel
{    
    /**
     * getAllUnitesMesure : Récupère toutes les unités de mesure
     *
     * @return array : La liste des unités de mesure
     */
    public static function getAllUnitesMesure()
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->query("SELECT * FROM Unite_mesure");
            $unitesMesure = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $unitesMesureList = array();
            foreach ($unitesMesure as $uniteMesure)
            {
                $uniteMesureObj = new UniteMesure($uniteMesure['code_unite'], $uniteMesure['unite_nom'], $uniteMesure['multiplicateur']);
                $uniteMesureObj->setIdUnite($uniteMesure['id_unite_mesure']);
                $unitesMesureList[] = $uniteMesureObj;
            }

            return $unitesMesureList;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération des unités de mesure : " . $e->getMessage();
            return array();
        }
    }
    
    /**
     * addUniteMesure : Ajoute une unité de mesure
     *
     * @param  UniteMesure $uniteMesure : L'unité de mesure à ajouter
     * @return boolean : True si l'ajout a réussi, false sinon
     */
    public static function addUniteMesure($uniteMesure)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("INSERT INTO Unite_mesure (code_unite, unite_nom, multiplicateur) VALUES (:codeUnite, :uniteNom, :multiplicateur)");
            $stmt->bindParam(':codeUnite', $uniteMesure->getCodeUnite());
            $stmt->bindParam(':uniteNom', $uniteMesure->getUniteNom());
            $stmt->bindParam(':multiplicateur', $uniteMesure->getMultiplicateur());
            $stmt->execute();

            // Ajout dans la table Historique
            $textForHistoric = "L'unité de mesure " . $uniteMesure->getCodeUnite() . " " ."ajouté à la date du " . date("d/m/Y") . " à " . date("H:i:s") . " par l'utilisateur ".$_SESSION["nom_util"]." ".$_SESSION["prenom_util"].".\n";
            HistoriqueModel::addToHistorique($textForHistoric);
            return true;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de l'ajout de l'unité de mesure : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * getUniteMesureById : Récupère une unité de mesure par son id
     *
     * @param  int $idUniteMesure
     * @return UniteMesure|null
     */
    public static function getUniteMesureById($idUniteMesure)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT * FROM Unite_mesure WHERE id_unite_mesure = :idUniteMesure");
            $stmt->bindParam(':idUniteMesure', $idUniteMesure);
            $stmt->execute();
            $uniteMesure = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$uniteMesure) {
                return null;
            }
            
            $uniteMesure =  new UniteMesure($uniteMesure['code_unite'], $uniteMesure['unite_nom'], $uniteMesure['multiplicateur']);
            $uniteMesure->setIdUnite($idUniteMesure);
            return $uniteMesure;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la récupération de l'unité de mesure : " . $e->getMessage();
            return null;
        }
    }
    
    /**
     * modifyUniteMesure : Modifie une unité de mesure
     *
     * @param UniteMesure $uniteMesure
     * @return boolean : True si la modification a réussi, false sinon
     */
    public static function modifyUniteMesure($uniteMesure)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("UPDATE Unite_mesure SET code_unite = :codeUnite, unite_nom = :uniteNom, multiplicateur = :multiplicateur WHERE id_unite_mesure = :idUniteMesure");
            $stmt->bindParam(':idUniteMesure', $uniteMesure->getIdUnite());
            $stmt->bindParam(':codeUnite', $uniteMesure->getCodeUnite());
            $stmt->bindParam(':uniteNom', $uniteMesure->getUniteNom());
            $stmt->bindParam(':multiplicateur', $uniteMesure->getMultiplicateur());
            $stmt->execute();

            // Ajout dans la table Historique
            $textForHistoric = "L'unité de mesure " . $uniteMesure->getCodeUnite() . " " ."modifié à la date du " . date("d/m/Y") . " à " . date("H:i:s") . " par l'utilisateur ".$_SESSION["nom_util"]." ".$_SESSION["prenom_util"].".\n";
            HistoriqueModel::addToHistorique($textForHistoric);
            return true;
        }
        catch (PDOException $e)
        {
            echo "Erreur lors de la modification de l'unité de mesure : " . $e->getMessage();
            return false;
        }
    }

}