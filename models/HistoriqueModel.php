<?php
require_once 'models/connexionDB.php';

class HistoriqueModel
{    
    /**
     * addToHistorique : Ajouter un texte à l'historique représentant une action d'un utilisateur
     *
     * @param  string $textForHistoric
     * @return void
     */
    public static function addToHistorique($textForHistoric)
    {
        $dbConnection = ConnexionDB::getInstance()->getConnection();
        $stmt = $dbConnection->prepare("INSERT INTO Historique (texte_historique) VALUES (:texte_historique)");
        $stmt->bindParam(':texte_historique', $textForHistoric);
        $stmt->execute();
    }
}