<?php 
    
    
    /**
     * unsetSessionVariables : Supprimer les variables de session error, success et errorPage
     * Elle sera appelée dans toutes les pages qui utilisent ces variables afin de les supprimer
     *
     * @return void
     */
    function unsetSessionVariables()
    {
        unset($_SESSION['error']);
        unset($_SESSION['success']);
        unset($_SESSION['errorPage']);
    }
    
    /**
     * isDateValid : Vérifie si la date est valide
     *
     * @param  mixed $date
     * @return boolean
     */
    function isDateValid($date)
    {
        $date = explode('-', $date);
        if (count($date) == 3)
        {
            if (checkdate($date[1], $date[2], $date[0]))
            {
                return true;
            }
        }
        return false;
    }

    function createValidDate($date)
    {
        $config = require 'config/config.php';
        // Vérification du format de la date
        if (isset($date) && !empty($date))
        {
            if(!isDateValid($date))
            {
                return "La date n'est pas définie"; // Erreur : La date n'est pas au bon format
            }
            $date_brq = htmlspecialchars($date);
            $date_brq = date("Y-m-d", strtotime($date_brq));
        }
        else
        {
            return "La date n'est pas dans le bon format"; // Erreur : La date n'est pas au bon format
        }

        // Vérifier si la date est dans le bon intervalle
        if (strtotime($date_brq) < strtotime($config['date_begin_brq']) || strtotime($date_brq) > strtotime($config['date_end_brq']))
        {
            return "La date n'est pas dans l'intervalle autorisé "+$date_brq; // Erreur : La date n'est pas dans l'intervalle autorisé
            
        }

        // Vérifier si le brq existe déjà
        $brqList = BrqModel::getAllBrq();
        foreach ($brqList as $brq)
        {
            if ($brq->getDateBrq() == $date_brq)
            {
                return "Le brq existe déjà"; // Erreur : Le brq existe déjà
            }
        }

        return $date_brq;
    }

?>