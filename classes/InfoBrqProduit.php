<?php

class InfoBrqProduit {
    private $idInfoBrqProduit;
    private $realisation;
    private $objectif;
    private $stockFinJournee;
    private $quantiteVente;
    private $valeurVente;
    private $couvertureJour;
    private $idProduit;
    private $dateBrq;
    
    /**
     * __construct
     * 
     * Constructeur de la classe InfoBrqProduit
     * 
     * @param int $realisation
     * @param int $objectif
     * @param int $stockFinJournee
     * @param int $quantiteVente
     * @param float $valeurVente
     * @param float $couvertureJour
     * @param int $idProduit
     * @param string $dateBrq
     *
     * @return void
     */
    public function __construct(
        $realisation,
        $objectif,
        $stockFinJournee,
        $quantiteVente,
        $valeurVente,
        $couvertureJour,
        $idProduit,
        $dateBrq
    ) {
        $this->realisation = $realisation;
        $this->objectif = $objectif;
        $this->stockFinJournee = $stockFinJournee;
        $this->quantiteVente = $quantiteVente;
        $this->valeurVente = $valeurVente;
        $this->couvertureJour = $couvertureJour;
        $this->idProduit = $idProduit;
        $this->dateBrq = $dateBrq;
    }

    /**
     * getIdInfoBrqProduit : Retourne l'ID de l'info BRQ produit
     *
     * @return int
     */
    public function getIdInfoBrqProduit() {
        return $this->idInfoBrqProduit;
    }

    /**
     * setIdInfoBrqProduit : Définit l'ID de l'info BRQ produit
     *
     * @param int $idInfoBrqProduit
     * @return void
     */
    public function setIdInfoBrqProduit($idInfoBrqProduit) {
        $this->idInfoBrqProduit = $idInfoBrqProduit;
    }

    /**
     * getRealisation : Retourne le montant de la réalisation
     *
     * @return int
     */
    public function getRealisation() {
        return $this->realisation;
    }

    /**
     * setRealisation : Définit le montant de la réalisation
     *
     * @param int $realisation
     * @return void
     */
    public function setRealisation($realisation) {
        $this->realisation = $realisation;
    }

    /**
     * getObjectif : Retourne le montant de l'objectif
     *
     * @return int
     */
    public function getObjectif() {
        return $this->objectif;
    }

    /**
     * setObjectif : Définit le montant de l'objectif
     *
     * @param int $objectif
     * @return void
     */
    public function setObjectif($objectif) {
        $this->objectif = $objectif;
    }

    /**
     * getStockFinJournee : Retourne le stock final du produit en fin de journée
     *
     * @return int
     */
    public function getStockFinJournee() {
        return $this->stockFinJournee;
    }

    /**
     * setStockFinJournee : Définit le stock final du produit en fin de journée
     *
     * @param int $stockFinJournee
     * @return void
     */
    public function setStockFinJournee($stockFinJournee) {
        $this->stockFinJournee = $stockFinJournee;
    }

    /**
     * getQuantiteVente : Retourne la quantité de vente du produit
     *
     * @return int
     */
    public function getQuantiteVente() {
        return $this->quantiteVente;
    }

    /**
     * setQuantiteVente : Définit la quantité de vente du produit
     *
     * @param int $quantiteVente
     * @return void
     */
    public function setQuantiteVente($quantiteVente) {
        $this->quantiteVente = $quantiteVente;
    }

    /**
     * getValeurVente : Retourne la valeur de vente du produit
     *
     * @return float
     */
    public function getValeurVente() {
        return $this->valeurVente;
    }

    /**
     * setValeurVente : Définit la valeur de vente du produit
     *
     * @param float $valeurVente
     * @return void
     */
    public function setValeurVente($valeurVente) {
        $this->valeurVente = $valeurVente;
    }

    /**
     * getCouvertureJour : Retourne la couverture journalière du produit
     *
     * @return float
     */
    public function getCouvertureJour() {
        return $this->couvertureJour;
    }

    /**
     * setCouvertureJour : Définit la couverture journalière du produit
     *
     * @param float $couvertureJour
     * @return void
     */
    public function setCouvertureJour($couvertureJour) {
        $this->couvertureJour = $couvertureJour;
    }

    /**
     * getIdProduit : Retourne l'ID du produit associé à l'info
     *
     * @return int
     */
    public function getIdProduit() {
        return $this->idProduit;
    }

    /**
     * setIdProduit : Définit l'ID du produit associé à l'info
     *
     * @param int $idProduit
     * @return void
     */
    public function setIdProduit($idProduit) {
        $this->idProduit = $idProduit;
    }

    /**
     * getDateBrq : Retourne la date BRQ associée à l'info
     *
     * @return string
     */
    public function getDateBrq() {
        return $this->dateBrq;
    }

    /**
     * setDateBrq : Définit la date BRQ associée à l'info
     *
     * @param string $dateBrq
     * @return void
     */
    public function setDateBrq($dateBrq) {
        $this->dateBrq = $dateBrq;
    }
}
