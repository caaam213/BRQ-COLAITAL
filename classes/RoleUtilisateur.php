<?php

/**
 * RoleUtilisateur : Classe représentant le rôle d'un utilisateur dans la base de données
 * Exemple de valeur : (ADMIN : Administrateur)
 */
class RoleUtilisateur
{
    private $codeRole;
    private $nomRole;
    
    /**
     * __construct : Constructeur de la classe RoleUtilisateur
     *
     * @param  string $code
     * @param  string $nom
     * @return void
     */
    public function __construct($code, $nom)
    {
        $this->codeRole = $code;
        $this->nomRole = $nom;
    }
    
    /**
     * getCodeRole : Retourne le code du rôle de l'utilisateur
     *
     * @return string
     */
    public function getCodeRole()
    {
        return $this->codeRole;
    }
    
    /**
     * setCodeRole : Change la valeur du code du rôle de l'utilisateur
     *
     * @param  string $code
     * @return void
     */
    public function setCodeRole($code)
    {
        $this->codeRole = $code;
    }
    
    /**
     * getNomRole : Retourne le nom du rôle de l'utilisateur
     *
     * @return string
     */
    public function getNomRole()
    {
        return $this->nomRole;
    }
    
    /**
     * setNomRole : Change la valeur du nom du rôle de l'utilisateur
     *
     * @param  string $nom
     * @return void
     */
    public function setNomRole($nom)
    {
        $this->nomRole = $nom;
    }
}
