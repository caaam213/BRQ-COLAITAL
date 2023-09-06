<?php

require_once 'utilsController.php';
require_once 'models/BrqModel.php';
require_once 'models/ProduitModel.php';
require_once 'models/CategorieProduitModel.php';
require_once 'models/UniteMesureModel.php';
require_once 'models/InfoBrqProduitModel.php';
require_once 'models/BrqProduitPerteModel.php';


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
            if (strtotime($brq->getDateBrq()) >= strtotime($beginDate) 
            && strtotime($brq->getDateBrq()) <= strtotime($endDate))
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
        verifyAccesRoleCode();
        
        $isFirstBrq = self::ifIsFirstBrq();

        if(!$isFirstBrq)
        {
            $brqList = BrqModel::getAllBrq();
            $beginDate = isset($_POST['begin_date']) ? htmlspecialchars($_POST['begin_date']) : self::$config['date_begin_brq'];
            $endDate = isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : self::$config['date_end_brq'];
            $brqList = self::filterBrqListByDate($brqList, $beginDate, $endDate);

            $brqNotFilled = array();
            $brqFilled = array();
            $brqNotComplete = array();

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
                            
                        // On vérifie si le brq est complet
                        if($brq->getEtatBrq()!="complet")
                        {
                            $brqNotComplete[$brq->getDateBrq()] = self::$config['urlStatutBrq'][$brq->getEtatBrq()]["url"];
                        }
                        else
                        {
                            $brqFilled[] = $brq->getDateBrq();
                        }
                            
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
        }
            
        require_once 'views/brqInterface.php';
        unsetSessionVariables();
        
    }
    
    /**
     * ifIsFirstBrq : Vérifie si le brq est le premier de la base de données
     *
     * @return boolean
     */
    public function ifIsFirstBrq()
    {
        $brqList = BrqModel::getAllBrq();
        // On vérifie s'il existe à la valeur de begin_date
        $isFirstBrq = true;
        foreach ($brqList as $brq)
        {
            if ($brq->getDateBrq() == self::$config['date_begin_brq'])
            {
                $isFirstBrq = false;
            }
        }
        return $isFirstBrq;
    }

    
    /**
     * addOrModifyBrq : Ajoute un brq dans la base de données
     *
     * @return void
     */
    public function addOrModifyBrq()
    {
        verifyAccesRoleCode();
        $produits = ProduitModel::getAllProduits();
        $categoriesProduits = CategorieProduitModel::getAllCategoriesProduits();
        $unites = UniteMesureModel::getAllUnitesMesure();

        // Vérifier la date du brq en paramètre
        $date_brq = createValidDate($_GET['dateBrq']);

        if(!isDateValid($date_brq))
        {
            $erreur_msg = $date_brq;
            $_SESSION['error'] = $erreur_msg;
            header('Location: '.self::$config['base_url'].'index.php/brq');
            exit();
        }
        else
        {
            // Vérifier si le brq existe déjà
            $brq = BrqModel::getBrqByDate($date_brq);
            if($brq != null)
            {
                $brqProduitsArray = InfoBrqProduitModel::getInfosBrqProduitsByDate($date_brq);
            }
            
        }

        $isFirstBrq = self::ifIsFirstBrq();

       
        
        // On vérifie si le brq à la date précédente existe
        $previousBrq = null;
        if($date_brq != self::$config['date_begin_brq'])
        {
            $previousDate = date ("Y-m-d", strtotime("-1 day", strtotime($date_brq)));
            $previousBrq = BrqModel::getBrqByDate($previousDate);
            if($previousBrq != null)
            {
                $brqFound = true;
                $previousBrqProduitsArray = InfoBrqProduitModel::getInfosBrqProduitsByDate($previousDate);
            }
            else
            {
                $brqFound = false;
            }
        }
        else
        {
            
            $brqFound = true;
        }

            

        // Récupérer les unités de mesures en fonction des id
        $unitesParId = array();
        foreach ($unites as $unite)
        {
            $unitesParId[$unite->getIdUnite()] = [
                "nom" => $unite->getUniteNom(),
                "multiplicateur" => $unite->getMultiplicateur()
            ];
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
            
        require_once 'views/brq1ProdVente.php';
        unsetSessionVariables();
    }
    
    /**
     * createBrq : Ajoute un BRQ dans la base de données
     *
     * @return void
     */
    public function createBrq()
    {
        verifyAccesRoleCode();// Date du brq 

        $dateBrq = htmlspecialchars($_POST['date_brq']);
        $dateBrq = date("Y-m-d", strtotime($dateBrq));

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

        if($response == false)
        {
            $_SESSION['error'] = "Erreur lors de la création du brq ";
            // Erreur : Erreur lors de la création du brq
            header('Location: '.self::$config['base_url'].'index.php/brq');
            exit();
        }
        else
        {
            // Récupérer les produits
            $produits = ProduitModel::getAllProduits();
            $productsIdList = array();
            foreach ($produits as $produit)
            {
                $productsIdList[] = $produit->getIdProduit();
            }

            // Récupérer les informations des produits
            $infosProduits = array();
            foreach ($productsIdList as $idProduit)
            {
                $realisation = $_POST['realisationProduit'.$idProduit] != "" ? htmlspecialchars($_POST['realisationProduit'.$idProduit]) : 0;
                $objectif = $_POST['objectifProduit'.$idProduit] != "" ? htmlspecialchars($_POST['objectifProduit'.$idProduit]) : 0;
                $stockFinJournee = $_POST['stockFinProduit'.$idProduit] != "" ? htmlspecialchars($_POST['stockFinProduit'.$idProduit]) : 0;
                $quantite = $_POST['qteProduit'.$idProduit] != "" ? htmlspecialchars($_POST['qteProduit'.$idProduit]) : 0;
                $valeur = $_POST['valeurProduit'.$idProduit]!= "" ? htmlspecialchars($_POST['valeurProduit'.$idProduit]) : 0;
                $perteProd = $_POST['perteProduit'.$idProduit]!= "" ? htmlspecialchars($_POST['perteProduit'.$idProduit]) : 0;
                $debutJournee = $_POST['stocksDebutProduit'.$idProduit]!= "" ? htmlspecialchars($_POST['stocksDebutProduit'.$idProduit]) : 0;

                $info_brq_produit = new InfoBrqProduit(
                    $realisation,
                    $objectif,
                    $stockFinJournee,
                    $quantite,
                    $valeur,
                    null,
                    $idProduit,
                    $dateBrq,
                    $perteProd, 
                    $debutJournee

                );
                $infosProduits[] = $info_brq_produit;
                    
            }
            self::createBrqAddOrModifyInfosProducts($infosProduits, $dateBrq);
        }
    }
    
    /**
     * updateBrq : Modifie un BRQ dans la base de données
     *
     * @return void
     */
    public function updateBrq()
    {
        verifyAccesRoleCode();// Date du brq 

        $dateBrq = htmlspecialchars($_POST['date_brq']);
        $dateBrq = date("Y-m-d", strtotime($dateBrq));

        // Récupérer les produits
        $produits = ProduitModel::getAllProduits();
        $productsIdList = array();
        foreach ($produits as $produit)
        {
            $productsIdList[] = $produit->getIdProduit();
        }

        // Récupérer les informations des produits
        $infosProduits = array();
        foreach ($productsIdList as $idProduit)
        {
            $realisation = $_POST['realisationProduit'.$idProduit] != "" ? htmlspecialchars($_POST['realisationProduit'.$idProduit]) : 0;
            $objectif = $_POST['objectifProduit'.$idProduit] != "" ? htmlspecialchars($_POST['objectifProduit'.$idProduit]) : 0;
            $stockFinJournee = $_POST['stockFinProduit'.$idProduit] != "" ? htmlspecialchars($_POST['stockFinProduit'.$idProduit]) : 0;
            $quantite = $_POST['qteProduit'.$idProduit] != "" ? htmlspecialchars($_POST['qteProduit'.$idProduit]) : 0;
            $valeur = $_POST['valeurProduit'.$idProduit]!= "" ? htmlspecialchars($_POST['valeurProduit'.$idProduit]) : 0;
            $perteProd = $_POST['perteProduit'.$idProduit]!= "" ? htmlspecialchars($_POST['perteProduit'.$idProduit]) : 0;
            $debutJournee = $_POST['stocksDebutProduit'.$idProduit]!= "" ? htmlspecialchars($_POST['stocksDebutProduit'.$idProduit]) : 0;

            $info_brq_produit = new InfoBrqProduit(
                $realisation,
                $objectif,
                $stockFinJournee,
                $quantite,
                $valeur,
                null,
                $idProduit,
                $dateBrq,
                $perteProd,
                $debutJournee

            );
            $infosProduits[] = $info_brq_produit;
                
        }
        self::createBrqAddOrModifyInfosProducts($infosProduits, $dateBrq, "modify");


    }

    

    
    /**
     * createBrqAddOrModifyInfosProducts : Ajoute ou modifie les infos des produits dans la base de données
     *
     * @param  mixed $infosProduits
     * @param  mixed $dateBrq
     * @param  mixed $addOrModify
     * @return void
     */
    public function createBrqAddOrModifyInfosProducts($infosProduits, $dateBrq, $addOrModify="add")
    {
        verifyAccesRoleCode();

        if($addOrModify == "add")
        {
            // On ajoute les infos dans la base de données
            $response = InfoBrqProduitModel::addInfosBrqProduits($infosProduits);
            $errorMsg = "Erreur lors de l'ajout des infos des produits";
            $successMsg = "La phase 1 du brq est un succès !";
        }
        else
        {
            // On modifie les infos dans la base de données
            $response = InfoBrqProduitModel::updateInfosBrqProduit($infosProduits);
            $errorMsg = "Erreur lors de la modification des infos des produits";
            $successMsg = "La phase 1 du brq est un succès !";
        }

        if(!$response)
        {
            $_SESSION['error'] = $errorMsg;
            header('Location: '.self::$config['base_url'].'index.php/brq/addOrModifyBrq?dateBrq='.$dateBrq);
            exit();
        }

        $_SESSION['success'] = $successMsg; 
        header('Location: '.self::$config['base_url'].self::$config['urlStatutBrq']["incomplet-2"]["url"].'?dateBrq='.$dateBrq);
        exit();
        
    }
    
    



    
}