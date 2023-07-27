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

?>