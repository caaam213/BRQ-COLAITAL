<?php 
    return array(
        [
            "name" => "Gérer les produits",
            "url" => self::$config["base_url"]."index.php/produits",
            "icon" => "fa-solid fa-barcode",
            "restrict_access" => false
        ], 
        [
            "name" => "Gérer les catégories",
            "url" => self::$config["base_url"]."index.php/categoriesProduits",
            "icon" => "fa-solid fa-folder-open",
            "restrict_access" => false
        ]
        ,
        [
            "name" => "Gérer les unités de mesure",
            "url" => self::$config["base_url"]."index.php/unitesMesure",
            "icon" => "fa-solid fa-calculator",
            "restrict_access" => false
        ]

        
    );