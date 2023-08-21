<?php

require_once 'utilsController.php';
require_once 'models/BrqModel.php';
require_once 'models/ProduitModel.php';
require_once 'models/CategorieProduitModel.php';
require_once 'models/UniteMesureModel.php';
require_once 'models/InfoBrqProduitModel.php';


class BrqController
{
    private static $config;

    private static $configErrors;
    
    /**
     * __construct : Constructeur de la classe BrqController
     *
     * @return void
     */
    public function __construct()
    {
        self::$config = require 'config/config.php';
        self::$configErrors = require 'config/config_errors.php';
    }
    
    /**
     * default : Fonction appelée par défaut par le routeur
     *
     * @return void
     */
    public function default()
    {
        $this->displayBrqInterface();
    }
    
    /**
     * filterBrqListByDate
     *
     * @param  array $brqList
     * @param  string $beginDate
     * @param  string $endDate
     * @return array
     */
    public function filterBrqListByDate($brqList, $beginDate, $endDate)
    {
        $filteredBrqList = array();
        foreach ($brqList as $brq)
        {
            if (strtotime($brq->getDateBrq()) >= strtotime($beginDate) && strtotime($brq->getDateBrq()) <= strtotime($endDate))
            {
                $filteredBrqList[] = $brq;
            }
        }
        return $filteredBrqList;
    }

