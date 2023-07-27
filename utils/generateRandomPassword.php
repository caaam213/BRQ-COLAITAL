    
    <?php

    /**
     * generateRandomPassword : génère un mot de passe aléatoire
     *
     * @return string
     */
    function generateRandomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890/.*-+@';
        $pass = array(); 

        $alphaLength = strlen($alphabet) - 1; // longueur de l'alphabet
        $lengthRandomPassword = random_int(8,12); // longueur du mot de passe

        for ($i = 0; $i < $lengthRandomPassword; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); // retourne le mot de passe
    }

?>