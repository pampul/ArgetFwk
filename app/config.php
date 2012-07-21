<?php

/* 
 * CONFIGURATION DE VOTRE SITE INTERNET
 * --------------------------------------------------------------------------------
 */


/*
 * Constantes relatives à la societe
 */
define('SOCIETE_NOM', 'ArgetWeb');

/*
 * Constantes relatives à l'admin
 */
define('ADMIN_EMAIL', 'florian.mithieux@supinfo.com');
define('ADMIN_NOM', 'Mithieux');
define('ADMIN_PRENOM', 'Florian');

/*
 * Constantes relatives au navigateur
 */
define('NAVIGATEUR_NOM', $_SERVER['HTTP_USER_AGENT']);




/*
 * Définition des différends environnements de développement
 * --------------------------------------------------------------------------------
 */
if(preg_match("#localhost#", $_SERVER['HTTP_HOST'])){
    
    /*
     * Nom de l'environnement
     */
    define('ENV_DEV', true);
    
    /*
     * Génération de l'URL de base pour le local
     */
    define('SITE_URL', 'http://localhost:8080/ArgetFwk/');
    define('SITE_URL_REFERENCEMENT', 'index.php?content=');
    /*
     * Connexion à la BDD locale
     */
    define('PDO_DRIVER', 'pdo_mysql');
    define('PDO_HOST', 'localhost');
    define('PDO_PORT', '');
    define('PDO_DATABASE_NAME', 'doctrineproject');
    define('PDO_USER', 'root');
    define('PDO_PASSWORD', '');
    
    
}elseif(isset($_GET['dev_env'])){
    
    /*
     * Nom de l'environnement
     */
    define('ENV_DEV', true);
    
    /*
     * Génération de l'url de pre-prod
     */
    define('SITE_URL', 'http://www.YourPreProdUrl.fr/');
    define('SITE_URL_REFERENCEMENT', 'index.php?content=');
    /*
     * Connexion à la BDD pre-prod
     */
    define('PDO_DRIVER', 'pdo_mysql');
    define('PDO_HOST', 'localhost');
    define('PDO_PORT', '');
    define('PDO_DATABASE_NAME', 'doctrineproject');
    define('PDO_USER', 'root');
    define('PDO_PASSWORD', '');
    
    
}else{
    
    /*
     * Nom de l'environnement
     */
    define('ENV_DEV', false);
    
    /*
     * Génération de l'url de base Web
     */
    define('SITE_URL', 'http://www.YourWebUrl.fr/');
    define('SITE_URL_REFERENCEMENT', 'index.php?content=');
    /*
     * Connexion à la BDD Web
     */
    define('PDO_DRIVER', 'pdo_mysql');
    define('PDO_HOST', 'localhost');
    define('PDO_PORT', '');
    define('PDO_DATABASE_NAME', 'doctrineproject');
    define('PDO_USER', 'root');
    define('PDO_PASSWORD', '');
    
    
}

?>