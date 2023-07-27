<?php
require_once 'models/connexionDB.php';
require_once 'classes/Utilisateur.php';
class UtilisateurModel 
{    
    /**
     * getAllUtilisateurs : Retourne tous les utilisateurs
     * @return array
     */
    public static function getAllUtilisateurs()
    {
        
        try {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->query("SELECT * FROM Utilisateur");
            $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            $utilisateursList = array();

            // Créer une liste d'Utilisateur
            foreach ($utilisateurs as $row) {
                $utilisateur = new Utilisateur(
                    $row['Id_Utilisateur'],
                    $row['nom_util'],
                    $row['prenom_util'],
                    $row['adresse_mail'],
                    $row['mdp'],
                    $row['a_acces'],
                    $row['code_role']
                );
                $utilisateursList[] = $utilisateur;
            }

            return $utilisateursList;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
            return array();
        }
    }
    
    /**
     * Retourne un utilisateur en fonction de son id s'il existe dans la base de données
     *
     * @param string $email
     * @return Utilisateur|boolean
     */
    public static function verifyIfUserExists($email)
    {
        try {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT * FROM Utilisateur WHERE adresse_mail = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!is_bool($row))
            {
                $utilisateur = new Utilisateur(
                    $row['Id_Utilisateur'],
                    $row['nom_util'],
                    $row['prenom_util'],
                    $row['adresse_mail'],
                    $row['mdp'],
                    $row['a_acces'],
                    $row['code_role']
                );
            }
            

            if (isset($utilisateur)) {
                return $utilisateur;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Retourne un utilisateur en fonction de son nom s'il existe dans la base de données
     * @param  mixed $email
     * @return Utilisateur|boolean
     */
    public static function getUserByUsername($email)
    {
        try {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT * FROM Utilisateur WHERE adresse_mail = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur) {
                return $utilisateur;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * addUtilisateur : Ajouter un utilisateur dans la base de données
     *
     * @param  mixed $utilisateur
     * @return bool
     */
    public static function addUtilisateur($utilisateur)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();

            // Création d'un utilisateur
            $stmt = $dbConnection->prepare("INSERT INTO Utilisateur (nom_util, prenom_util, adresse_mail, mdp, a_acces, code_role) VALUES (:nom_util, :prenom_util, :adresse_mail, :mdp, :a_acces, :code_role)");
            $stmt->bindParam(':nom_util', $utilisateur->getNom());
            $stmt->bindParam(':prenom_util', $utilisateur->getPrenom());
            $stmt->bindParam(':adresse_mail', $utilisateur->getAdresseMail());
            $stmt->bindParam(':mdp', $utilisateur->getMdp());
            $stmt->bindParam(':a_acces', $utilisateur->getAAcces());
            $stmt->bindParam(':code_role', $utilisateur->getCodeRole());
            $stmt->execute();

            $lastIdInserted = $dbConnection->lastInsertId(); // Récupère l'id de l'utilisateur qui vient d'être ajouté

            // Ajout dans la table Historique
            $textForHistoric = "L'utilisateur " . $utilisateur->getNom() . " " . $utilisateur->getPrenom() . " avec l'id ".$lastIdInserted." a été ajouté à la date du " . date("d/m/Y") . " à " . date("H:i:s") . ".\n";
            $stmt = $dbConnection->prepare("INSERT INTO Historique (texte_historique) VALUES (:texte_historique)");
            $stmt->bindParam(':texte_historique', $textForHistoric);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * getUtilisateurById : Récupérer un utilisateur en fonction de son id
     *
     * @param  mixed $idUtilisateur
     * @return Utilisateur|boolean
     */
    public static function getUtilisateurById($idUtilisateur)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT * FROM Utilisateur WHERE Id_Utilisateur = :idUtilisateur");
            $stmt->bindParam(':idUtilisateur', $idUtilisateur);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $utilisateur = new Utilisateur(
                    $row['Id_Utilisateur'],
                    $row['nom_util'],
                    $row['prenom_util'],
                    $row['adresse_mail'],
                    $row['mdp'],
                    $row['a_acces'],
                    $row['code_role']
                );
                return $utilisateur;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * updateAcces : Mettre à jour l'accès d'un utilisateur
     *
     * @param  mixed $acces
     * @param  mixed $idUtilisateur
     * @return boolean
     */
    public static function updateAcces($acces, $idUtilisateur)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $acces = ($acces == 0) ? 1 : 0; // Changer l'accès de l'utilisateur

            $utilisateur = UtilisateurModel::getUtilisateurById($idUtilisateur);
            $stmt = $dbConnection->prepare("UPDATE Utilisateur SET a_acces = :a_acces WHERE Id_Utilisateur = :idUtilisateur");
            $stmt->bindParam(':a_acces', $acces);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur);
            $stmt->execute();

            // Ajout dans la table Historique
            $accesText = ($acces == 0) ? "désactivé" : "activé";
            $textForHistoric = "L'accès de l'utilisateur avec l'id ".$idUtilisateur." identifié comme ".$utilisateur->getNom()." ".$utilisateur->getPrenom()." a été ".$accesText." à la date du " . date("d/m/Y") . " à " . date("H:i:s") . ".\n";
            $stmt = $dbConnection->prepare("INSERT INTO Historique (texte_historique) VALUES (:texte_historique)");
            $stmt->bindParam(':texte_historique', $textForHistoric);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la modification de l'accès de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * updatePassword : Mettre à jour le mot de passe d'un utilisateur
     *
     * @param  int $idUtilisateur
     * @param  string $password
     * @return boolean
     */
    public static function updatePassword($idUtilisateur, $password)
    {
        try
        {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("UPDATE Utilisateur SET mdp = :mdp WHERE Id_Utilisateur = :idUtilisateur");
            $stmt->bindParam(':mdp', $password);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur);
            $stmt->execute();

            $utilisateur = UtilisateurModel::getUtilisateurById($idUtilisateur);
            $textForHistoric = "Le mot de passe de l'utilisateur avec l'id ".$idUtilisateur." identifié comme ".$utilisateur->getNom()." ".$utilisateur->getPrenom()." a été modifié/réinitialisé à la date du " . date("d/m/Y") . " à " . date("H:i:s") . ".\n";
            $stmt = $dbConnection->prepare("INSERT INTO Historique (texte_historique) VALUES (:texte_historique)");
            $stmt->bindParam(':texte_historique', $textForHistoric);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la modification du mot de passe de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * deleteUser : Supprimer un utilisateur
     *
     * @param  int $idUtilisateur
     * @return boolean
     */
    public static function deleteUser($idUtilisateur)
    {
        try
        {
            // Suppression
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $utilisateur = UtilisateurModel::getUtilisateurById($idUtilisateur);
            $stmt = $dbConnection->prepare("DELETE FROM Utilisateur WHERE Id_Utilisateur = :idUtilisateur");
            $stmt->bindParam(':idUtilisateur', $idUtilisateur);
            $stmt->execute();

            // Ajout dans la table Historique
            $textForHistoric = "L'utilisateur avec l'id ".$idUtilisateur." identifié comme ".$utilisateur->getNom()." ".$utilisateur->getPrenom()." a été supprimé à la date du " . date("d/m/Y") . " à " . date("H:i:s") . ".\n";
            $stmt = $dbConnection->prepare("INSERT INTO Historique (texte_historique) VALUES (:texte_historique)");
            $stmt->bindParam(':texte_historique', $textForHistoric);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }

    

    
}