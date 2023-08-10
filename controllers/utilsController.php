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

?>