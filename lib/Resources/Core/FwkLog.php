<?php

/**
 * Classe de génération de logs d'erreurs PHP
 *
 * @author f.mithieux
 */
class FwkLog extends FwkManager {

  /**
   * Le chemin du fichier de logs
   * @var string
   */
  private $_log_path = null;

  /**
   * Tableau des messages de logs à créer
   * @var array
   */
  private $_log = array();

  /**
   * Log constructeur : Permet de définir le chemin des fichiers de logs
   *
   * @param string $path
   *  Le chemin de l'emplacement où les fichiers de logs doivent être écrits
   */
  public function __construct($path) {
    $this->setLogPath($path);
  }

  /**
   * Setter du `$_log_path`.
   *
   * @param string $path
   *  Le chemin de l'emplacement où les fichiers de logs doivent être écrits
   */
  public function setLogPath($path) {
    if(preg_match('#^log#', $path))
      $path = __DIR__.'/../../../'.$path;
    $this->_log_path = $path;
  }

  /**
   * Getter du `$_log_path`.
   *
   * @return string
   */
  public function getLogPath() {
    return $this->_log_path;
  }

  /**
   * Getter du `$_log`.
   *
   * @return array
   */
  public function getLog() {
    return $this->_log;
  }

  /**
   * Récupère le type d'erreur géré si il existe, autrement retourne UNKNOWN
   *
   * @param integer $type
   *  L'erreur PHP
   * @return string
   *  Si type non-defini : UNKNOWN
   */
  private function __defineNameString($type) {
    if (isset(FwkErrorHandler::$errorTypeStrings[$type])) {
      return FwkErrorHandler::$errorTypeStrings[$type];
    }

    return 'UNKNOWN';
  }

  /**
   * Retourne le dernier message de logs
   *
   * @return array
   *  Returns an associative array of a log message, containing the type of the log
   *  message, the actual message and the time at the which it was added to the log
   */
  public function popFromLog() {
    if (!empty($this->_log))
      return array_pop($this->_log);

    return false;
  }

  /**
   * Ajoute aux logs un tableau contenant le log et peut ajouter un message de log au fichier
   *
   * @param string $message
   * Message d'erreur
   * @param type $type
   * Type d'erreur (ex: E_NOTICE)
   * @param type $writeToLog
   * Si true : on écrit dans les logs
   * @param type $addbreak
   * Si true : on saute une ligne après
   */
  public function pushToLog($message, $type = E_NOTICE, $writeToLog = false, $addbreak = true) {

    array_push($this->_log, array('type' => $type, 'time' => time(), 'message' => $message));

    if (BACKOFFICE_ACTIVE === '')
      $boFo = 'Front Office';
    else
      $boFo = 'Back Office';

    $userName = '-';
    if (isset($_SESSION['admin']))
      $userName = $_SESSION['admin']['name'];

    $messageLog = date('d/m/Y H:i') . ' | ' . FwkUtils::getMemoryUsage() . ' | ' . $boFo . ' | ' . $userName . ' >> ' . $this->__defineNameString($type) . ' : ' . $message;

    if ($writeToLog)
      $this->writeToLog($messageLog, $addbreak);
  }

  /**
   * Ecrit le message dans le fichier donné
   *
   * @param string $message
   *  Le message à ajouter
   * @param boolean $addbreak
   *  Saute une ligne avant écriture
   * @return boolean
   *  Retourne true si message écrit avec succès
   */
  public function writeToLog($message, $addbreak = true) {

    if (file_exists($this->_log_path) && !is_writable($this->_log_path)) {
      $this->pushToLog('Impossible d\'écrire dans le fichier de logs. Le fichier est illisible.');
      return false;
    }
    return file_put_contents($this->_log_path, $message . ($addbreak ? PHP_EOL : ''), FILE_APPEND);
  }

