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


// Définition des variables de routing
HttpCore::initialize();
// Le site est en MVC obligatoire avec un controller nécessaire pour une vue
define('CONFIG_DEV_PHP', false);
// Utilisation du cache de Twig
// Si oui : PATH_TO_IMPORTANT_FILES . 'lib/Resources/Twig/Cache/'
define('TWIG_CACHE_PATH', false);
// Envoi d'email si erreur production
define('ERROR_SEND_EMAIL', true);
// Activation des logs
define('ERROR_LOGS_ENABLED', true);
// Activation du refresh automatique en back office après 10 sec d'inactivité
define('REFRESH_AUTO_BO', true);





// Constantes relatives à la societe
define('SOCIETE_NOM', 'ArgetFwk');
define('SITE_NOM', 'ArgetFwk');

// Constantes relatives aux clients
define('CLIENT_EMAIL', 'florian.mithieux@supinfo.com');
define('CLIENT_EMAIL_LISTING', serialize(array('florian.mithieux@supinfo.com')));
define('CLIENT_NOM', 'Mithieux');
define('CLIENT_PRENOM', 'Florian');

// Constantes relatives à l'administrateur
define('ADMIN_EMAIL', 'florian.mithieux@gmail.com');
define('ADMIN_EMAIL_LISTING', serialize(array('florian.mithieux@gmail.com')));
define('ADMIN_NOM', 'M.');
define('ADMIN_PRENOM', 'Florian');
define('ADMIN_PASSWORD', 'admin');


/**
 * Définition des différents environnements
 * --------------------------------------------------------------------------------
 *
 *
 *
 * Connexion à la BDD si environnement Localhost
 */
if (HttpCore::isLocalhost()) {

  define('ENV_DEV', true);
  define('ENV_LOCALHOST', true);

  define('PDO_PREFIX', 'db_');
  define('PDO_DRIVER', 'pdo_mysql');
  define('PDO_HOST', 'localhost');
  define('PDO_PORT', '');
  define('PDO_DATABASE_NAME', 'argetfwk');
  define('PDO_USER', 'root');
  define('PDO_PASSWORD', 'root');

  /**
   * --------------------------------------------------------------------------------
   * Connexion à la BDD en cas de PreProd (si l'url contient le mot ci-dessous)
   */
} elseif (HttpCore::isPreprodUrl('argetfwk')) {

  define('ENV_DEV', true);
  define('ENV_LOCALHOST', false);

  define('PDO_PREFIX', 'db_');
  define('PDO_DRIVER', 'pdo_mysql');
  define('PDO_HOST', 'localhost');
  define('PDO_PORT', '');
  define('PDO_DATABASE_NAME', 'argetweb_argetfwk');
  define('PDO_USER', 'argetweb');
  define('PDO_PASSWORD', '');

  /**
   * --------------------------------------------------------------------------------
   * Autrement, nous sommes en conditions de production
   */
} else {

  // Suppression de l'affichage des erreurs
  ini_set("display_errors", 'off');
  error_reporting(0);

  define('ENV_DEV', false);
  define('ENV_LOCALHOST', false);

  define('PDO_PREFIX', 'db_');
  define('PDO_DRIVER', 'pdo_mysql');
  define('PDO_HOST', 'localhost');
  define('PDO_PORT', '');
  define('PDO_DATABASE_NAME', 'argetfwk');
  define('PDO_USER', '');
  define('PDO_PASSWORD', '');
}
?>
