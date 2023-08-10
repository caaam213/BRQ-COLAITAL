<?php 
class CategorieProduit {
    /**
     * CategorieProduit : Classe représentant une catégorie de produit dans la base de données
     * Exemple de valeur : (1, BEURRE, "Beurre", 5, 2)
     */

    private $idCategorie;
    private $codeCategorie;
    private $nomCategorie;
    private $idUnite;
    
    /**
     * __construct : Constructeur de la classe CategorieProduit
     * 
     * 
     * @param string $codeCategorie
     * @param string $nomCategorie
     * @param int $idUnite
     *
     * @return void
     */
    public function __construct(
        $codeCategorie,
        $nomCategorie,
        $idUnite
    ) {
        $this->codeCategorie = $codeCategorie;
        $this->nomCategorie = $nomCategorie;
        $this->idUnite = $idUnite;
    }
    
    /**
     * getIdCategorie : Retourne l'identifiant de la catégorie
     *
     * @return int
     */
    public function getIdCategorie() {
        return $this->idCategorie;
    }

    /**
     * setIdCategorie : Définit l'identifiant de la catégorie
     *
     * @param  int $idCategorie
     * @return void
     */
    public function setIdCategorie($idCategorie) {
        $this->idCategorie = $idCategorie;
    }
    
    /**
     * getCodeCategorie : Retourne le code de la catégorie
     *
     * @return string
     */
    public function getCodeCategorie() {
        return $this->codeCategorie;
    }
    
    /**
     * setCodeCategorie : Définit le code de la catégorie
     *
     * @param  string $codeCategorie
     * @return void
     */
    public function setCodeCategorie($codeCategorie) {
        $this->codeCategorie = $codeCategorie;
    }
    
    /**
     * getNomCategorie : Retourne le nom de la catégorie
     *
     * @return string
     */
    public function getNomCategorie() {
        return $this->nomCategorie;
    }
    
    /**
     * setNomCategorie
     *
     * @param  string $nomCategorie
     * @return void
     */
    public function setNomCategorie($nomCategorie) {
        $this->nomCategorie = $nomCategorie;
    }
    
    
    /**
     * getIdUnite : Retourne le code de l'unité de mesure
     *
     * @return int
     */
    public function getIdUnite() {
        return $this->idUnite;
    }
    
    /**
     * setIdUnite : Définit le code de l'unité de mesure
     *
     * @param  string $idUnite
     * @return void
     */
    public function setIdUnite($idUnite) {
        $this->idUnite = $idUnite;
    }
}
