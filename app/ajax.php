<?php

session_start();

if (isset($_POST['method']) && isset($_POST['controller'])) {

  define('BACKOFFICE_ACTIVE', '');
  define('PATH_TO_IMPORTANT_FILES', __DIR__.'/../');
  define('PATH_TO_BACKOFFICE_FILES', __DIR__.'/../gestion/');
  define('GET_METHOD', $_POST['method']);
  define('GET_CONTROLLER', $_POST['controller']);


  /**
   * Appel de la classe HTTP
   */
  require_once PATH_TO_IMPORTANT_FILES.'app/HttpCore.php';

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

    default :
      require_once PATH_TO_IMPORTANT_FILES . 'controllers/DefaultController.php';
      new DefaultController();
      break;
  }
}
?>
