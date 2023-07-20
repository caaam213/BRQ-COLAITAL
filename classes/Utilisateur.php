<?php

class Utilisateur
{
    private $id;
    private $nom;
    private $prenom;
    private $adresseMail;
    private $mdp;
    private $aAcces;
    private $codeRole;

    public function __construct($id, $nom, $prenom, $adresseMail, $mdp, $aAcces, $codeRole)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresseMail = $adresseMail;
        $this->mdp = $mdp;
        $this->aAcces = $aAcces;
        $this->codeRole = $codeRole;
    }
    
    /**
     * Retourne l'id de l'utilisateur
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Retourne le nom de l'utilisateur
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    
    /**
     * Retourne le prénom de l'utilisateur
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }
    
    /**
     * Retourne l'adresse mail de l'utilisateur
     * @return string
     */
    public function getAdresseMail()
    {
        return $this->adresseMail;
    }
    
    /**
     * Retourne le mot de passe de l'utilisateur encrypté
     * @return string
     */
    public function getMdp()
    {
        return $this->mdp;
    }
    
    /**
     * Retourne si l'utilisateur a accès ou non
     * @return int
     */
    public function getAAcces()
    {
        return $this->aAcces;
    }
    
    /**
     * Retourne le code du rôle de l'utilisateur
     * @return string
     */
    public function getCodeRole()
    {
        return $this->codeRole;
    }
}
    