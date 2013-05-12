<?php
// Définition des variables de routing
HttpCore::initialize();


// Le site est en MVC obligatoire avec un controller nécessaire pour une vue
define('CONFIG_DEV_PHP', false);
// Utilisation du cache de Twig
define('TWIG_CACHE_ACTIVE', false);
// Envoi d'email si erreur en production
define('ERROR_SEND_EMAIL', true);
// Activation des logs
define('ERROR_LOGS_ENABLED', true);
// Activation du refresh automatique en back office après 10 sec d'inactivité
define('REFRESH_AUTO_BO', true);
// Le site autorise l'aide un peu plus poussée en pre-prod (problèmes de DB plus explicite etc..)
define('PRE_PROD_ALLOW_HELP', true);



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
 * Connexion à la BDD si Localhost
 */
if (HttpCore::isLocalhost()) {

  // Affichage des erreurs
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
  ini_set('html_errors', 'On');

  define('ENV_DEV', true);
  define('ENV_LOCALHOST', true);

  // Configuration de la DB DEV
  define('PDO_PREFIX', 'db_');
  define('PDO_DRIVER', 'pdo_mysql');
  define('PDO_HOST', 'localhost');
  define('PDO_PORT', '');
  define('PDO_DATABASE_NAME', 'argetfwk2');
  define('PDO_USER', 'root');
  define('PDO_PASSWORD', 'root');

  /**
   * --------------------------------------------------------------------------------
   * Connexion à la BDD en cas de PreProd (si l'url contient le mot ci-dessous)
   */
} elseif (HttpCore::isPreprodUrl('argetfwk')) {

  // Affichage des erreurs
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
  ini_set('html_errors', 'On');

  define('ENV_DEV', true);
  define('ENV_LOCALHOST', false);

  // Configuration de la DB PRE-PROD
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
  error_reporting(0);
  ini_set("display_errors", 'off');
  ini_set('html_errors', 'Off');

  define('ENV_DEV', false);
  define('ENV_LOCALHOST', false);

  // Configuration de la DB PROD
  define('PDO_PREFIX', 'db_');
  define('PDO_DRIVER', 'pdo_mysql');
  define('PDO_HOST', 'localhost');
  define('PDO_PORT', '');
  define('PDO_DATABASE_NAME', 'argetfwk');
  define('PDO_USER', '');
  define('PDO_PASSWORD', '');
}
?>
