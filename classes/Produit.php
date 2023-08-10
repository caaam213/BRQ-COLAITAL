    
    <?php 
    class Produit {
        /**
         * Produit : Classe représentant un produit dans la base de données
         * Exemple de valeur : (1, P0101, "Beurre doux", "Produit fini", 100000, 2023/07/29, null, 6, 1)
         * Exemple de valeur : (2, P0102, "lait 26%", "Matière premiere", 200000, 2023/07/29, 5, null, 3)
         */

        private $idProduit;
        private $codeProduit;
        private $nomProduit;
        private $typeProduit;
        private $objectif;
        private $dateCreation;
        private $idUnite;
        private $idCategorie;
        private $idUtilisateur;
        
        /**
         * __construct : Constructeur de la classe Produit
         *
         * @param  string $codeProduit
         * @param  string $nomProduit
         * @param  string $typeProduit
         * @param  int $objectif
         * @param  datetime $dateCreation
         * @param  int $idUnite
         * @param  int|null $idCategorie
         * @param  int $idUtilisateur
         * @return void
         */
        public function __construct($codeProduit, $nomProduit, $typeProduit, $objectif, $dateCreation,
        $idUnite, $idCategorie, $idUtilisateur) {
            $this->codeProduit = $codeProduit;
            $this->nomProduit = $nomProduit;
            $this->typeProduit = $typeProduit;
            $this->objectif = $objectif;
            $this->dateCreation = $dateCreation;
            $this->idUnite = $idUnite;
            $this->idCategorie = $idCategorie;
            $this->idUtilisateur = $idUtilisateur;
        }

        /**
         * getIdProduit : Retourne l'id du produit
         *
         * @return int
         */
        public function getIdProduit() {
            return $this->idProduit;
        }

        /**
         * setIdProduit : Définit l'id du produit
         *
         * @param  int $idProduit
         * @return void
         */
        public function setIdProduit($idProduit) {
            $this->idProduit = $idProduit;
        }
            
        /**
         * getCodeProduit : Retourne le code du produit
         *
         * @return string
         */
        public function getCodeProduit() {
            return $this->codeProduit;
        }
            
        /**
         * setCodeProduit : Définit le code du produit
         *
         * @param  string $codeProduit
         * @return void
         */
        public function setCodeProduit($codeProduit) {
            $this->codeProduit = $codeProduit;
        }
            
        /**
         * getNomProduit : Retourne le nom du produit
         *
         * @return string
         */
        public function getNomProduit() {
            return $this->nomProduit;
        }
            
        /**
         * setNomProduit : Définit le nom du produit
         *
         * @param  string $nomProduit
         * @return void
         */
        public function setNomProduit($nomProduit) {
            $this->nomProduit = $nomProduit;
        }
            
        /**
         * getTypeProduit : Retourne le type du produit
         *
         * @return string
         */
        public function getTypeProduit() {
            return $this->typeProduit;
        }
            
        /**
         * setTypeProduit : Définit le type du produit
         *
         * @param  string $typeProduit
         * @return void
         */
        public function setTypeProduit($typeProduit) {
            $this->typeProduit = $typeProduit;
        }
            
        /**
         * getObjectif : Retourne l'objectif du produit
         *
         * @return int
         */
        public function getObjectif() {
            return $this->objectif;
        }
            
        /**
         * setObjectif : Définit l'objectif du produit
         *
         * @param  int $objectif
         * @return void
         */
        public function setObjectif($objectif) {
            $this->objectif = $objectif;
        }
            
        /**
         * getDateCreation : Retourne la date de création du produit
         *
         * @return datetime
         */
        public function getDateCreation() {
            return $this->dateCreation;
        }
            
        /**
         * setDateCreation : Définit la date de création du produit
         *
         * @param  datetime $dateCreation
         * @return void
         */
        public function setDateCreation($dateCreation) {
            $this->dateCreation = $dateCreation;
        }
        
            
        /**
         * getIdUnite : Retourne le code de l'unité du produit
         *
         * @return string
         */
        public function getIdUnite() {
            return $this->idUnite;
        }
            
        /**
         * setidUnite : Définit le code de l'unité du produit
         *
         * @param  string $idUnite
         * @return void
         */
        public function setidUnite($idUnite) {
            $this->idUnite = $idUnite;
        }
            
        /**
         * getIdCategorie : Retourne le code de la catégorie du produit
         *
         * @return int
         */
        public function getIdCategorie() {
            return $this->idCategorie;
        }
            
        /**
         * setIdCategorie : Définit le code de la catégorie du produit
         *
         * @param  int $idCategorie
         * @return void
         */
        public function setIdCategorie($idCategorie) {
            $this->idCategorie = $idCategorie;
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
         * setIdUtilisateur
         *
         * @param  int $idUtilisateur
         * @return void
         */
        public function setIdUtilisateur($idUtilisateur) {
            $this->idUtilisateur = $idUtilisateur;
        }
    }
    