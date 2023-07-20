<?php
class ConnexionDB
{
    private static $instance = null;
    private $connection;
    
    /**
     * 
     * Constructeur de la classe ConnexionDB
     * @return void 
     */
    private function __construct()
    {
        $config = require 'config/config.php';
        $dsn = 'sqlsrv:Server='.$config['host'].';Database='.$config['db'];
        $user = $config['username'];
        $password = $config['password'];

        try {
            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    
    /**
     * Singleton qui permet de créer une seule instance de la classe ConnexionDB
     * @return ConnexionDB
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Retourne la connexion à la base de données
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
?>