<?php
require_once 'models/connexionDB.php';
require_once 'classes/Utilisateur.php';
class UtilisateurModel 
{    
    /**
     * Retourne tous les utilisateurs
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
     * @param string $nom_util
     * @return Utilisateur|boolean
     */
    public static function verifyIfUserExists($nom_util)
    {
        try {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT * FROM Utilisateur WHERE nom_util = :nom_util");
            $stmt->bindParam(':nom_util', $nom_util);
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
     * @param  mixed $nom_util
     * @return Utilisateur|boolean
     */
    public static function getUserByUsername($nom_util)
    {
        try {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("SELECT * FROM Utilisateur WHERE nom_util = :nom_util");
            $stmt->bindParam(':nom_util', $nom_util);
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

    
}