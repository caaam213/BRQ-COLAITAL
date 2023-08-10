<?php 
class ModifierProduit {
    /**
     * ModifierProduit : Classe représentant un produit modifié et les utilisateurs 
     * impliqués dans la base de données
     * Exemple de valeur : (3, 10, 2021/07/29 10:00:00)
     */
    private $idUtilisateur;
    private $idProduit;
    private $dateModif;
    
    /**
     * __construct
     *
     * @param  int $idUtilisateur
     * @param  int $idProduit
     * @param  datetime $dateModif
     * @return void
     */
    public function __construct($idUtilisateur, $idProduit, $dateHeure) {
        $this->idUtilisateur = $idUtilisateur;
        $this->idProduit = $idProduit;
        $this->dateModif = $dateHeure;
    }
    
    /**
     * getIdUtilisateur : Retourne l'id de l'utilisateur
     *
     * @return int
     */
    public function getIdUtilisateur() {
        return $this->idUtilisateur;
    }
    
    /**
     * setIdUtilisateur : Définit l'id de l'utilisateur
     *
     * @param  int $idUtilisateur
     * @return void
     */
    public function setIdUtilisateur($idUtilisateur) {
        $this->idUtilisateur = $idUtilisateur;
    }
    
    /**
     * getIdProduit : Retourne l'identifiant du produit
     *
     * @return int
     */
    public function getIdProduit() {
        return $this->idProduit;
    }
    
    /**
     * setIdProduit : Définit l'identifiant du produit
     *
     * @param  int $idProduit
     * @return void
     */
    public function setCodeProduit($idProduit) {
        $this->idProduit = $idProduit;
    }
    
    /**
     * getDateModif : Retourne la date et l'heure de la modification
     *
     * @return datetime
     */
    public function getDateModif() {
        return $this->dateModif;
    }
    
    /**
     * setDateModif : Définit la date et l'heure de la modification
     *
     * @param  datetime $dateHeure
     * @return void
     */
    public function setDateModif($dateHeure) {
        $this->dateModif = $dateHeure;
    }
}