  /**
   * Creation du fichier de logs
   *
   * @param boolean $overwrite
   * Supprimer l'ancien fichier ou non
   * @param type $mode
   * 0777 de base
   * @return int
   * Si 1 : fichier créé
   * Si 2 : fichier déjà existant
   */
  public function createLog($overwrite = false, $mode = 0777) {

    if (!file_exists($this->_log_path))
      $overwrite = true;

    if ($overwrite) {
      if (file_exists($this->_log_path) && is_writable($this->_log_path)) {
        unlink($this->_log_path);
      }

      $this->writeToLog('============================================', true);
      $this->writeToLog('Creation du log: ' . date('d/m/Y H:i'), true);
      $this->writeToLog('============================================', true);
      $this->writeToLog('', true, true);
      $this->writeToLog('', true, true);

      chmod($this->_log_path, intval($mode, 8));

      return 1;
    }

    return 2;
  }

  /**
   * Ecrit les lignes de fin du fichier
   */
  public function close() {
    $this->writeToLog('', true);
    $this->writeToLog('', true);
    $this->writeToLog('============================================', true);
    $this->writeToLog('Fermeture du log: ' . date('d/m/Y H:i'), true);
    $this->writeToLog("============================================" . PHP_EOL . PHP_EOL, true);
  }

  /**
   * Ajouter un message de log
   *
   * @param string $message
   * @param string $directory - par défaut : ../logs/
   * @param string $subDirectory - Choix du dossier de destination
   */
  public static function add($message, $directory = '../logs/', $subDirectory = '') {

    if($directory == '../logs/' || $directory == 'logs/')
      $directory = __DIR__.'/../../../logs/';

    /*
     * On vérifie la directory et on la créé si besoin
     */
    $directory = FwkUtils::getDir($directory, $subDirectory, true);

    /*
     * Le nom du fichier comporte la date du jour
     */
    $fileName = date('Y-m-d') . '.log';

    /*
     * Chemin complet
     */
    $filePath = $directory . $fileName;

    /*
     * Message puis retour à la ligne
     */
    $time = date('H:i:s');

    /*
     * Recherche de l'utilisateur pour logger ses actions
     */
    $userName = '-';
    if (isset($_SESSION['admin']))
      $userName = $_SESSION['admin']['name'];

    $message = $time . ' | ' . FwkUtils::getMemoryUsage() . ' | ' . $_SERVER['REMOTE_ADDR'] . ' | ' . $userName . ' >> ' . $message . '' . PHP_EOL;

    /*
     * On écrit dans le fichier
     */
    $file = fopen($filePath, "a+");
    fwrite($file, $message);
    fclose($file);
  }

  /**
   * Fonction permettant de lister les fichiers de logs d'une directory :
   *
   * @param array $orderCrits - Tableau regroupant les critÃ¨res de sÃ©lection :
   * + dirFiles
   */
  public static function getLogFiles($orderCrits = array()) {

    if (!isset($orderCrits['dirFiles']))
      $orderCrits['dirFiles'] = 'errors';

    return FwkUtils::getDirList(PATH_TO_IMPORTANT_FILES . 'logs/' . $orderCrits['dirFiles']);
  }

  /**
   * Fonction permettant de lire un fichier en fonction des critÃ¨res suivants :
   *
   * @param array $orderCrits - Tableau regroupant les critÃ¨res de sÃ©lection :
   * + dirFile
   * + fileName
   * + dateMin
   * + dateMax
   * @return array retourne chaque ligne du fichier
   */
  public static function getContentLogFile($orderCrits = array()) {

    $arrayLines = file(PATH_TO_IMPORTANT_FILES . 'logs/' . $orderCrits['dirFile'] . '/' . $orderCrits['fileName'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $arrayLines = array_reverse($arrayLines);
    return $arrayLines;
  }

  /**
   * Log la suppression d'une entité
   *
   * @param object $entity The entity instance to remove.
   */
  public static function logRemovedEntity($entity) {

    if(method_exists($entity, '__toString')) $name = ' - Infos supp. : ' . $entity;
    else $name = '';

    $messageLog = ' Suppression de l\'entite "' . get_class($entity) . '" avec l\'id : ' . $entity->getId() . $name;


    FwkLog::add($messageLog, PATH_TO_IMPORTANT_FILES . 'logs/', 'delete/');
  }

}

?>
