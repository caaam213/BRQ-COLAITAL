<?php

class BRQ {
    /**
     * BRQ : Classe représentant un BRQ dans la base de données
     * Exemple de valeur : (2023-07-25, 2023-07-27, 28, 2021-07-25, "Faits saillants", 1, 1)
     */
    private $date_brq;
    private $date_creation;
    private $num_brq;
    private $date_derniere_modif;
    private $faits_saillants;
    private $Id_Utilisateur_cree;
    private $Id_dernier_util_modif;

       
    /**
     * __construct : Constructeur de la classe BRQ
     *
     * @param  datetime $date_brq
     * @param  datetime $date_creation
     * @param  int $num_brq
     * @param  datetime $date_derniere_modif
     * @param  string $faits_saillants
     * @return void
     */
    public function __construct($date_brq, $date_creation, $num_brq, $date_derniere_modif, $faits_saillants, 
    $Id_Utilisateur_cree, $Id_dernier_util_modif) {
        $this->date_brq = $date_brq;
        $this->date_creation = $date_creation;
        $this->num_brq = $num_brq;
        $this->date_derniere_modif = $date_derniere_modif;
        $this->faits_saillants = $faits_saillants;
        $this->Id_Utilisateur_cree = $Id_Utilisateur_cree;
        $this->Id_dernier_util_modif = $Id_dernier_util_modif;
    }
    
        
    /**
     * getDateBrq : Retourne la date du BRQ
     *
     * @return datetime
     */
    public function getDateBrq() {
        return $this->date_brq;
    }
    
    /**
     * setDateBrq : Définit la date du BRQ
     *
     * @param  datetime $date_brq
     * @return void
     */
    public function setDateBrq($date_brq) {
        $this->date_brq = $date_brq;
    }

        
    /**
     * getDateCreation : Retourne la date de création du BRQ
     *
     * @return datetime
     */
    public function getDateCreation() {
        return $this->date_creation;
    }
    
    /**
     * setDateCreation : Définit la date de création du BRQ
     *
     * @param  datetime $date_creation
     * @return void
     */
    public function setDateCreation($date_creation) {
        $this->date_creation = $date_creation;
    }

        
    /**
     * getNumBrq : Retourne le numéro du BRQ
     *
     * @return int
     */
    public function getNumBrq() {
        return $this->num_brq;
    }
    
    /**
     * setNumBrq : Définit le numéro du BRQ
     *
     * @param  int $num_brq
     * @return void
     */
    public function setNumBrq($num_brq) {
        $this->num_brq = $num_brq;
    }
    
    
    /**
     * getDateDerniereModif : Retourne la date de dernière modification du BRQ
     *
     * @return datetime
     */
    public function getDateDerniereModif() {
        return $this->date_derniere_modif;
    }
    
    /**
     * setDateDerniereModif : Définit la date de dernière modification du BRQ
     *
     * @param  datetime $date_derniere_modif
     * @return void
     */
    public function setDateDerniereModif($date_derniere_modif) {
        $this->date_derniere_modif = $date_derniere_modif;
    }

        
    /**
     * getFaitsSaillants : Retourne les faits saillants du BRQ
     *
     * @return string
     */
    public function getFaitsSaillants() {
        return $this->faits_saillants;
    }
    
    /**
     * setFaitsSaillants : Définit les faits saillants du BRQ
     *
     * @param  string $faits_saillants
     * @return void
     */
    public function setFaitsSaillants($faits_saillants) {
        $this->faits_saillants = $faits_saillants;
    }

        
    /**
     * getIdUtilisateurCree : Retourne l'identifiant de l'utilisateur qui a créé le BRQ
     *
     * @return int
     */
    public function getIdUtilisateurCree() {
        return $this->Id_Utilisateur_cree;
    }
    
    /**
     * setIdUtilisateurCree : Définit l'identifiant de l'utilisateur qui a créé le BRQ
     *
     * @param  int $Id_Utilisateur_cree
     * @return void
     */
    public function setIdUtilisateurCree($Id_Utilisateur_cree) {
        $this->Id_Utilisateur_cree = $Id_Utilisateur_cree;
    }

        
    /**
     * getIdDernierUtilModif : Retourne l'identifiant de l'utilisateur qui a modifié le BRQ pour la dernière fois
     *
     * @return int
     */
    public function getIdDernierUtilModif() {
        return $this->Id_dernier_util_modif;
    }
    
    /**
     * setIdDernierUtilModif : Définit l'identifiant de l'utilisateur qui a modifié le BRQ pour la dernière fois
     *
     * @param  int $Id_dernier_util_modif
     * @return void
     */
    public function setIdDernierUtilModif($Id_dernier_util_modif) {
        $this->Id_dernier_util_modif = $Id_dernier_util_modif;
    }
}

?>
