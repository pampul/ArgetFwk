<?php

session_start();

if (isset($_POST['method']) && isset($_POST['controller'])) {

  define('BACKOFFICE_ACTIVE', 'gestion/');
  define('PATH_TO_IMPORTANT_FILES', __DIR__.'/../../');
  define('PATH_TO_BACKOFFICE_FILES', __DIR__.'/../');
  define('GET_METHOD', $_POST['method']);
  define('GET_CONTROLLER', $_POST['controller']);


  /**
   * Appel de la classe HTTP
   */
  require_once PATH_TO_IMPORTANT_FILES.'lib/Resources/Core/HttpCore.php';

  /*
   * Appel au fichier de configuration et des constantes
   */
  require_once PATH_TO_IMPORTANT_FILES . 'app/config.php';

  /*
   * Appel aux classes utiles du Fwk
   */
  require_once PATH_TO_IMPORTANT_FILES . 'lib/Resources/bin/FwkLoader.php';
  FwkLoader::getContext();



  /*
   * Choix du controller Ajax
   */
  switch (GET_CONTROLLER) {

    /*
     * Laisser le case url-error il permet le traitement de 404
     */
    case 'table'   :
    case 'chart'   :
    case 'login'   :
    case 'private' :
    case 'dashboard' :
      require_once PATH_TO_BACKOFFICE_FILES . 'controllers/ajax/DefaultAjax.php';
      new DefaultAjax();
      break;

    default :
      require_once PATH_TO_BACKOFFICE_FILES . 'controllers/DefaultController.php';
      new DefaultController();
      break;
  }
}
?>
