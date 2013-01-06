<?php

/**
 * Classe d'archivage de fichiers de logs
 *
 * @author f.mithieux
 */
class cron extends SecuredClass {

    /**
     *
     * @var array $multiplePaths
     */
    private $simplePath;

    /**
     *
     * @var array $multiplePaths
     */
    private $multiplePaths;

    /**
     *
     * @var array $arrayPaths
     */
    private $arrayPathFiles;

    public function execute() {

        $this->startLog();

        $this->multiplePaths = array(PATH_TO_IMPORTANT_FILES . 'logs/');
        $this->simplePath = array();
        $this->arrayPathFiles = $this->getFiles();
        // Archiver les fichiers du mois précédent
        $this->archiveForMonth();

        $this->endLog();
    }

    private function startLog() {

        $message = '
    

--------------------------------------------------
              LANCEMENT DU CRON - ARCHIVAGE
--------------------------------------------------

';

        FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');
    }

    /**
     * Récupère l'ensemble des URLs intégrales des fichiers
     * 
     * @return array
     */
    private function getFiles() {

        $message = 'Starting read dir ...
 
----------------------------
';
        FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');

        $arrayResults = array();
        foreach ($this->multiplePaths as $onePath) {
            $arrayOtherPaths = FwkUtils::getDirList($onePath);
            foreach ($arrayOtherPaths as $oneOtherPath) {
                if (is_dir($onePath . $oneOtherPath)) {
                    $arrayFiles = FwkUtils::getDirList($onePath . DIRECTORY_SEPARATOR . $oneOtherPath);
                    foreach ($arrayFiles as $oneFile) {
                        if (is_file($onePath . $oneOtherPath . DIRECTORY_SEPARATOR . $oneFile)) {
                            $arrayResults[] = array('path' => $onePath . $oneOtherPath . DIRECTORY_SEPARATOR, 'complete' => $onePath . $oneOtherPath . DIRECTORY_SEPARATOR . $oneFile, 'simple' => $oneFile);
                            $message = 'File : ' . $onePath . $oneOtherPath . DIRECTORY_SEPARATOR . $oneFile . ' added.';
                            FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');
                        }
                    }
                }
            }
        }

        foreach ($this->simplePath as $onePath) {
            $arrayOtherPaths = FwkUtils::getDirList($onePath);
            foreach ($arrayOtherPaths as $oneFile) {
                if (is_file($onePath . $oneFile)) {
                    $arrayResults[] = array('path' => $onePath, 'complete' => $onePath . $oneFile, 'simple' => $oneFile);
                    $message = 'File : ' . $onePath . $oneFile . ' added.';
                    FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');
                }
            }
        }

        $message = '

------------------------------            

 - ' . sizeof($arrayResults) . ' files founded.';
        FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');

        return $arrayResults;
    }

    private function archiveForMonth() {

        $message = '
            
------------------------------

 -- ARCHIVAGE DES FICHIERS --
 
------------------------------

';
        FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');

        $month = date('m');

        foreach ($this->arrayPathFiles as $arrayFile) {

            if (preg_match('#^([0-9]+)-([0-9]+)-([0-9]+)([a-zA-Z0-9@ -_]+)*\.log$#', $arrayFile['simple'], $matches)) {
                if ($month != $matches[2]) {
                    FwkUtils::getDir($arrayFile['path'], '_ARCHIVES/' . $matches[1] . '/' . $matches[2] . '_' . DateUtils::getNumMonthToFrenchLetter($matches[2]) . '/');
                    copy($arrayFile['complete'], $arrayFile['path'] . '_ARCHIVES' . '/' . $matches[1] . '/' . $matches[2] . '_' . DateUtils::getNumMonthToFrenchLetter($matches[2]) . '/' . $arrayFile['simple']);
                    $message = 'Copying ' . $arrayFile['complete'] . ' to ' . $arrayFile['path'] . '_ARCHIVES' . DIRECTORY_SEPARATOR . $matches[1] . DIRECTORY_SEPARATOR . $matches[2] . '_' . DateUtils::getNumMonthToFrenchLetter($matches[2]) . DIRECTORY_SEPARATOR . $arrayFile['simple'];
                    unlink($arrayFile['complete']);
                }else
                    $message = 'Same month.';
            }else
                $message = 'File name incorrect ...';

            FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');
        }
    }

    private function endLog() {

        $message = '
    

--------------------------------------------------
           CRON TERMINE AVEC SUCCES
--------------------------------------------------

';
        FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');

        echo 'BATCH TERMINE.';
    }

}

?>