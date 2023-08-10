<?php 


class ComposerProduits {
    /**
     * ComposerProduits : Classe représentant un produit composé d'un ou plusieurs produits qui sont des 
     * matières premières dans la base de données
     * Exemple de valeur : (1, 20)
     * Exemple de valeur : (1, 30)
     */
    private $idProduit;
    private $idProduitMatierePremiere;
    
    /**
     * __construct : Constructeur de la classe ComposerProduits
     *
     * @param  int $idProduit
     * @param  int $idProduitMatierePremiere
     * @return void
     */
    public function __construct($idProduit, $idProduitMatierePremiere) {
        $this->idProduit = $idProduit;
        $this->idProduitMatierePremiere = $idProduitMatierePremiere;
    }
    
    /**
     * getCodeProduit : Retourne l'id du du produit
     *
     * @return int
     */
    public function getIdProduit() {
        return $this->idProduit;
    }
    
    /**
     * setCodeProduit : Définit l'id du produit
     *
     * @param  int $idProduit
     * @return void
     */
    public function setIdProduit($idProduit) {
        $this->idProduit = $idProduit;
    }
    
    /**
     * getIdProduitMatierePremiere : Retourne l'id du produit matière première
     *
     * @return int
     */
    public function getIdProduitMatierePremiere() {
        return $this->idProduitMatierePremiere;
    }
    
    /**
     * setCodeProduitMatierePremiere : Définit l'id du produit matière première
     *
     * @param  int $codeProduitMatierePremiere
     * @return void
     */
    public function setIdProduitMatierePremiere($idProduitMatierePremiere) {
        $this->idProduitMatierePremiere = $idProduitMatierePremiere;
    }
}