    /**
     * displayBrqInterface : Affiche l'interface de gestion des produits brq
     *
     * @return void
     */
    public function displayBrqInterface()
    {
        if(isset($_SESSION['code_role']))
        {
            $brqList = BrqModel::getAllBrq();
            $beginDate = isset($_POST['begin_date']) ? htmlspecialchars($_POST['begin_date']) : self::$config['date_begin_brq'];
            $endDate = isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : self::$config['date_end_brq'];
            $brqList = self::filterBrqListByDate($brqList, $beginDate, $endDate);

            $brqNotFilled = array();
            $brqFilled = array();
            $date = $beginDate;

            while (strtotime($date) <= date ("Y-m-d", strtotime("-1 day", strtotime($beginDate))))
            {
                // On vérifie si la clé primaire se trouve dans le tableau
                // Un objet brq contient une fonction get_date()

                $brqFound = false;
                foreach ($brqList as $brq)
                {
                    if ($brq->getDateBrq() == $date)
                    {
                        $brqFound = true;
                        $brqFilled[] = $brq->getDateBrq();
                    }
                }

                if (!$brqFound)
                {
                    $brqNotFilled[] = $date;
                }

            
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
            $date = $endDate;
            
            $filter_list = isset($_POST['filter_list']) ? htmlspecialchars($_POST['filter_list']) : 'all';

            require_once 'views/brqInterface.php';
            unsetSessionVariables();
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url'].'index.php/home');
            exit();
        }
    }
    
    /**
     * ifIsFirstBrq : Vérifie si le brq est le premier de la base de données
     *
     * @return boolean
     */
    public function ifIsFirstBrq()
    {
        $brqList = BrqModel::getAllBrq();
        if (count($brqList) == 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    
    /**
     * addBrq : Ajoute un brq dans la base de données
     *
     * @return void
     */
    public function addBrq()
    {
        if(isset($_SESSION['code_role']))
        {
            $produits = ProduitModel::getAllProduits();
            $categoriesProduits = CategorieProduitModel::getAllCategoriesProduits();
            $unites = UniteMesureModel::getAllUnitesMesure();

            $isFirstBrq = self::ifIsFirstBrq();

            // Vérifier la date du brq en paramètre
            $date_brq = createValidDate($_GET['dateBrq']);

            if(!isDateValid($date_brq))
            {
                $erreur_msg = $date_brq;
                $_SESSION['error'] = $erreur_msg;
                header('Location: '.self::$config['base_url'].'index.php/brq');
                exit();
            }

            // Récupérer les unités de mesures en fonction des id
            $unitesParId = array();
            foreach ($unites as $unite)
            {
                $unitesParId[$unite->getIdUnite()] = $unite->getUniteNom();
            } 

            // Pour chaque catégorie, on récupère la liste des produits
            $produitsParCategorie = array();
            $objectifsParCategorie = array();
            foreach ($categoriesProduits as $categorieProduit)
            {
                $produitsParCategorie[$categorieProduit->getIdCategorie()] = array();
                $objectifsParCategorie[$categorieProduit->getIdCategorie()] = 0;
                foreach ($produits as $produit)
                {
                    if ($produit->getIdCategorie() == $categorieProduit->getIdCategorie())
                    {
                        $produitsParCategorie[$categorieProduit->getIdCategorie()][] = $produit;
                        $objectifsParCategorie[$categorieProduit->getIdCategorie()] += $produit->getObjectif();

                    }
                }
            }

            // On récupère les objectifs des catégories en ajoutant les objectifs des produits
            
            require_once 'views/brq1ProdVente.php';
            
            return;
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url'].'index.php/home');
            exit();
        }

    }

    public function createBrq()
    {
        if(isset($_SESSION['code_role']))
        {
            
            // Récupérer les produits
            $produits = ProduitModel::getAllProduits();
            $productsIdList = array();
            foreach ($produits as $produit)
            {
                $productsIdList[] = $produit->getIdProduit();
            }

            // Date du brq 
            $dateBrq = htmlspecialchars($_POST['date_brq']);
            $dateBrq = date("d/m/Y", strtotime($dateBrq));

            // Récupérer le nombre de BRQ pour cette année 
            $nbrBrq = BrqModel::getNbrBrqByYear(date("Y")) +1;

            // On va crée le brq
            $brq = new BRQ(
                $dateBrq, 
                date('d/m/Y'),
                $nbrBrq,
                date('d/m/Y'),
                null, 
                $_SESSION['id_util'],
                $_SESSION['id_util'],
                "incomplet-2"
            );

            // On va ajouter le brq dans la base de données
            $response = BrqModel::createBrq($brq);

            if(!$response)
            {
                $_SESSION['error'] = "Erreur lors de la création du brq ";
                // Erreur : Erreur lors de la création du brq
                header('Location: '.self::$config['base_url'].'index.php/brq');
                exit();
            }

            // Pour chaque produit, on va récupérer les données du tableau 
            foreach ($productsIdList as $idProduit)
            {
                $realisation = $_POST['realisationProduit'.$idProduit] != "" ? htmlspecialchars($_POST['realisationProduit'.$idProduit]) : 0;
                $objectif = $_POST['objectifProduit'.$idProduit] != "" ? htmlspecialchars($_POST['objectifProduit'.$idProduit]) : 0;
                $stockFinJournee = $_POST['stockFinProduit'.$idProduit] != "" ? htmlspecialchars($_POST['stockFinProduit'.$idProduit]) : 0;
                $quantite = $_POST['qteProduit'.$idProduit] != "" ? htmlspecialchars($_POST['qteProduit'.$idProduit]) : 0;
                $valeur = $_POST['valeurProduit'.$idProduit]!= "" ? htmlspecialchars($_POST['valeurProduit'.$idProduit]) : 0;

                $info_brq_produit = new InfoBrqProduit(
                    $realisation,
                    $objectif,
                    $stockFinJournee,
                    $quantite,
                    $valeur,
                    null,
                    $idProduit,
                    $dateBrq
                );
                $response = InfoBrqProduitModel::addInfoBrqProduit($info_brq_produit);

                if(!$response)
                {
                    $_SESSION['error'] = "Erreur lors de la création des informations du brq pour le produit ".$idProduit;
                    header('Location: '.self::$config['base_url'].'index.php/brq');
                    exit();
                }
                
            }
            
            $_SESSION['success'] = "La phase 1 du brq est un succès !"; 
            header('Location: '.self::$config['base_url'].'index.php/brq/infosPertesProd?dateBrq='.$dateBrq);
            exit();
            
            
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url'].'index.php/home');
            exit();
        }
    }

    /**
     * infosPertesProd : Afficher la page des infos pertes
     *
     * @return void
     */
    public function infosPertesProd()
    {
        unsetSessionVariables();
        if (isset($_SESSION['code_role']))
        {
            // Vérifier la date du brq en paramètre
            $date_brq = createValidDate($_GET['dateBrq']);

            if(!isDateValid($date_brq))
            {
                $erreur_msg = $date_brq;
                $_SESSION['error'] = $erreur_msg;
                header('Location: '.self::$config['base_url'].'index.php/brq');
                exit();
            }
            require_once 'views/brq2PerteProd.php';
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config["base_url"]); 
            exit();
        }
    }



    
}