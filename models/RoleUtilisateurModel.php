<?php 
    require_once 'models/connexionDB.php';
    require_once 'classes/RoleUtilisateur.php';

class RoleUtilisateurModel 
{    
    /**
     * getAllRoleUtilisateurs : Récupérer tous les rôles des utilisateurs
     *
     * @return array
     */
    public static function getAllRoleUtilisateurs()
    {
        try {
            $dbConnection = ConnexionDB::getInstance()->getConnection();
            $stmt = $dbConnection->query("SELECT * FROM Role_utilisateur");
            $roleUtilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            $roleUtilisateursList = array();

            // Créer une liste d'Utilisateur
            foreach ($roleUtilisateurs as $row) {
                $roleUtilisateur = new RoleUtilisateur(
                    $row['code_role'],
                    $row['nom_role']
                );
                $roleUtilisateursList[] = $roleUtilisateur;
            }

            return $roleUtilisateursList;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
            return array();
        }
    }
}