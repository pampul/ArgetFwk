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
 * Définition des variables de routing
 */
HttpCore::initialize();


/**
 * Le site est en mode intégration seule en Front Office (sans utiliser de PHP) : false pour integration
 */
define('CONFIG_DEV_PHP', FALSE);

/**
 * Utilisation du cache de Twig
 * Si oui : PATH_TO_IMPORTANT_FILES . 'lib/Resources/Twig/Cache/'
 */
define('TWIG_CACHE_PATH', FALSE);

/**
 * Envoi d'email si erreur en dev/pre-prod
 */
define('ERROR_SEND_EMAIL', TRUE);

/**
 * Activation des logs
 */
define('ERROR_LOGS_ENABLED', TRUE);

/**
 * Activation du refresh automatique en back office
 */
define('REFRESH_AUTO_BO', TRUE);

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
 * Définition des différents environnements de développement
 * --------------------------------------------------------------------------------
 */
if (HttpCore::isLocalhost()) {

  /**
   * Environnement de developpement
   *
   * @return boolean - True : dev / False : prod
   */
  define('ENV_DEV', TRUE);
  define('ENV_LOCALHOST', TRUE);

  /**
   * Connexion à la BDD locale
   */
  define('PDO_PREFIX', 'db_');
  define('PDO_DRIVER', 'pdo_mysql');
  define('PDO_HOST', 'localhost');
  define('PDO_PORT', '');
  define('PDO_DATABASE_NAME', 'argetfwk');
  define('PDO_USER', 'root');
  define('PDO_PASSWORD', 'root');


} elseif (HttpCore::isPreprodUrl('argetfwk')) {

  /**
   * Environnement de developpement
   *
   * @return boolean - True : dev / False : prod
   */
  define('ENV_DEV', TRUE);
  define('ENV_LOCALHOST', FALSE);

  /**
   * Connexion à la BDD pre-prod
   */
  define('PDO_PREFIX', 'db_');
  define('PDO_DRIVER', 'pdo_mysql');
  define('PDO_HOST', 'localhost');
  define('PDO_PORT', '');
  define('PDO_DATABASE_NAME', 'argetweb_argetfwk');
  define('PDO_USER', 'argetweb');
  define('PDO_PASSWORD', 'ArgetL0um');

} else {

  /**
   * Environnement de production
   *
   * @return boolean - True : dev / False : prod
   */
  define('ENV_DEV', FALSE);
  define('ENV_LOCALHOST', FALSE);

  /**
   * Suppression de l'affichage des erreurs
   */
  ini_set("display_errors", 'off');
  error_reporting(0);

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
