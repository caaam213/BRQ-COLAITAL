<?php

/**
 * Utilisateur : Classe représentant un utilisateur dans la base de données
 * Exemple de valeur : (3, "Admin", "Jean", "jean@gmail.com", "mdp crypté", 1, "ADMIN")
 */
class Utilisateur
{
    private $id;
    private $nom;
    private $prenom;
    private $adresseMail;
    private $mdp;
    private $aAcces;
    private $codeRole;
    
    /**
     * __construct : Constructeur de la classe Utilisateur
     *
     * @param  mixed $id
     * @param  string $nom
     * @param  string $prenom
     * @param  string $adresseMail
     * @param  string $mdp
     * @param  bool|int $aAcces
     * @param  string $codeRole
     * @return void
     */
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
     * getId : Retourne l'id de l'utilisateur
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * getNom : Retourne le nom de l'utilisateur
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    
    /**
     * getPrenom : Retourne le prénom de l'utilisateur
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }
    
    /**
     * getAdresseMail : Retourne l'adresse mail de l'utilisateur
     * @return string
     */
    public function getAdresseMail()
    {
        return $this->adresseMail;
    }
    
    /**
     * getMdp : Retourne le mot de passe de l'utilisateur encrypté
     * @return string
     */
    public function getMdp()
    {
        return $this->mdp;
    }
    
    /**
     * getAAcces : Retourne si l'utilisateur a accès ou non
     * @return int
     */
    public function getAAcces()
    {
        return $this->aAcces;
    }
    
    /**
     * getCodeRole : Retourne le code du rôle de l'utilisateur
     * @return string
     */
    public function getCodeRole()
    {
        return $this->codeRole;
    }
}
    