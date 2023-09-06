<?php 
class ProduitPerte {

    /**
        * ProduitPerte : Classe représentant un produit qui subit une perte en production et distribution
    */
    private $codeProduitPerte;
    private $intitule;

    /**
     * Constructeur de la classe ProduitPerte
     * @param string $codeProduitPerte Le code du produit perte
     * @param string|null $intitule L'intitulé du produit perte (peut être null)
     */
    public function __construct($codeProduitPerte, $intitule = null) {
        $this->codeProduitPerte = $codeProduitPerte;
        $this->intitule = $intitule;
    }

    /**
     * Getter pour le code du produit perte
     * @return string
     */
    public function getCodeProduitPerte() {
        return $this->codeProduitPerte;
    }

    /**
     * Setter pour le code du produit perte
     * @param string $codeProduitPerte
     */
    public function setCodeProduitPerte($codeProduitPerte) {
        $this->codeProduitPerte = $codeProduitPerte;
    }

    /**
     * Getter pour l'intitulé du produit perte
     * @return string|null
     */
    public function getIntitule() {
        return $this->intitule;
    }

    /**
     * Setter pour l'intitulé du produit perte
     * @param string|null $intitule
     */
    public function setIntitule($intitule) {
        $this->intitule = $intitule;
    }
}
