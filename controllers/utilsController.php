<?php 
    
    $configErrors = require 'config/config_errors.php';
    $config = require 'config/config.php';
    
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
        
        global $config;
        // Vérification du format de la date
        if (isset($date) && !empty($date))
        {
            $date_brq = htmlspecialchars($date);
            $date_brq = date("Y-m-d", strtotime($date_brq));
            if(!isDateValid($date_brq))
            {
                return "La date n'est pas dans le bon format"; // Erreur : La date n'est pas au bon format
            }
            
        }
        else
        {
            return "La date n'est pas définie"; // Erreur : La date n'est pas au bon format
        }

        // Vérifier si la date est dans le bon intervalle
        if (strtotime($date_brq) < strtotime($config['date_begin_brq']))
        {
            return "La date est inférieure à l'intervalle autorisé ".$date_brq; // Erreur : La date n'est pas dans l'intervalle autorisé
            
        }

        if(strtotime($date_brq) > strtotime($config['date_end_brq']))
        {
            return "La date est supérieure à l'intervalle autorisé ".$date_brq; // Erreur : La date n'est pas dans l'intervalle autorisé
        }

        return $date_brq;
    }
    
    /**
     * verifyAccesRoleCode : Fonction qui va vérifier si l'utilisateur a les droits pour accéder à la page
     *
     * @param  array $tabRoleCode
     * @return void
     */
    function verifyAccesRoleCode($tabRoleCode=array())
    {
        global $configErrors, $config;
        $errorLink = "index.php/error";

        // Vérifier si l'utilisateur est connecté
        if(!isset($_SESSION['code_role']))
        {
            $_SESSION['errorPage'] = $configErrors['1003'];
            header('Location: '.$config['base_url'].$errorLink);
            exit();
        }

        // Vérifier si l'utilisateur a les droits pour accéder à cette page
        if(!in_array($_SESSION['code_role'], $tabRoleCode) && !empty($tabRoleCode))
        {
            $_SESSION['errorPage'] = $configErrors['1004'];
            header('Location: '.$config["base_url"].$errorLink);
            exit();
        }
    }

?>