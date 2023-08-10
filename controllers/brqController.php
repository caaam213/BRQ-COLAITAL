<?php

require_once 'utilsController.php';
require_once 'models/BrqModel.php';
require_once 'models/ProduitModel.php';
require_once 'models/CategorieProduitModel.php';
require_once 'models/UniteMesureModel.php';

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

            // Vérification du format de la date
            if (isset($_GET['dateBrq']) && !empty($_GET['dateBrq']))
            {
                if(!isDateValid($_GET['dateBrq']))
                {
                    $_SESSION['error'] = "La date n'est pas dans le bon format"; // Erreur : La date n'est pas au bon format
                    header('Location: '.self::$config['base_url'].'index.php/brq');
                    return;
                }
                $date_brq = htmlspecialchars($_GET['dateBrq']);
                $date_brq = date("Y-m-d", strtotime($date_brq));
            }
            else
            {
                $_SESSION['error'] = "La date n'est pas dans le bon format"; // Erreur : La date n'est pas au bon format
                header('Location: '.self::$config['base_url'].'index.php/brq');
                return;
            }

            // Vérifier si la date est dans le bon intervalle
            if (strtotime($date_brq) < strtotime(self::$config['date_begin_brq']) || strtotime($date_brq) > strtotime(self::$config['date_end_brq']))
            {
                $_SESSION['error'] = "La date n'est pas dans l'intervalle autorisé "+$date_brq; // Erreur : La date n'est pas dans l'intervalle autorisé
                header('Location: '.self::$config['base_url'].'index.php/brq');
                return;
            }

            // Vérifier si le brq existe déjà
            $brqList = BrqModel::getAllBrq();
            foreach ($brqList as $brq)
            {
                if ($brq->getDateBrq() == $date_brq)
                {
                    $_SESSION['error'] = "Le brq existe déjà"; // Erreur : Le brq existe déjà
                    header('Location: '.self::$config['base_url'].'index.php/brq');
                    return;
                }
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
        }
        else
        {
            $_SESSION['error'] = self::$configErrors['1003']; // Erreur : Vous devez être connecté pour accéder à cette page
            header('Location: '.self::$config['base_url'].'index.php/home');
        }

    }
}