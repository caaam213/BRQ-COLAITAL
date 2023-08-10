<?php 
    return array(
        [
            "name" => "Interface BRQ",
            "url" => self::$config["base_url"]."index.php/brq",
            "icon" => "fa-solid fa-file-contract",
            "restrict_access" => false
        ], 
        [
            "name" => "Interface Produits",
            "url" => self::$config["base_url"]."index.php/produitsAssocies",
            "icon" => "fa-solid fa-cart-plus",
            "restrict_access" => false,
        ],
        [
            "name" => "Interface Utilisateurs",
            "url" => self::$config["base_url"]."index.php/utilisateurs",
            "icon" => "fa-solid fa-user",
            "restrict_access" => false, 
        ], 
        [
            "name" => "Déconnexion",
            "url" => self::$config["base_url"]."index.php/login/deconnexion",
            "icon" => "fa-solid fa-right-from-bracket",
            "restrict_access" => false
        ],

    );
