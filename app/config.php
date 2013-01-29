<?php
/*
 * 2013 ArgetFwk
 * 
 * FrameWork MVC en phase de développement et d'amélioration
 * 
 * Utilisation des FrameWorks :
 *  - TwitterBootstrap 2.1
 *  - Doctrine 2
 *  - Twig
 * 
 * -------------------
 * 
 *
 * @author Florian MITHIEUX <florian.mithieux@gmail.com>
 * 
 * 
 * 
 * --------------------------------------------------------------------------------
 * CONFIGURATION DE VOTRE SITE INTERNET
 * --------------------------------------------------------------------------------
 */


/**
 * TimeZone du serveur
 */
define('TIMEZONE', 'Europe/Paris');

/**
 * Lancement de la gestion de chrono
 */
define('MICRO_TIME', microtime(TRUE));

/**
 * Le site comporte une BDD (false pour non)
 */
define('CONFIG_REQUIRE_BDD', FALSE);

/**
 * Le site est en mode intégration seule en Front Office (sans utiliser de PHP) : false pour integration
 */
define('CONFIG_DEV_PHP', FALSE);

/**
 * Utilisation du cache de Twig
 * Si oui : PATH_TO_IMPORTANT_FILES . 'lib/Resources/Twig/Cache/'
 */
define('TWIG_CACHE_PATH', FALSE);

/*
 * Utilisation du cache de ImageResizer
 */
define('IMAGE_RESIZER_CACHE', FALSE);

/**
 * Envoi d'email si erreur en dev/pre-prod
 */
define('ERROR_SEND_EMAIL', TRUE);

/**
 * Activation des logs
 */
define('ERROR_LOGS_ENABLED', TRUE);

/**
 * Constantes relatives à la societe
 */
define('SOCIETE_NOM', 'ArgetFwk');
define('SITE_NOM', 'ArgetFwk');

/**
 * Constantes relatives au client
 */
define('CLIENT_EMAIL', 'florian.mithieux@supinfo.com');
define('CLIENT_NOM', 'Mithieux');
define('CLIENT_PRENOM', 'Florian');
define('CLIENT_TEL', '0666812988');
define('CLIENT_LOGIN', 'admin');
define('CLIENT_PASSWORD', 'admin');

/**
 * Constantes relatives à l'admin
 */
define('ADMIN_EMAIL', 'florian.mithieux@gmail.com');
define('ADMIN_EMAIL_LISTING', serialize(array('florian.mithieux@gmail.com')));
define('ADMIN_NOM', 'M.');
define('ADMIN_PRENOM', 'Florian');
define('ADMIN_LOGIN', 'admin');
define('ADMIN_PASSWORD', 'admin');

/**
 * Méthode de cryptage
 * "SHA512", "BLOWFISH+" et "MD5" disponibles
 * Login Protect = Empêcher les brute force
 */
define('PASSWORD_METHOD', 'SHA512');
define('LOGIN_PROTECT', TRUE);

/**
 * Constantes de recuperation en HTTP
 */
if (isset($_GET['pattern']))
    define('GET_PATTERN', $_GET['pattern']);
else
    define('GET_PATTERN', NULL);

if (isset($_GET['content']))
    define('GET_CONTENT', $_GET['content']);
else
    define('GET_CONTENT', 'home');

/**
 * Constantes relatives au navigateur
 */
define('NAVIGATEUR_NOM', $_SERVER['HTTP_USER_AGENT']);

/**
 * Constante d'URL
 */
define('SITE_CURRENT_URI', $_SERVER['REQUEST_URI']);

/*
 * Définition des différents environnements de développement
 * --------------------------------------------------------------------------------
 */
if (preg_match("#localhost#", $_SERVER['HTTP_HOST'])) {

    /**
     * Environnement de developpement
     * 
     * @return boolean - True : dev / False : prod
     */
    define('ENV_DEV', TRUE);
    define('ENV_LOCALHOST', TRUE);

    /**
     * Génération de l'URL de base pour le local
     */
    define('SITE_URL_BASE', 'http://' . $_SERVER['HTTP_HOST'] . '/ArgetFwk/');
    define('SITE_URL', SITE_URL_BASE . BACKOFFICE_ACTIVE);
    define('SITE_URL_REFERENCEMENT', '');

    /*
     * Connexion à la BDD locale
     */
    define('PDO_PREFIX', 'db_');
    define('PDO_DRIVER', 'pdo_mysql');
    define('PDO_HOST', 'localhost');
    define('PDO_PORT', '');
    define('PDO_DATABASE_NAME', 'argetfwk');
    define('PDO_USER', 'root');
    define('PDO_PASSWORD', '');
} elseif (preg_match("#dev#", $_SERVER['HTTP_HOST'])) {

    /**
     * Environnement de developpement
     * 
     * @return boolean - True : dev / False : prod
     */
    define('ENV_DEV', TRUE);
    define('ENV_LOCALHOST', FALSE);
    
    /*
     * Génération de l'url de pre-prod
     */
    define('SITE_URL_BASE', 'http://yourwebsite.fr/');
    define('SITE_URL', SITE_URL_BASE . BACKOFFICE_ACTIVE);
    define('SITE_URL_REFERENCEMENT', '');
    /*
     * Connexion à la BDD pre-prod
     */
    define('PDO_PREFIX', 'db_');
    define('PDO_DRIVER', 'pdo_mysql');
    define('PDO_HOST', 'localhost');
    define('PDO_PORT', '');
    define('PDO_DATABASE_NAME', 'doctrineproject');
    define('PDO_USER', 'root');
    define('PDO_PASSWORD', '');
} else {

    /**
     * Environnement de production
     * 
     * @return boolean - True : dev / False : prod
     */
    define('ENV_DEV', TRUE);
    define('ENV_LOCALHOST', FALSE);

    /*
     * Génération de l'url de base Web
     */
    define('SITE_URL_BASE', 'http://argetfwk.fr/');
    define('SITE_URL', SITE_URL_BASE . BACKOFFICE_ACTIVE);
    define('SITE_URL_REFERENCEMENT', '');

    /*
     * Connexion à la BDD Web
     */
    define('PDO_PREFIX', 'db_');
    define('PDO_DRIVER', 'pdo_mysql');
    define('PDO_HOST', 'localhost');
    define('PDO_PORT', '');
    define('PDO_DATABASE_NAME', 'argetfwk');
    define('PDO_USER', '');
    define('PDO_PASSWORD', '');
}
?>
