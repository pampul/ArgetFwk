<?php

/**
 *
 * Controller par defaut
 * Le controller doit absolument heriter de ControllerManager
 */
class PrivateController extends ControllerManager {

  /**
   * Affichage de la page d'accueil des admins
   *
   * @return view
   */
  protected function adminsController() {

    $arrayActionButtons = array('edit' => array('link' => 'private/admins-gestion'), 'delete' => array('link' => 'private/admins-delete'));
    $arrayContentTable = array('admin' => array('#' => 'id', 'Privilège' => array('class' => 'privilege', 'getter' => 'privilege', 'method' => 'getTableName', 'sort' => 'nom'), 'Nom' => 'nom', 'Prenom' => 'prenom', 'Fonction' => 'fonction', 'Email' => 'email'));
    $arraySearchTable = array('placeholder' => 'Nom, prénom ...', 'autocomplete' => true, 'champs' => array('nom', 'prenom'));

    $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
    $objFwkTable->buildHead();
    $objFwkTable->buildBody();
    $objFwkTable->buildSearch($arraySearchTable);
    $objFwkTable->build();

    $this->renderView('views/admins.html.twig', array(
      'tableFwk' => $objFwkTable
    ));
  }

  /**
   * Affichage du PHP info
   */
  protected function phpinfoController() {

    phpinfo();
  }

  protected function backupController() {

    $filePath = __DIR__.'/../__backups/' . date('Y-m-d') . '_backup_DB.bkp';
    $cmd = 'mysqldump --opt -h ' . PDO_HOST . ' --user=' . PDO_USER . ' --password=' . PDO_PASSWORD . ' ' . PDO_DATABASE_NAME . ' > ' . $filePath;


    if (!is_dir(__DIR__.'/../__backups'))
      mkdir(__DIR__.'/../__backups');

    exec($cmd);

    $this->renderView('views/backup.html.twig', array(
      'query' => $cmd,
      'env_localhost' => ENV_LOCALHOST,
      'link' => $filePath
    ));
  }



  protected function archiveController(){

    require_once PATH_TO_IMPORTANT_FILES.'app/secured/lib/SecuredClass.php';

    require_once PATH_TO_IMPORTANT_FILES.'app/secured/cron/archive.php';

    $object = new cron($this->em);
    $object->execute();


  }

}

?>