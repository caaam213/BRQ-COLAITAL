    
    <?php 
    class UniteMesure {
        /**
         * UniteMesure : Classe représentant une unité de mesure dans la base de données
         * Exemple de valeur : (U0405, "kg", 4)
         */
        
        private $idUnite;
        private $codeUnite;
        private $uniteNom;
        private $multiplicateur;
        
        /**
         * __construct : Constructeur de la classe UniteMesure
         *
         * 
         * @param  string $codeUnite
         * @param  string $uniteNom
         * @param  float $multiplicateur
         * @return void
         */
        public function __construct($codeUnite, $uniteNom, $multiplicateur=1) {
            $this->codeUnite = $codeUnite;
            $this->uniteNom = $uniteNom;
            $this->multiplicateur = $multiplicateur;
        }

        /**
         * getIdUnite : Retourne l'identifiant de l'unité de mesure
         *
         * @return int
         */
        public function getIdUnite() {
            return $this->idUnite;
        }

        /**
         * setIdUnite : Définit l'identifiant de l'unité de mesure
         *
         * @param  int $idUnite
         * @return void
         */
        public function setIdUnite($idUnite) {
            $this->idUnite = $idUnite;
        }

            
        /**
         * getCodeUnite : Retourne le code de l'unité de mesure
         *
         * @return string
         */
        public function getCodeUnite() {
            return $this->codeUnite;
        }
            
        /**
         * setCodeUnite : Définit le code de l'unité de mesure
         *
         * @param  string $codeUnite
         * @return void
         */
        public function setCodeUnite($codeUnite) {
            $this->codeUnite = $codeUnite;
        }
            
        /**
         * getUniteNom : Retourne le nom de l'unité de mesure
         *
         * @return string
         */
        public function getUniteNom() {
            return $this->uniteNom;
        }
            
        /**
         * setUniteNom : Définit le nom de l'unité de mesure
         *
         * @param  string $uniteNom
         * @return void
         */
        public function setUniteNom($uniteNom) {
            $this->uniteNom = $uniteNom;
        }
            
        /**
         * getMultiplicateur : Retourne le multiplicateur de l'unité de mesure
         *
         * @return float
         */
        public function getMultiplicateur() {
            return $this->multiplicateur;
        }
            
        /**
         * setMultiplicateur : Définit le multiplicateur de l'unité de mesure
         *
         * @param  float $multiplicateur
         * @return void
         */
        public function setMultiplicateur($multiplicateur) {
            $this->multiplicateur = $multiplicateur;
        }
    }
    