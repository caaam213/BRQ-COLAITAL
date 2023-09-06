<?php 
    return array(
        [
            "name" => "Ajouter un utilisateur",
            "url" => self::$config["base_url"]."index.php/utilisateurs/addUser",
            "icon" => "fa-solid fa-user-plus",
            "restrict_access" => true, 
            "roleToAccess" => ["ADMIN"]
        ], 
        
        [
            "name" => "Gérer les utilisateurs",
            "url" => self::$config["base_url"]."index.php/utilisateurs/allUsers",
            "icon" => "fa-solid fa-users",
            "restrict_access" => true, 
            "roleToAccess" => ["ADMIN", "CONTROLEUR"]
        ],

        [
            "name" => "Modifier mon mot de passe",
            "url" => self::$config["base_url"]."index.php/utilisateurs/updatePassword",
            "icon" => "fa-solid fa-user-pen",
            "restrict_access" => false, 
            "modal_name_file" => "modal_update_password.php"
        ], 
    );