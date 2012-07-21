<?php

abstract class PdoConnect {

    // Si site web sur internet
    private $DRIVER = 'mysql';
    private $HOST = 'localhost';
    private $PORT = '';
    private $DATABASE_NAME = 'YOURDBNAME';
    private $USER = 'YOURUSER';
    private $PASSWORD = 'YOURPASSWORD';
    protected $pdo;

    public function __construct() {

        // Si site web en local
        if (preg_match("#localhost#", $_SERVER['HTTP_HOST'])) {
            $this->DRIVER = 'mysql';
            $this->HOST = 'localhost';
            $this->PORT = '';
            $this->DATABASE_NAME = 'basesite';
            $this->USER = 'root';
            $this->PASSWORD = '';
        }

        $dsn = $this->DRIVER . ':host=' . $this->HOST . ';dbname=' . $this->DATABASE_NAME;
        try {
            $this->pdo = new PDO($dsn, $this->USER, $this->PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (PDOException $e) {
            throw new Exception('Connexion a la base de donnee impossible');
        }
    }

}

?>